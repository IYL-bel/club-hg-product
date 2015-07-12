<?php
/**
 * Club Hg-Product
 *
 * Comment Production Disallow handler
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

use Application\AdminBundle\Form\Type\CommentProductionDisallow as CommentProductionDisallowForm;
use Application\UsersBundle\Repository\CommentsProduction as CommentsProductionRepository;


/**
 * Application\AdminBundle\Form\Handler\CommentProductionDisallow
 */
class CommentProductionDisallow
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var \Application\UsersBundle\Entity\CommentsProduction
     */
    private $itemCommentProduct;

    /**
     * @var \Symfony\Component\Form\Form
     */
    private $form;


    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param int $id
     * @return bool
     */
    public function findCommentProduct($id)
    {
        /** @var $commentsProductRepository \Application\UsersBundle\Repository\CommentsProduction */
        $commentsProductRepository = $this->em->getRepository('ApplicationUsersBundle:CommentsProduction');
        /** @var $itemCommentProduct \Application\UsersBundle\Entity\CommentsProduction */
        $itemCommentProduct = $commentsProductRepository->findOneBy(array(
            'id' => $id,
            'status' => $commentsProductRepository::STATUS_NEW
        ));

        if ($itemCommentProduct) {
            $this->itemCommentProduct = $itemCommentProduct;

            return true;
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    public function process(Request $request)
    {
        /** @var $formFactory \Symfony\Component\Form\FormFactory */
        $formFactory = $this->container->get('form.factory');
        $this->form = $formFactory->create  (new CommentProductionDisallowForm(), $this->itemCommentProduct );
        $this->form->handleRequest($request);

        if ( $this->form->isValid() ) {
            /** @var \Application\UsersBundle\Entity\CommentsProduction $itemCommentProduct */
            $itemCommentProduct = $this->form->getData();

            $itemCommentProduct->setStatus(CommentsProductionRepository::STATUS_REJECTED);
            $itemCommentProduct->setProcessingAt( new \DateTime('now') );

            $this->em->persist($itemCommentProduct);
            $this->em->flush();

            return true;
        }

        return false;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getFormView()
    {
        return $this->form->createView();
    }

}
