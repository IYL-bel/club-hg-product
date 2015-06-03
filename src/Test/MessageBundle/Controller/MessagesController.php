<?php
/**
 * Test task
 *
 * Messages controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Test\UserBundle\Entity\Gender;

/**
 * Test\MessageBundle\Controller\MessagesController
 */
class MessagesController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function sendEmailAction()
    {
        return array();
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function congratulationsBirthdayAction()
    {
        /** @var $emailsManager \Test\MessageBundle\Manager\EmailsManager */
        $emailsManager = $this->get('message.emails_manager');
        $emailsManager->sendCongratulationsBirthday();

        return array();
    }

}
