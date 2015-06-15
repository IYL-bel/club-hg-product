<?php
/**
 * Club Hg-Product
 *
 * Contests controller
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

use Application\AdminBundle\Entity\Prizes;
use Application\AdminBundle\Form\Type\EditPrize as EditPrizeForm;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\PrizesController
 */
class PrizesController extends Controller
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
        /** @var $prizesRepository \Application\AdminBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationAdminBundle:Prizes');
        $prizes = $prizesRepository->findAll();

        return array(
            'prizes' => $prizes,
        );
    }

    /**
     * @Template()
     *
     * @param \Application\AdminBundle\Controller\Request $request
     * @param null $id
     * @return array
     */
    public function editPrizeAction(Request $request, $id = null)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\AdminBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationAdminBundle:Prizes');

        if ($id) {
            /** @var $prize \Application\AdminBundle\Entity\Prizes */
            $prize = $prizesRepository->find($id);
            if (!$prize) {
                return $this->redirectToRoute('application_admin_prizes');
            }
        } else {
            /** @var $prize \Application\AdminBundle\Entity\Prizes */
            $prize = new Prizes();
        }

        $form = $this->createForm(new EditPrizeForm(), $prize);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $prize = $form->getData();
            $prize->setStatus($prizesRepository::STATUS_ACTIVE);

            $em->persist($prize);
            $em->flush();

            return $this->redirectToRoute('application_admin_prizes');
        }

        return array(
            'id' => $id,
            'form' => $form->createView()
        );
    }

}
