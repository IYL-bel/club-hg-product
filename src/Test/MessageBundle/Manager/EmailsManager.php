<?php
/**
 * Test task
 *
 * Emails manager
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\MessageBundle\Manager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Test\UserBundle\Entity\Users;
use Test\UserBundle\Entity\Gender;

/**
 * Test\MessageBundle\Manager\EmailsManager
 */
class EmailsManager
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;


    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * @return \Test\MessageBundle\Manager\EmailsManager
     */
    public function sendCongratulationsBirthday()
    {
        /** @var $usersRepository \Test\UserBundle\Repository\UsersRepository */
        $usersRepository = $this->em->getRepository('TestUserBundle:Users');
        $today = new \DateTime('now');
        $today->setTime(0, 0, 0);
        $users = $usersRepository->getUsersTodayBirthday($today);

        $attachePath = 'bundles/testmessage/images/';
        $basePath = __DIR__.'/../../../../web/';

        /** @var $translator \Symfony\Bundle\FrameworkBundle\Translation\Translator */
        $translator = $this->container->get('translator');
        /** @var $template \Symfony\Bundle\TwigBundle\TwigEngine */
        $template = $this->container->get('templating');

        /** @var $sendEmailService \Test\MessageBundle\Service\SendEmailService */
        $sendEmailService = $this->container->get('send_email');
        $subject = $translator->trans('message.emails.congratulations_birthday.subject');
        /** @var $user \Test\UserBundle\Entity\Users */
        foreach($users as $user) {
            $genderName = ( $user->getGender()->getGender() ) ? $user->getGender()->getGenderString(null) : 'male';
            $yj = $translator->trans('gender_text.ending.yj.' . $genderName );
            $body = $template->render('TestMessageBundle:Emails:congratulationsBirthday.html.twig', array(
                'yj' => $yj,
                'user' => $user,
            ));

            if ( $user->getGender()->getGender() == Gender::GENDER__MALE ) {
                $attacheFile = $basePath . $attachePath . 'pic1.jpg';
            } elseif ( $user->getGender()->getGender() == Gender::GENDER__FEMALE ) {
                $attacheFile = $basePath . $attachePath . 'pic2.jpg';
            } else {
                $attacheFile = null;
            }

            $sendEmailService->addAttach($attacheFile);
            $sendEmailService->send($user, $subject, $body);
        }

        return $this;
    }

}
