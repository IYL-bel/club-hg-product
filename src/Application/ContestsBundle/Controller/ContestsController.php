<?php
/**
 * Club Hg-Product
 *
 * Contests controller
 *
 * @package    ApplicationContestsBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ContestsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;

use Application\ContestsBundle\Form\Type\AddContestsMember as AddContestsMemberForm;
use Application\ContestsBundle\Entity\ContestsMembers;
use Application\ContestsBundle\Entity\ContestsMembersPhotos;


/**
 * @Security("has_role('ROLE_USER')")
 *
 * Application\ContestsBundle\Controller\ContestsController
 */
class ContestsController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\ContestsBundle\Repository\Contests */
        $contestsRepository = $em->getRepository('ApplicationContestsBundle:Contests');

        $contests = $contestsRepository->findBy( array(), array('createdAt' => 'DESC') );

        return array(
            'contests' => $contests
        );
    }

    /**
     * @param \Application\ContestsBundle\Controller\Request $request
     * @param int $idContest
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function responseMemberAction(Request $request, $idContest)
    {
        $success = false;
        $isContestsMember = false;
        $formView = null;
        $addedFiles = array();

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\ContestsBundle\Repository\Contests */
        $contestsRepository = $em->getRepository('ApplicationContestsBundle:Contests');
        /** @var $contest \Application\ContestsBundle\Entity\Contests */
        $contest = $contestsRepository->findOneBy( array('id' => $idContest) );

        /** @var $contestsMemberRepository \Application\ContestsBundle\Repository\ContestsMembers */
        $contestsMemberRepository = $em->getRepository('ApplicationContestsBundle:ContestsMembers');
        $contestsMember = $contestsMemberRepository->findOneBy(array(
            'contest' => $contest,
            'user' => $this->getUser(),
        ));

        if ($contestsMember) {
            $isContestsMember = true;
        } else {
            $contestsMember = new ContestsMembers();
            $contestsMember->setUser( $this->getUser() );
            $contestsMember->setContest($contest);
            $contestsMember->setStatus($contestsMemberRepository::STATUS_NEW);

            $form = $this->createForm(new AddContestsMemberForm(), $contestsMember);
            $form->handleRequest($request);

            $addedFilesName = $request->request->get('fileName');
            $addedFilesNameOriginal = $request->request->get('fileNameOriginal');
            $addedFilesDescription = $request->request->get('fileDescription');

            if ( count($addedFilesName) ) {
                foreach($addedFilesName as $key => $val) {
                    $addedFiles[$key] = array(
                        'fileName' => $addedFilesName[$key],
                        'fileNameOriginal' => $addedFilesNameOriginal[$key],
                        'linkFile' => ContestsMembersPhotos::getWebPath() . '/' . $addedFilesName[$key],
                        'fileDescription' => $addedFilesDescription[$key],
                    );
                }
            }

            if ( $form->isValid() ) {

                /** @var $contestsMember \Application\ContestsBundle\Entity\ContestsMembers */
                $contestsMember = $form->getData();
                $em->persist($contestsMember);
                $em->flush();

                $contestsMembersPhotos = array();
                if ( count($addedFiles) ) {
                    foreach ($addedFiles as $addedFile) {
                        $itemContestsMembersPhotos = new ContestsMembersPhotos();
                        $itemContestsMembersPhotos->setDescription( $addedFile['fileDescription'] );
                        $itemContestsMembersPhotos->setFileName( $addedFile['fileNameOriginal'] );
                        $itemContestsMembersPhotos->setFilePath( $addedFile['fileName'] );
                        $itemContestsMembersPhotos->setContestsMember($contestsMember);
                        $contestsMembersPhotos[] = $itemContestsMembersPhotos;
                    }
                }
                $contestsMember->setContestsMembersPhotos($contestsMembersPhotos);

                $em->persist($contestsMember);
                $em->flush();

                $success = true;
            }
            $formView = $form->createView();
        }

        $template = $this->renderView('ApplicationContestsBundle:Contests:responseMember.html.twig', array(
            'contest' => $contest,
            'isContestsMember' => $isContestsMember,
            'form' => $formView,
            'addedFiles' => $addedFiles,
        ));

        return new Response(json_encode(array(
            'success' => $success,
            'template' => $template,
        )));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addFileInResponseMemberAction(Request $request)
    {
        $success = false;
        $originalName = null;
        $fileName = null;
        $errorMessage = 'Не удалось загрузить файл';
        $errorValidMessage = null;
        $itemListFiles = null;

        /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
        $file = $request->files->get('add_file');
        $countFiles = $request->request->get('count');

        $fileConstraint = new File();
        /** @var $translator \Symfony\Bundle\FrameworkBundle\Translation\Translator */
        $translator = $this->get('translator');

        $fileConstraint->mimeTypes = array(
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
        );
        $fileConstraint->mimeTypesMessage = $translator->trans('scores.add_file_in_response_member.text_validation.mime_types');

        $fileConstraint->maxSize = '7000000';
        $fileConstraint->maxSizeMessage = $translator->trans('scores.add_file_in_response_member.text_validation.max_size');

        /** @var $validatorService \Symfony\Component\Validator\Validator */
        $validatorService = $this->get('validator');
        $errorList = $validatorService->validateValue($file, $fileConstraint);

        if ($file && count($errorList) == 0) {
            $countFiles = $countFiles + 1;

            $path = ContestsMembersPhotos::getFileFullPath();
            $fileName = uniqid() . '.' . $file->guessExtension();
            $originalName = $_FILES['add_file']['name'];
            $file->move($path, $fileName);

            $addedFile = array(
                'fileName' => $fileName,
                'fileNameOriginal' => $originalName,
                'linkFile' => ContestsMembersPhotos::getWebPath() . '/' . $fileName,
                'fileDescription' => '',
            );

            $itemListFiles = $this->renderView('ApplicationContestsBundle:Contests:responseMember_form_addFile.html.twig', array(
                'addedFile' => $addedFile,
                'countsFiles' => $countFiles,
            ));

            $success = true;
        }

        if ( count($errorList) > 0 ) {
            $errorValidMessage = $errorList[0]->getMessage();
        }

        return new Response(json_encode(array(
            'success' => $success,
            'errorMessage' => $errorMessage,
            'errorValidMessage' => $errorValidMessage,
            'countFiles' => $countFiles,
            'file_originalName' => $originalName,
            'file_name' => $fileName,
            'item_list_files' => $itemListFiles,
        )));
    }

    /**
     * @Template()
     *
     * @param int $idContest
     * @return array
     */
    public function itemAction($idContest)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\ContestsBundle\Repository\Contests */
        $contestsRepository = $em->getRepository('ApplicationContestsBundle:Contests');

        /** @var $contest \Application\ContestsBundle\Entity\Contests */
        $contest = $contestsRepository->find($idContest);

        return array(
            'contest' => $contest,
            'idContest' => $idContest
        );
    }
}
