<?php
/**
 * Club Hg-Product
 *
 * Template controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\ResultSetMapping;

use TemplatesBundle\Form\Type\AddMainSlider;
use TemplatesBundle\Entity\MainSlider;
use TemplatesBundle\Entity\MainTipsClub;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\TemplateController
 */
class TemplatesController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $cautionMainTips = true;
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $mainTipsClubRepository \TemplatesBundle\Repository\MainTipsClub */
        $mainTipsClubRepository = $em->getRepository('TemplatesBundle:MainTipsClub');
        $mainTipsClub[1] = $mainTipsClubRepository->findOneBy( array('numTip' => 1) );
        $mainTipsClub[2] = $mainTipsClubRepository->findOneBy( array('numTip' => 2) );

        if ($mainTipsClub[1] &&  $mainTipsClub[2]) {
            $cautionMainTips = false;
        }

        return array(
            'main_tips_club' => $mainTipsClub,
            'caution_main_tips' => $cautionMainTips,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return array
     */
    public function changeMainSliderAction(Request $request, $id)
    {
        // Check id number of slides
        if ( !in_array($id, range(1, 3)) ) {
            return $this->redirectToRoute('application_admin_templates');
        }

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $mainSliderRepository = $em->getRepository('TemplatesBundle:MainSlider');
        $mainSlider = $mainSliderRepository->findOneBy(array('numSlide' => $id));

        if (!$mainSlider) {
            $mainSlider = new MainSlider();
            $mainSlider->setNumSlide($id);
            $mainSlider->setText( $this->getMainSliderDefault($id) );
        }

        $form = $this->createForm(new AddMainSlider(), $mainSlider);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mainSlider = $form->getData();
            $em->persist($mainSlider);
            $em->flush();

            return $this->redirectToRoute('application_admin_templates');
        }

        $formView = $form->createView();

        return array(
            'id' => $id,
            'form' => $formView,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return array
     */
    public function changeMainTipAction(Request $request, $id)
    {
        // Check id number of slides
        if ( !in_array($id, range(1, 2)) ) {
            return $this->redirectToRoute('application_admin_templates');
        }

        $error = false;

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('HgProductBundle\Entity\Content', 'c');
        $rsm->addFieldResult('c', 'id', 'id');
        $rsm->addFieldResult('c', 'title', 'title');
        $rsm->addFieldResult('c', 'description', 'description');
        $rsm->addFieldResult('c', 'category', 'category');
        $rsm->addFieldResult('c', 'url', 'url');

        /** @var $emHgProd \Doctrine\ORM\EntityManager */
        $emHgProd = $this->getDoctrine()->getManager('hg_prod_ru');
        $query = $emHgProd
            ->createNativeQuery('SELECT c.* FROM content c WHERE category = :category ORDER BY c.id DESC', $rsm)
            ->setParameter('category', 69);
        $blockHelgi = $query->getResult();

        if ($request->getMethod() == 'POST') {
            $selectedId = $request->request->get('news-id');
            if ($selectedId) {
                $queryCurrentArticle = $emHgProd
                    ->createNativeQuery('SELECT c.* FROM content c WHERE id = :id', $rsm)
                    ->setParameter('id', $selectedId);
                /** @var $currentArticle \HgProductBundle\Entity\Content */
                $currentArticle = $queryCurrentArticle->getSingleResult();

                $contentFieldsDataRepository = $emHgProd->getRepository('HgProductBundle:ContentFieldsData');
                /** @var $contentFieldsData \HgProductBundle\Entity\ContentFieldsData */
                $contentFieldsData = $contentFieldsDataRepository->findOneBy(array('itemId' => $selectedId));

                /** @var $em \Doctrine\ORM\EntityManager */
                $em = $this->getDoctrine()->getManager();
                /** @var $mainTipsClubRepository \TemplatesBundle\Repository\MainTipsClub */
                $mainTipsClubRepository = $em->getRepository('TemplatesBundle:MainTipsClub');

                /** @var $mainTips \TemplatesBundle\Entity\MainTipsClub */
                $mainTips = $mainTipsClubRepository->findOneBy(array('numTip' => $id));
                if (!$mainTips) {
                    $mainTips = new MainTipsClub();
                    $mainTips->setNumTip($id);
                }
                $mainTips
                    ->setTitle( $currentArticle->getTitle() )
                    ->setLink('http://hg-product.ru/blog-helgi/' . $currentArticle->getUrl() )
                    ->setDescription( $currentArticle->getDescription() )
                    ->setPictureLink('http://hg-product.ru' . $contentFieldsData->getData() );

                $em->persist($mainTips);
                $em->flush();

                return $this->redirectToRoute('application_admin_templates');
            } else {
                $error = true;
            }
        }

        return array(
            'id' => $id,
            'block_helgi' => $blockHelgi,
            'error' => $error,
        );
    }

    /**
     * @param int $id
     * @return null|string
     */
    public function getMainSliderDefault($id)
    {
        if ( !in_array($id, range(1, 3)) ) {
            return null;
        }

        $slideText = array(
            1 => '<strong>Скидки 50% <br/>для всех членов</strong>Клуба Экспертов Чистоты',
            2 => '<strong>Скидки 70% <br/>для всех членов</strong>Клуба Тугой Шуфлятки',
            3 => '<strong>Скидки 30% <br/>для всех членов</strong>Клуба Почасовой Муж',
        );

        return $slideText[$id];
    }

}
