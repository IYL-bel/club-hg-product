<?php
/**
 * Club Hg-Product
 *
 * Scores Table service
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Application\ScoresBundle\Repository\Scores;


/**
 * Application\ScoresBundle\Service\ScoresTableService
 */
class ScoresTableService
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     * constructor
     *
     * @param ContainerInterface $container
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getBaseTypesScore()
    {
        $types = array(
            'registration',
            'share',
            'contests_participation_base',
            'contests_winner_base',
            'checks_base',
            'filled_profile',
            'test_drive_request_base',
            'test_drive_report_base',
            'reviews_product_base',
            'filling_questionnaire',
        );

        return $types;
    }

    /**
     * @return array
     */
    public function getTableScore()
    {
        $typesForTable = $this->getBaseTypesScore();
        $nameTypes = Scores::getNameTypes();
        $nameTypesFlip = array_flip($nameTypes);

        $typesForDb = array();
        foreach ($typesForTable as $type) {
            if ($type == 'share') {
                $typesForDb[] = Scores::TYPE__SHARE_FB;
            } else {
                $typesForDb[] = $nameTypes[$type];
            }
        }

        /** @var \Application\ScoresBundle\Repository\Scores $scoresRepository */
        $scoresRepository = $this->em->getRepository('ApplicationScoresBundle:Scores');
        $scores = $scoresRepository->getScoresForTypes($typesForDb);

        $scoresKey = array();
        /** @var \Application\ScoresBundle\Entity\Scores $score */
        foreach ($scores as $score) {
            $scoresKey[ $nameTypesFlip[$score['type']] ] = $score;
        }

        $scoresForTable = array();
        foreach ($typesForTable as $type) {
            $typeKey = $type;
            if ($type == 'share') {
                $typeKey = 'share_fb';
            }
            $scoresForTable[$type] = $scoresKey[$typeKey];
            $scoresForTable[$type]['description'] = 'scores.tab_scores.' . $type;
        }

        return $scoresForTable;
    }

}
