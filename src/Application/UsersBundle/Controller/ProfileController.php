<?php
/**
 * Club Hg-Product
 *
 * Profile controller
 *
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

/**
 * Application\UsersBundle\Controller\ProfileController
 */
class ProfileController extends Controller
{

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function myAction()
    {
        $addCheckFileType = new AddCheckFileForm();
        $formAddCheckFile = $this->createForm($addCheckFileType);

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $checksRepository = $em->getRepository('ApplicationUsersBundle:Checks');
        $checks = $checksRepository->findBy( array('user' => $this->getUser()), array('createdAt' => 'DESC') );

        $profStatuses[] = array(
            'title' => 'БРОНЗА',
            'name_medal' => 'bronze',
            'comments' => array(
                'Бесплатно тестировать средства HG раз в месяц',
                'Участвовать в "бронзовых" розыгрышах',
                'Ообменивать баллы на "бронзовые" призы'
            )
        );
        $profStatuses[] = array(
            'title' => 'СЕРЕБРО',
            'name_medal' => 'silver',
            'comments' => array(
               'Бесплатно тестировать средства HG два в месяц',
                'Участвовать в "серебрянных" розыгрышах',
                'Обменивать баллы на "серебрянные" призы'
            )
        );
        $profStatuses[] = array(
            'title' => 'ЗОЛОТО',
            'name_medal' => 'gold',
            'comments' => array(
                'Бесплатно тестировать средства HG три в месяц',
                'Участвовать в "золотых" розыгрышах',
                'Обменивать баллы на "золотые" призы'
            )
        );
        $profStatuses[] = array(
            'title' => 'ПЛАТИНА',
            'name_medal' => 'platinum',
            'comments' => array(
                'Бесплатно тестировать любые средства HG, но не более одного средства один раз.',
                'Участвовать в "платиновых" розыгрышах',
                'Обменивать баллы на "платиновые" призы'
            )
        );

        $form = array(
            'add_check_file' => $formAddCheckFile->createView(),
        );

        return array(
            'form' => $form,
            'checks' => $checks,
            'prof_statuses' => $profStatuses
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

        return $this->redirectToRoute('application_users_profile_my');
    }

}
