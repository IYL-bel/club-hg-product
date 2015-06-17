<?php
/**
 * Club Hg-Product
 *
 * Contests controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

use Application\AdminBundle\Entity\Contests;
use Application\AdminBundle\Form\Type\EditContests as EditContestsForm;
use Application\ScoresBundle\Entity\Scores;
use Application\ScoresBundle\Repository\Scores as ScoresRepository;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\ContestsController
 */
class ContestsController extends Controller
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
        /** @var $contestsRepository \Application\AdminBundle\Repository\Contests */
        $contestsRepository = $em->getRepository('ApplicationAdminBundle:Contests');
        $contests = $contestsRepository->findAll();

        return array(
            'contests' => $contests,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param null $id
     * @return array
     */
    public function editContestAction(Request $request, $id = null)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $contestsRepository \Application\AdminBundle\Repository\Contests */
        $contestsRepository = $em->getRepository('ApplicationAdminBundle:Contests');

        if ($id) {
            /** @var $contests \Application\AdminBundle\Repository\Contests */
            $contests = $contestsRepository->find($id);
            if (!$contests) {
                return $this->redirectToRoute('application_admin_contests');
            }
        } else {
            /** @var $contests \Application\AdminBundle\Repository\Contests */
            $contests = new Contests();
        }

        $scoresParticipation = $contests->getScoresParticipation();
        if ($scoresParticipation) {
            $contests->setPointsParticipation( $scoresParticipation->getPoints() );
        }

        $scoresWinner = $contests->getScoresWinner();
        if ($scoresWinner) {
            $contests->setPointsWinner($scoresWinner->getPoints() );
        }

        $form = $this->createForm(new EditContestsForm(), $contests);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $contests = $form->getData();
            $contests->setStatus($contestsRepository::STATUS_ACTIVE);

            $scoresParticipation = $contests->getScoresParticipation();
            if (!$scoresParticipation) {
                $scoresParticipation = new Scores();
                $scoresParticipation->setType(ScoresRepository::TYPE__CONTESTS_PARTICIPATION);
            }
            $scoresParticipation->setPoints( $contests->getPointsParticipation() );
            $contests->setScoresParticipation($scoresParticipation);

            $scoresWinner = $contests->getScoresWinner();
            if (!$scoresWinner) {
                $scoresWinner = new Scores();
                $scoresWinner->setType(ScoresRepository::TYPE__CONTESTS_WINNER );
            }
            $scoresWinner->setPoints( $contests->getPointsWinner() );
            $contests->setScoresWinner($scoresWinner);

            $em->persist($contests);
            $em->flush();

            return $this->redirectToRoute('application_admin_contests');
        }

        return array(
            'id' => $id,
            'form' => $form->createView(),
        );
    }

}
