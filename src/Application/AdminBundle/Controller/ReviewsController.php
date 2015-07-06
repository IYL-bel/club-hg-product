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

use Application\AdminBundle\Form\Type\CommentProductionDisallow as CommentProductionDisallowForm;


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
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approvedCommentAction($id)
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
