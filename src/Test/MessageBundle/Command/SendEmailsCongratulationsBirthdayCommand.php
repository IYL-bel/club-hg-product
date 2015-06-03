<?php
/**
 * Test task
 *
 * Congratulations Birthday command
 * CLI command:  app/console message:send_emails_congratulations_birthday
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\MessageBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Test\MessageBundle\Command\SendEmailsCongratulationsBirthdayCommand
 */
class SendEmailsCongratulationsBirthdayCommand extends ContainerAwareCommand
{

    /**
     * Configuration
     */
    protected function configure()
    {
        $this
            ->setName('message:send_emails_congratulations_birthday')
            ->setDescription('Send Emails to Users who have a birthday today.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        /** @var $emailsManager \Test\MessageBundle\Manager\EmailsManager */
        $emailsManager = $container->get('message.emails_manager');
        $emailsManager->sendCongratulationsBirthday();
    }

}
