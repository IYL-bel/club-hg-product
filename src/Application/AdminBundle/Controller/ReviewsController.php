<?php
/**
 * Club Hg-Product
 *
 * Reviews controller
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\ResultSetMapping;

use Application\AdminBundle\Form\Type\CommentProductionDisallow as CommentProductionDisallowForm;
use HgProductBundle\Entity\Comments;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\ReviewsController
 */
class ReviewsController extends Controller
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
        /** @var  $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $em->getRepository('ApplicationUsersBundle:CommentsProduction');
        $commentsProduct = $commentsProductRepository->findBy( array(), array('createdAt' => 'DESC') );

        /** @var $shopProductionsManager \HgProductBundle\Manager\ShopProductions */
        $shopProductionsManager = $this->get('hg_product.shop_productions_manager');
        $commentsProduct = $shopProductionsManager->addShopProductData($commentsProduct);

        return array(
            'commentsProduct' => $commentsProduct
        );
    }

    /**
     * @Template()
     *
     * @param int $id
     * @return array
     */
    public function commentMoreAction($id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var  $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $em->getRepository('ApplicationUsersBundle:CommentsProduction');
        $commentProduct = $commentsProductRepository->find($id);

        if (!$commentProduct) {
            return $this->redirectToRoute('application_admin_reviews');
        }

        return array(
            'itemCommentProduct' => $commentProduct,
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approvedCommentAction(Request $request, $id)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        /** @var $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $em->getRepository('ApplicationUsersBundle:CommentsProduction');
        /** @var $itemCommentProduct \Application\UsersBundle\Entity\CommentsProduction */
        $itemCommentProduct = $commentsProductRepository->findOneBy(array(
            'id' => $id,
            'status' => $commentsProductRepository::STATUS_NEW
        ));

        if ($itemCommentProduct) {
            $itemCommentProduct->setStatus($commentsProductRepository::STATUS_CONFIRMED);
            $itemCommentProduct->setProcessingAt( new \DateTime('now') );

            $em->persist($itemCommentProduct);
            $em->flush();

            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->additionUserScore( $itemCommentProduct->getScore(), $itemCommentProduct->getUser() );

            // not work for virtual host
            if ( $itemCommentProduct->getShopProductsI18nId() && $request->getHost() != 'virtual.club-hg-product' ) {
                /** @var $emHgProd \Doctrine\ORM\EntityManager */
                $emHgProd = $this->getDoctrine()->getManager('hg_prod_ru');
                /** @var \HgProductBundle\Repository\Comments $commentRepository */
                $commentRepository= $emHgProd->getRepository('HgProductBundle:Comments');
                $commentRepository->addComment($request, $itemCommentProduct);
            }
        }

        return $this->redirectToRoute('application_admin_reviews');
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function disallowCommentAction(Request $request, $id)
    {
        /** @var \Application\AdminBundle\Form\Handler\CommentProductionDisallow $commentProductionDisallowHandler */
        $commentProductionDisallowHandler = $this->get('form.handler.comment_production_disallow');
        if ( !$commentProductionDisallowHandler->findCommentProduct($id) ) {
            return $this->redirectToRoute('application_admin_reviews');
        }

        if ( $commentProductionDisallowHandler->process($request) ) {
            return $this->redirectToRoute('application_admin_reviews');
        }

        return array(
            'form' => $commentProductionDisallowHandler->getFormView(),
        );
    }

}
