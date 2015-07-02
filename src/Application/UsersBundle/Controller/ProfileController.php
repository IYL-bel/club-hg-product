<?php
/**
 * Club Hg-Product
 *
 * Profile controller
 *
 * @package    ApplicationUsersBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\Request;

use Application\UsersBundle\Form\Type\AddCheckFile as AddCheckFileForm;
use Application\UsersBundle\Entity\Checks;
use Application\UsersBundle\Repository\Checks as ChecksRepository;
use TemplatesBundle\Repository\Statuses as StatusesRepository;
use Application\UsersBundle\Form\Type\EditUserProfile as EditUserProfileForm;
use Application\UsersBundle\Form\Type\AddRequestTestingProduct as AddRequestTestingProductForm;
use Application\TestProductionBundle\Entity\TestsProduction;
use Application\TestProductionBundle\Repository\TestsProduction as TestsProductionRepository;


/**
 * @Security("has_role('ROLE_USER')")
 *
 * Application\UsersBundle\Controller\ProfileController
 */
class ProfileController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function myAction()
    {
        return $this->redirectToRoute('application_users_profile_checks');
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function checksAction()
    {
        $addCheckFileType = new AddCheckFileForm();
        $formAddCheckFile = $this->createForm($addCheckFileType);

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $checksRepository = $em->getRepository('ApplicationUsersBundle:Checks');
        $checks = $checksRepository->findBy( array('user' => $this->getUser()), array('createdAt' => 'DESC') );

        $form = array(
            'add_check_file' => $formAddCheckFile->createView(),
        );

        return array(
            'form' => $form,
            'checks' => $checks,
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCheckFileAction(Request $request)
    {
        $success = false;
        $originalName = null;
        $fileName = null;
        $errorMessage = 'Не удалось загрузить файл';
        $errorValidMessage = null;

        /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
        $file = $request->files->get('file');

        $fileConstraint = new File();
        $fileConstraint->mimeTypes = array(
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
        );
        $fileConstraint->mimeTypesMessage = 'Данный тип файла недопустим. Допустимые типы файлов: jpg, png, gif.';

        $fileConstraint->maxSize = '7000000';
        $fileConstraint->maxSizeMessage = 'Размер файла больше допустимого. Допустимый размер файла 6Mb';

        /** @var $validatorService \Symfony\Component\Validator\Validator */
        $validatorService = $this->get('validator');
        $errorList = $validatorService->validateValue($file, $fileConstraint);

        if ($file && count($errorList) == 0) {

            $fileName = uniqid() . '.' . $file->guessExtension();
            //$originalName = $file->getClientOriginalName();
            $originalName = $_FILES['file']['name'];

            $check = new Checks();
            $check->setUser($this->getUser() );
            $check->setFile($file);
            $check
                ->setFilePath($fileName)
                ->setFileName($originalName);
            $check->setStatus(ChecksRepository::STATUS_NEW);

            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($check);
            $em->flush();

            $success = true;
        }

        if ( count($errorList) > 0 ) {
            $errorValidMessage = $errorList[0]->getMessage();
        }

        return new Response(json_encode(array(
            'success' => $success,
            'errorMessage' => $errorMessage,
            'errorValidMessage' => $errorValidMessage,
            'file_originalName' => $originalName,
            'file_name' => $fileName,
        )));
    }

    /**
     * @param int $id
     * @return array
     */
    public function removeCheckAction($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $checksRepository = $em->getRepository('ApplicationUsersBundle:Checks');
        $check = $checksRepository->findOneBy( array(
            'user' => $this->getUser(),
            'status' => ChecksRepository::STATUS_NEW,
            'id' => $id
        ));

        if ($check) {
            $em->remove($check);
            $em->flush();
        }

        return $this->redirectToRoute('application_users_profile_checks');
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function contestsAction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $contestsMembersRepository \Application\ContestsBundle\Entity\ContestsMembers */
        $contestsMembersRepository = $em->getRepository('ApplicationContestsBundle:ContestsMembers');
        $contestsMembers = $contestsMembersRepository->findBy( array( 'user' => $this->getUser() ), array('createdAt' => 'DESC') );

        return array(
            'contestsMembers' => $contestsMembers,
        );
    }

    /**
     * @param int $id
     * @return array
     */
    public function removeContestAction($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $contestsMembersRepository = $em->getRepository('ApplicationContestsBundle:ContestsMembers');
        $member = $contestsMembersRepository->findOneBy( array(
            'user' => $this->getUser(),
            'status' => ChecksRepository::STATUS_NEW,
            'id' => $id
        ));

        if ($member) {
            $em->remove($member);
            $em->flush();
        }

        return $this->redirectToRoute('application_users_profile_contests');
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function reviewsAction()
    {
        return array();
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function testingAction(Request $request)
    {
        $isAddress = false;
        /** @var  $currentUser \Application\UsersBundle\Entity\Users */
        $currentUser = $this->getUser();
        if ( $currentUser->getPostcode() && $currentUser->getShippingAddress() ) {
            $isAddress = true;
        }

        $testProduction = new TestsProduction();
        $testProduction->setUser( $this->getUser() );
        $testProduction->setStatus(TestsProductionRepository::STATUS__NEW);

        $form = $this->createForm(new  AddRequestTestingProductForm(), $testProduction);
        $form->handleRequest($request);

        if ($form->isValid() && $isAddress) {
            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getDoctrine()->getManager();
            $testProduction = $form->getData();

            $em->persist($testProduction);
            $em->flush();

            return $this->redirectToRoute('application_users_profile_testing');
        }

        return array(
            'isAddress' => $isAddress,
            'form' => $form->createView()
        );
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function inviteAction()
    {
        return array();
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function questionnaireAction()
    {
        return array();
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function statusAction()
    {
        /** @var $statusesManager \TemplatesBundle\Manager\StatusesManager */
        $statusesManager = $this->get('templates.statuses_manager');
        $statuses = $statusesManager->getActualStatuses();
        $userStatus = $statusesManager->getUserStatus( $this->getUser() );

        return array(
            'statuses' => $statuses,
            'userStatus' => $userStatus,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function settingsAction(Request $request)
    {
        $userProfile = $this->getUser();
        $form = $this->createForm(new EditUserProfileForm(), $userProfile);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userProfile = $form->getData();

            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getDoctrine()->getManager();
            $em->persist($userProfile);
            $em->flush();

            return $this->redirectToRoute('application_users_profile_settings');
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
