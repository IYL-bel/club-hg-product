<?php
/**
 * Test task
 *
 * Load Gender Data fixture
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Test\UserBundle\Entity\Gender;

/**
 * Test\UserBundle\DataFixtures\ORM\LoadGenderData
 */
class LoadGenderData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $genderMale = new Gender();
        $genderMale->setGender(Gender::GENDER__MALE);
        $manager->persist($genderMale);

        $genderFemale = new Gender();
        $genderFemale->setGender(Gender::GENDER__FEMALE);
        $manager->persist($genderFemale);

        $manager->flush();

        $this->addReference('gender-male', $genderMale);
        $this->addReference('gender-female', $genderFemale);
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }

}
