<?php
/**
 * Club Hg-Product
 *
 * Scores Action service
 *
 * @package    ApplicationScoresBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ScoresBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Application\UsersBundle\Entity\Users;
use Application\ScoresBundle\Entity\Scores;
use Application\ScoresBundle\Entity\ScoresUsers;
use Application\ScoresBundle\Repository\ScoresUsers as ScoresUsersRepository;


/**
 * Application\ScoresBundle\Service\ScoresActionService
 */
class ScoresActionService
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;


    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param \Application\ScoresBundle\Entity\Scores $score
     * @param \Application\UsersBundle\Entity\Users $user
     * @return \Application\ScoresBundle\Service\ScoresActionService
     */
    public function additionUserScore(Scores $score, Users $user)
    {
        // add Balls for User
        $scoresUsers = new ScoresUsers();
        $scoresUsers->setScore($score);
        $scoresUsers->setUser($user);
        $scoresUsers->setTypeCalculation(ScoresUsersRepository::TYPE_CALCULATION__ADDITION);

        $this->entityManager->persist($scoresUsers);
        $this->entityManager->flush();

        // recalculate user points
        $this->recalculateUserScores($user);

        return $this;
    }

    /**
     * @param \Application\ScoresBundle\Entity\Scores $score
     * @param \Application\UsersBundle\Entity\Users $user
     * @return ScoresActionService
     */
    public function subtractionUserScore(Scores $score, Users $user)
    {
        // remove Balls for User
        $scoresUsers = new ScoresUsers();
        $scoresUsers->setScore($score);
        $scoresUsers->setUser($user);
        $scoresUsers->setTypeCalculation(ScoresUsersRepository::TYPE_CALCULATION__SUBTRACTION);

        $this->entityManager->persist($scoresUsers);
        $this->entityManager->flush();

        // recalculate user points
        $this->recalculateUserScores($user);

        return $this;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return bool
     */
    public function addUserRegistrationScore(Users $user)
    {
        /** @var $scoresRepository \Application\ScoresBundle\Repository\Scores */
        $scoresRepository = $this->entityManager->getRepository('ApplicationScoresBundle:Scores');
        /** @var $scoreRegistration \Application\ScoresBundle\Entity\Scores */
        $scoreRegistration = $scoresRepository->findOneBy( array('type' => $scoresRepository::TYPE__REGISTRATION) );

        if ($scoreRegistration) {
            /** @var $scoresUsersRepository \Application\ScoresBundle\Repository\ScoresUsers */
            $scoresUsersRepository = $this->entityManager->getRepository('ApplicationScoresBundle:ScoresUsers');
            /** @var $scoreUserRegistration \Application\ScoresBundle\Entity\ScoresUsers */
            $scoreUserRegistration = $scoresUsersRepository->findOneBy( array('user' => $user, 'score' => $scoreRegistration) );
            if (!$scoreUserRegistration) {
                $this->additionUserScore($scoreRegistration, $user);

                return true;
            }
        }

        return false;
    }

    /**
     * @param \Application\UsersBundle\Entity\Users $user
     * @return ScoresActionService
     */
    public function recalculateUserScores(Users $user)
    {
        $scoresUsersRepository = $this->entityManager->getRepository('ApplicationScoresBundle:ScoresUsers');
        $allScoresUser = $scoresUsersRepository->findBy( array('user' => $user) );
        if ($allScoresUser) {
            $userPoints = 0;
            /** @var $scoreUser \Application\ScoresBundle\Entity\ScoresUsers */
            foreach($allScoresUser as $scoreUser) {
                $userPoints = $scoreUser->changeCalculate($userPoints);  //$userPoints + $scoreUser->getScore()->getPoints();
            }
            $userRepository = $this->entityManager->getRepository('ApplicationUsersBundle:Users');
            /** @var $user \Application\UsersBundle\Entity\Users */
            $user = $userRepository->find( $user->getId() );
            $user->setScorePoints($userPoints);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this;
    }

}
