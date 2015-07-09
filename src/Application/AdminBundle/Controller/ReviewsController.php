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

        /** @var $commentsProductionManager \Application\UsersBundle\Manager\CommentsProduction */
        $commentsProductionManager = $this->get('users.comments_production_manager');
        $commentsProduct = $commentsProductionManager->addShopProductData($commentsProduct);

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
                $sql = "INSERT INTO comments
                    (module, user_name, user_mail, user_site, item_id, text, date, agent, user_ip)
                    VALUES ('shop',
                        '" . $itemCommentProduct->getUser()->getFirstName() . ' ' . $itemCommentProduct->getUser()->getLastName() . "',
                        '" . $itemCommentProduct->getUser()->getEmail() . "',
                        'on',
                        '" . $itemCommentProduct->getShopProductsI18nId() . "',
                        '" . $itemCommentProduct->getDescription() . "',
                        '" . $itemCommentProduct->getCreatedAt()->getTimestamp() . "',
                        '" . $request->headers->get('user-agent') . "',
                        '127.0.0.1')";

                $query = $emHgProd->getConnection()->prepare($sql);
                $query->execute();
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
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        /** @var $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $em->getRepository('ApplicationUsersBundle:CommentsProduction');
        /** @var $itemCommentProduct \Application\UsersBundle\Entity\CommentsProduction */
        $itemCommentProduct = $commentsProductRepository->findOneBy(array(
            'id' => $id,
            'status' => $commentsProductRepository::STATUS_NEW
        ));

        if (!$itemCommentProduct) {
            return $this->redirectToRoute('application_admin_reviews');
        }

        $form = $this->createForm(new CommentProductionDisallowForm(), $itemCommentProduct );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $itemCommentProduct->setStatus($commentsProductRepository::STATUS_REJECTED);
            $itemCommentProduct->setProcessingAt( new \DateTime('now') );

            $em->persist($itemCommentProduct);
            $em->flush();

            return $this->redirectToRoute('application_admin_reviews');
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
