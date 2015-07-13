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
use Application\UsersBundle\Form\Type\AddCommentProduction as AddCommentProductionForm;
use Application\UsersBundle\Entity\CommentsProductionPhotos;
use Application\UsersBundle\Entity\CommentsProduction;
use Application\UsersBundle\Repository\CommentsProduction as CommentsProductionRepository;


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
        $fileConstraint->maxSizeMessage = 'Размер файла больше допустимого. Допустимый размер файла 6MB';

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
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var  $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $em->getRepository('ApplicationUsersBundle:CommentsProduction');
        $commentsProduct = $commentsProductRepository->findBy( array('user' => $this->getUser()), array('createdAt' => 'DESC') );

        /** @var $shopProductionsManager \HgProductBundle\Manager\ShopProductions */
        $shopProductionsManager = $this->get('hg_product.shop_productions_manager');
        $commentsProduct = $shopProductionsManager->addShopProductData($commentsProduct);

        return array(
            'commentsProduct' => $commentsProduct
        );
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function reviewRemoveAction($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var  $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $em->getRepository('ApplicationUsersBundle:CommentsProduction');
        $commentProduct = $commentsProductRepository->findOneBy(array(
            'id' => $id,
            'status' => $commentsProductRepository::STATUS_NEW,
            'user' => $this->getUser()
        ));

        if ($commentProduct) {
            $em->remove($commentProduct);
            $em->flush();
        }

        return $this->redirectToRoute('application_users_profile_reviews');
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reviewMoreAction($id)
    {
        $success = false;

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var  $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $em->getRepository('ApplicationUsersBundle:CommentsProduction');
        $commentProduct = $commentsProductRepository->find($id);

        $template = $this->renderView('ApplicationUsersBundle:Profile:reviewMore.html.twig', array(
            'commentProduct' => $commentProduct
        ));

        return new Response(json_encode(array(
            'success' => $success,
            'template' => $template,
        )));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function addReviewAction(Request $request)
    {
        $success = false;
        $addedFiles = array();
        $testProduction = array();
        $nameProductForm = null;
        $testingId = $request->query->get('testingId');
        $formSend = $request->request->get('form_send');

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $commentProduction = new CommentsProduction();
        $commentProduction->setUser( $this->getUser() );
        $commentProduction->setStatus(CommentsProductionRepository::STATUS_NEW);

        /** @var $scoresRepository \Application\ScoresBundle\Repository\Scores */
        $scoresRepository = $em->getRepository('ApplicationScoresBundle:Scores');
        /** @var  $scoreForCommentProduction \Application\ScoresBundle\Entity\Scores */
        $scoreForCommentProduction = $scoresRepository->findOneBy( array('type' => $scoresRepository::TYPE__REVIEWS_PRODUCT_BASE) );

        if ($scoreForCommentProduction) {
            $commentProduction->setScore($scoreForCommentProduction);
        }

        if ($testingId) {
            /** @var $testsProductionRepository \Application\TestProductionBundle\Repository\TestsProduction */
            $testsProductionRepository = $em->getRepository('ApplicationTestProductionBundle:TestsProduction');
            /** @var $testProduction \Application\TestProductionBundle\Entity\TestsProduction */
            $testProduction = $testsProductionRepository->find($testingId);
            if ($testProduction) {
                $commentProduction->setAfterTesting(true);
                //$commentProduction->setTestProduction($testProduction);
                $commentProduction->setNameProduct( $testProduction->getNameProduct() );
                $commentProduction->setShopProductsI18nId( $testProduction->getShopProductsI18nId() );
                $nameProductForm = $testProduction->getNameProduct();

                /** @var  $scoreForCommentProduction \Application\ScoresBundle\Entity\Scores */
                $scoreForCommentProduction = $scoresRepository->findOneBy( array('type' => $scoresRepository::TYPE__TEST_DRIVE_REPORT_BASE) );

                if ($scoreForCommentProduction) {
                    $commentProduction->setScore($scoreForCommentProduction);
                }
            }
        }

        $form = $this->createForm(new AddCommentProductionForm(), $commentProduction);

        if ($formSend && $testProduction) {
            $dataForm = $request->request->get( $form->getName() );
            $dataForm['nameProduct'] = json_encode(array(
                'label' => $testProduction->getNameProduct(),
                'value' => $testProduction->getShopProductsI18nId()
            ));
            $request->request->set($form->getName(), $dataForm);
        }

        $form->handleRequest($request);

        $addedFilesName = $request->request->get('fileName');
        $addedFilesNameOriginal = $request->request->get('fileNameOriginal');
        $addedFilesDescription = $request->request->get('fileDescription');

        if ( count($addedFilesName) ) {
            foreach($addedFilesName as $key => $val) {
                $addedFiles[$key] = array(
                    'fileName' => $addedFilesName[$key],
                    'fileNameOriginal' => $addedFilesNameOriginal[$key],
                    'linkFile' => CommentsProductionPhotos::getWebPath() . '/' . $addedFilesName[$key],
                    'fileDescription' => $addedFilesDescription[$key],
                );
            }
        }

        if ( $form->isValid() ) {
            /** @var  $commentProduction \Application\UsersBundle\Entity\CommentsProduction */
            $commentProduction = $form->getData();

            $data = $request->request->get( $form->getName() );
            if ( isset($data['nameProduct']) ) {
                $nameProduct = json_decode($data['nameProduct'], true);

                $commentProduction->setNameProduct( $nameProduct['label'] );
                $commentProduction->setShopProductsI18nId( $nameProduct['value'] );
            }

            $em->persist($commentProduction);
            $em->flush();

            $commentProductionPhotos = array();
            if ( count($addedFiles) ) {
                foreach ($addedFiles as $addedFile) {
                    $itemCommentProductionPhotos = new CommentsProductionPhotos();
                    $itemCommentProductionPhotos->setDescription( $addedFile['fileDescription'] );
                    $itemCommentProductionPhotos->setFileName( $addedFile['fileNameOriginal'] );
                    $itemCommentProductionPhotos->setFilePath( $addedFile['fileName'] );
                    $itemCommentProductionPhotos->setCommentsProduction($commentProduction);
                    $commentProductionPhotos[] = $itemCommentProductionPhotos;
                }
            }
            $commentProduction->setCommentsProductionPhotos($commentProductionPhotos);

            $em->persist($commentProduction);
            $em->flush();

            if ($testProduction) {
                $testProduction->setCommentProduction($commentProduction);
                $em->persist($testProduction);
                $em->flush();
            }

            $success = true;
        }

        $formView = $form->createView();

        $template = $this->renderView('ApplicationUsersBundle:Profile:addReview.html.twig', array(
            'testingId' => $testingId,
            'form' => $formView,
            'addedFiles' => $addedFiles,
            'commentProduction' => $commentProduction,
            'nameProductForm' => $nameProductForm,
            'formSend' => $formSend,
        ));

        return new Response(json_encode(array(
            'success' => $success,
            'template' => $template,
            'addedFiles' => $addedFiles,
        )));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addFileInReviewAction(Request $request)
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

            $path = CommentsProductionPhotos::getFileFullPath();
            $fileName = uniqid() . '.' . $file->guessExtension();
            $originalName = $_FILES['add_file']['name'];
            $file->move($path, $fileName);

            $addedFile = array(
                'fileName' => $fileName,
                'fileNameOriginal' => $originalName,
                'linkFile' => CommentsProductionPhotos::getWebPath() . '/' . $fileName,
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

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        /** @var $scoresRepository \Application\ScoresBundle\Repository\Scores */
        $scoresRepository = $em->getRepository('ApplicationScoresBundle:Scores');
        /** @var  $scoreForTestProduction \Application\ScoresBundle\Entity\Scores */
        $scoreForTestProduction = $scoresRepository->findOneBy( array('type' => $scoresRepository::TYPE__TEST_DRIVE_REQUEST_BASE) );

        $testProduction = new TestsProduction();
        $testProduction->setUser( $this->getUser() );
        $testProduction->setStatus(TestsProductionRepository::STATUS_NEW);
        if ($scoreForTestProduction) {
            $testProduction->setScore($scoreForTestProduction);
        }

        $form = $this->createForm(new AddRequestTestingProductForm(), $testProduction);
        $form->handleRequest($request);

        if ($form->isValid() && $isAddress) {
            /** @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getDoctrine()->getManager();
            $testProduction = $form->getData();

            $data = $request->get( $form->getName() );
            if ( isset($data['nameProduct']) ) {
                $nameProduct = json_decode($data['nameProduct'], true);

                $testProduction->setNameProduct( $nameProduct['label'] );
                $testProduction->setShopProductsI18nId( $nameProduct['value'] );
            }

            $em->persist($testProduction);
            $em->flush();

            return $this->redirectToRoute('application_users_profile_testing');
        }

        /** @var $testsProductionRepository \Application\TestProductionBundle\Repository\TestsProduction */
        $testsProductionRepository = $em->getRepository('ApplicationTestProductionBundle:TestsProduction');
        $testsProduction = $testsProductionRepository->findBy(array('user' => $this->getUser()), array('createdAt' => 'DESC'));

        /** @var $shopProductionsManager \HgProductBundle\Manager\ShopProductions */
        $shopProductionsManager = $this->get('hg_product.shop_productions_manager');
        $testsProduction = $shopProductionsManager->addShopProductData($testsProduction);

        return array(
            'testsProduction' => $testsProduction,
            'isAddress' => $isAddress,
            'form' => $form->createView(),
        );
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function testingRemoveAction($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $testsProductionRepository \Application\TestProductionBundle\Repository\TestsProduction */
        $testsProductionRepository = $em->getRepository('ApplicationTestProductionBundle:TestsProduction');
        /** @var $itemTestsProduction \Application\TestProductionBundle\Entity\TestsProduction */
        $testProduction = $testsProductionRepository->findOneBy(array(
            'id' => $id,
            'status' => $testsProductionRepository::STATUS_NEW,
            'user' => $this->getUser(),
        ));

        if ($testProduction) {
            $em->remove($testProduction);
            $em->flush();
        }

        return $this->redirectToRoute('application_users_profile_testing');
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
