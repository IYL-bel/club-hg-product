<?php
/**
 * Club Hg-Product
 *
 * Load Score Checks Base fixtures
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
 * Application\ScoresBundle\DataFixtures\ORM\LoadScoreChecksBase
 */
class LoadScoreChecksBase implements FixtureInterface
{

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $score = new Scores();
        $score->setType( ScoresRepository::TYPE__CHECKS_BASE );
        $score->setPoints(1);

        $manager->persist($score);
        $manager->flush();
    }

}
