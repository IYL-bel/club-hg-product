<?php
/**
 * Club Hg-Product
 *
 * Statuses controller
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

use TemplatesBundle\Repository\Statuses as StatusesRepository;
use TemplatesBundle\Form\Type\EditStatus as EditStatusForm;
use TemplatesBundle\Entity\Statuses;
use Application\AdminBundle\Form\Type\EditScorePoints as EditScorePointsForm;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\StatusesController
 */
class StatusesController extends Controller
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
        $statusesRepository = $em->getRepository('TemplatesBundle:Statuses');
        $statuses = $statusesRepository->findAll();

        $defaultStatuses = StatusesRepository::getAllStatuses();

        if ($statuses) {
            /** @var $status \TemplatesBundle\Entity\Statuses */
            foreach ($statuses as $status) {
                $defaultStatuses[$status->getNameStatus()] = $status;
            }
        }

        /** @var $scoresTableService \Application\ScoresBundle\Service\ScoresTableService */
        $scoresTableService = $this->get('scores_table.service');
        $tableScores = $scoresTableService->getTableScore();

        return array(
            'statuses' => $defaultStatuses,
            'default_scores' => StatusesRepository::getDefaultScoresForStatuses(),
            'tableScores' => $tableScores,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $name
     * @return array
     */
    public function editStatusAction(Request $request, $name)
    {
        $defaultStatuses = StatusesRepository::getAllStatuses();
        $allNameStatuses = array_keys($defaultStatuses);
        if ( !in_array($name, $allNameStatuses) ) {
            return $this->redirectToRoute('application_admin_statuses');
        }

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $statusesRepository \TemplatesBundle\Repository\Statuses */
        $statusesRepository = $em->getRepository('TemplatesBundle:Statuses');

        $status = $statusesRepository->findOneBy(array('nameStatus' => $name));

        if (!$status) {
            /** @var $translator \Symfony\Bundle\FrameworkBundle\Translation\Translator */
            $translator = $this->get('translator');

            $status = new Statuses();
            $status->setNameStatus($name);
            $status->setDescription( $translator->trans('templates.description.statuses.' . $name) );
            $status->setScores( $statusesRepository::getDefaultScoresForStatuses($name) );
        }

        $form = $this->createForm(new EditStatusForm(), $status);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $status = $form->getData();

            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('application_admin_statuses');
        }

        return array(
            'name' => $name,
            'form' => $form->createView(),
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $type
     * @return array
     */
    public function editTableScoresAction(Request $request, $type)
    {
        /** @var $scoresTableService \Application\ScoresBundle\Service\ScoresTableService */
        $scoresTableService = $this->get('scores_table.service');
        $allTypes = $scoresTableService->getBaseTypesScore();

        if ( !in_array($type, $allTypes) ) {
            return $this->redirectToRoute('application_admin_statuses');
        }

        $typeForDb = $type;
        if ($type == 'share') {
            $typeForDb = 'share_fb';
        }

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var \Application\ScoresBundle\Repository\Scores $scoresRepository */
        $scoresRepository = $em->getRepository('ApplicationScoresBundle:Scores');
        $score = $scoresRepository->findOneBy( array('type' => $scoresRepository::getNameTypes($typeForDb)) );

        $form = $this->createForm(new EditScorePointsForm(), $score);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var \Application\ScoresBundle\Entity\Scores $score */
            $score = $form->getData();

            $em->persist($score);
            $em->flush();

            // recalculate Users change points
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->changePointsScore( $scoresRepository::getNameTypes($typeForDb) );

            if ($type == 'share') {
                /** @var \Application\ScoresBundle\Entity\Scores $scoreShareVk */
                $scoreShareVk = $scoresRepository->findOneBy( array('type' => $scoresRepository::TYPE__SHARE_VK) );
                $scoreShareVk->setPoints( $score->getPoints() );
                $em->persist($scoreShareVk);

                /** @var \Application\ScoresBundle\Entity\Scores $scoreShareOk */
                $scoreShareOk = $scoresRepository->findOneBy( array('type' => $scoresRepository::TYPE__SHARE_OK) );
                $scoreShareOk->setPoints( $score->getPoints() );
                $em->persist($scoreShareOk);
                $em->flush();

                // recalculate Users change points
                $serviceScoresAction->changePointsScore($scoresRepository::TYPE__SHARE_VK);
                $serviceScoresAction->changePointsScore($scoresRepository::TYPE__SHARE_OK);
            }

            return $this->redirectToRoute('application_admin_statuses');
        }

        return array(
            'type' => $type,
            'form' => $form->createView(),
        );
    }

}
