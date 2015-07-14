<?php
/**
 * Club Hg-Product
 *
 * Load Base Scores fixtures
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Application\ScoresBundle\Entity\Scores;
use Application\ScoresBundle\Repository\Scores as ScoresRepository;


/**
 * Application\ScoresBundle\DataFixtures\ORM\LoadBaseScores
 */
class LoadBaseScores implements FixtureInterface
{

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach($this->getBaseScores() as $baseScore) {
            $score = new Scores();
            $score->setType( $baseScore['type'] );
            $score->setPoints( $baseScore['points'] );

            $manager->persist($score);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getBaseScores()
    {
        $scores[] = array('type' => ScoresRepository::TYPE__REGISTRATION, 'points' => 50);
        $scores[] = array('type' => ScoresRepository::TYPE__SHARE_FB, 'points' => 5);
        $scores[] = array('type' => ScoresRepository::TYPE__SHARE_VK, 'points' => 5);
        $scores[] = array('type' => ScoresRepository::TYPE__SHARE_OK, 'points' => 5);
        $scores[] = array('type' => ScoresRepository::TYPE__FB_REFERRAL_ACTIVITY, 'points' => 15);
        $scores[] = array('type' => ScoresRepository::TYPE__VK_REFERRAL_ACTIVITY, 'points' => 15);
        $scores[] = array('type' => ScoresRepository::TYPE__OK_REFERRAL_ACTIVITY, 'points' => 15);
        $scores[] = array('type' => ScoresRepository::TYPE__CONTESTS_PARTICIPATION_BASE, 'points' => 20);
        $scores[] = array('type' => ScoresRepository::TYPE__CONTESTS_WINNER_BASE, 'points' => 200);
        $scores[] = array('type' => ScoresRepository::TYPE__FILLED_PROFILE, 'points' => 10);
        $scores[] = array('type' => ScoresRepository::TYPE__TEST_DRIVE_REQUEST_BASE, 'points' => 5);
        $scores[] = array('type' => ScoresRepository::TYPE__TEST_DRIVE_REPORT_BASE, 'points' => 100);
        $scores[] = array('type' => ScoresRepository::TYPE__REVIEWS_PRODUCT_BASE, 'points' => 10);
        $scores[] = array('type' => ScoresRepository::TYPE__FILLING_QUESTIONNAIRE, 'points' => 10);

        return $scores;
    }

}
