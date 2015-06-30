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

use Application\PrizesBundle\Entity\Prizes;
use Application\AdminBundle\Form\Type\EditPrize as EditPrizeForm;
use Application\ScoresBundle\Entity\Scores;
use Application\ScoresBundle\Repository\Scores as ScoresRepository;


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
        /** @var $prizesRepository \Application\PrizesBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationPrizesBundle:Prizes');
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
        /** @var $prizesRepository \Application\PrizesBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationPrizesBundle:Prizes');

        if ($id) {
            /** @var $prize \Application\PrizesBundle\Entity\Prizes */
            $prize = $prizesRepository->find($id);
            if (!$prize) {
                return $this->redirectToRoute('application_admin_prizes');
            }
        } else {
            /** @var $prize \Application\PrizesBundle\Entity\Prizes */
            $prize = new Prizes();
        }

        $scoresBuy = $prize->getScoresBuy();
        if ($scoresBuy) {
            $prize->setPointsBuy( $scoresBuy->getPoints() );
        }

        $form = $this->createForm(new EditPrizeForm(), $prize);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $prize = $form->getData();

            if ( $prize->getFile() ) {
                /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
                $file = $prize->getFile();
                $prize->setFileName( $file->getClientOriginalName() );
            }

            $prize->setStatus($prizesRepository::STATUS_ACTIVE);

            $scoresBuy = $prize->getScoresBuy();
            if (!$scoresBuy) {
                $scoresBuy = new Scores();
                $scoresBuy->setType(ScoresRepository::TYPE__PRIZES);
            }
            $scoresBuy->setPoints( $prize->getPointsBuy() );
            $prize->setScoresBuy($scoresBuy);

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
