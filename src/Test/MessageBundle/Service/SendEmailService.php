<?php
/**
 * Test task
 *
 * Send Email service
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\MessageBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\File;
use \Swift_Message;
use \Swift_Attachment;

use Test\UserBundle\Entity\Users;

/**
 * Test\MessageBundle\Service\SendEmailService
 */
class SendEmailService
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var bool|string
     */
    private $attachPath = false;


    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \Test\UserBundle\Entity\Users|string $to
     * @param string $subject
     * @param string $body
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function send($to, $subject, $body)
    {
        $messageSettings = $this->container->getParameter('test_message.settings');
        $fromEmail = $messageSettings['send_email']['from'];
        $from = array($fromEmail['address'] => $fromEmail['sender_name']);

        /** @var $message \Swift_Message */
        //$message = $this->newInstance();
        $message = Swift_Message::newInstance();
        $message
            ->setSubject($subject)
            ->setFrom($from)
            ->setBody($body, 'text/html');

        if ( is_object($to) ) {
            if ($to instanceof Users) {
                /** @var $user \Test\UserBundle\Entity\Users */
                $user = $to;
                call_user_func_array( array($message, 'setTo'), array($user->getEmail(), $user->getName() ) );
            } else {
                throw new AccessDeniedException('Passed object must belong to the class "User".');
            }
        } else {
            if ( $this->validateEmail($to) ) {
                $message->setTo($to);
            } else {
                throw new AccessDeniedException('Specified string not in the form required for an email address');
            }
        }

        if ( $this->attachPath && file_exists($this->attachPath) ) {
            $attachment = Swift_Attachment::fromPath($this->attachPath);
            $message->attach($attachment);
        }

        /** @var $mailer \Swift_Mailer */
        $mailer = $this->container->get('mailer');
        $mailer->send($message);

        return true;
    }

    /**
     * @param string $attachPath
     * @return SendEmailService
     */
    public function addAttach($attachPath)
    {
        $this->attachPath = $attachPath;
        return $this;
    }

    /**
     * @param string $email
     * @return bool
     */
    protected function  validateEmail($email)
    {
        if ( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            return true;
        }

        return false;
    }

}
