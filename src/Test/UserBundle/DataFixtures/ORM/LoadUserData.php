<?php
/**
 * Test task
 *
 * Load User Data fixture
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Test\UserBundle\Entity\Users;
use Test\UserBundle\Entity\Gender;

/**
 * Test\UserBundle\DataFixtures\ORM\LoadUserData
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @return array
     */
    public function getUsersSample()
    {
        $users[] = array('name' => 'Иван',      'gender' => 'male',    'email' => 'iyl+ivan@talenthunting.ru');
        $users[] = array('name' => 'Дмитрий',   'gender' => 'male',    'email' => 'iyl+dmitriy@talenthunting.ru');
        $users[] = array('name' => 'Елена',     'gender' => 'female',  'email' => 'iyl+elena@talenthunting.ru');
        $users[] = array('name' => 'Александр', 'gender' => 'male',    'email' => 'iyl+alex@talenthunting.ru');
        $users[] = array('name' => 'Катерина',  'gender' => 'female',  'email' => 'iyl+katerina@talenthunting.ru');
        $users[] = array('name' => 'Михаил',    'gender' => 'male',    'email' => 'iyl+michail@talenthunting.ru');
        $users[] = array('name' => 'Полина',    'gender' => 'female',  'email' => 'iyl+polina@talenthunting.ru');

        return $users;
    }

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $today = new \DateTime('now');
        $today->setTime(0, 0, 0);

        foreach ($this->getUsersSample() as $userSample) {
            $user = new Users();
            $user
                ->setName($userSample['name'])
                ->setEmail($userSample['email'])
                ->setBirthDate($today)
                ->setGender( $this->getReference('gender-' . $userSample['gender']) );
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }

}
