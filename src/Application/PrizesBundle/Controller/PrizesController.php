<?php
/**
 * Club Hg-Product
 *
 * Prizes controller
 *
 * @package    ApplicationPrizesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\PrizesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Application\PrizesBundle\Entity\PrizesLottery;
use Application\PrizesBundle\Repository\PrizesLottery as PrizesLotteryRepository;
use Application\PrizesBundle\Entity\PrizesLotteryMembers;


/**
 * Application\PrizesBundle\Controller\PrizesController
 */
class PrizesController extends Controller
{

    /**
     * @param int $idPrize
     * @throws \Exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buyPrizeAction($idPrize)
    {
        $success = false;
        $errorMessage = false;
        $isEnoughBalls = true;
        $message = null;

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\PrizesBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationPrizesBundle:Prizes');
        /** @var $prize \Application\PrizesBundle\Entity\Prizes */
        $prize = $prizesRepository->find($idPrize);

        /** @var $statusesManager \TemplatesBundle\Manager\StatusesManager */
        $statusesManager = $this->get('templates.statuses_manager');
        $userStatuses = $statusesManager->getUserStatuses( $this->getUser() );

        /** @var $currentUser \Application\UsersBundle\Entity\Users */
        $currentUser = $this->getUser();
        if (!$currentUser->getPostcode() || !$currentUser->getShippingAddress() ) {
            $errorMessage = 'notShippingAddress';
        }

        if ( !in_array($prize->getTypeString(), $userStatuses) ) {
            $errorMessage = 'isEnoughBalls';
        }

        if ( $prize->getScoresBuy() ) {

            if ( $prize->getScoresBuy()->getPoints() > 0 && !$errorMessage ) {

                if ( $currentUser->getScorePoints() > $prize->getScoresBuy()->getPoints() ) {

                    // remove Balls for User
                    /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
                    $serviceScoresAction = $this->get('scores_action.service');
                    $serviceScoresAction->subtractionUserScore($prize->getScoresBuy(), $currentUser);

                    // SEND EMAIL FROM ADMIN
                    /** @var $sendEmailService \Application\MessageBundle\Service\SendEmailService */
                    $sendEmailService = $this->container->get('send_email');

                    $emailAdmin = $this->container->getParameter('admin_email');
                    /** @var $translator \Symfony\Bundle\FrameworkBundle\Translation\Translator */
                    $translator = $this->get('translator');
                    $subject = $translator->trans('messages.emails.checkout_prize_for_admin.subject');
                    $body = $translator->trans('messages.emails.checkout_prize_for_admin.body', array(
                        '%user_name%' => $currentUser->getFirstName() . ' ' . $currentUser->getLastName(),
                        '%user_postcode%' => $currentUser->getPostcode(),
                        '%user_address%' => $currentUser->getShippingAddress(),
                        '%prize%' => $prize->getTitle(),
                    ));
                    /////////$sendEmailService->send($emailAdmin, $subject, $body);

                    $message = 'prizeSendEmail';

                } else {
                    $errorMessage = 'isEnoughBalls';
                }
            }

            if ($prize->getScoresBuy()->getPoints() == 0 && !$errorMessage) {

                $prizeLottery = $prize->getPrizesLotteryActive();
                if ($prizeLottery) {
                    $prizeLotteryMembersRepository = $em->getRepository('ApplicationPrizesBundle:PrizesLotteryMembers');
                    $currentMember = $prizeLotteryMembersRepository->findOneBy(array(
                        'prizeLottery' => $prizeLottery,
                        'user' => $this->getUser(),
                    ));
                    if ($currentMember) {
                        $errorMessage = 'userIsMemberLottery';
                    }
                }
                if (!$prizeLottery && !$errorMessage) {
                    $prizeLottery = new PrizesLottery();
                    $prizeLottery->setPrize($prize);
                    $prizeLottery->setStartedAt( new \DateTime('now') );
                    $prizeLottery->setStatus( PrizesLotteryRepository::STATUS_ACTIVE );

                    $em->persist($prizeLottery);
                    $em->flush();
                }
                if (!$errorMessage) {
                    $prizeLotteryMember = new PrizesLotteryMembers();
                    $prizeLotteryMember->setPrizeLottery( $prizeLottery );
                    $prizeLotteryMember->setUser( $this->getUser() );

                    $em->persist($prizeLotteryMember);
                    $em->flush();

                    $message = 'bidParticipationLottery';
                }
            }

        } else {
            throw new \Exception('In Prize Unknown Scores Buy');
        }

        $template = $this->renderView('ApplicationPrizesBundle:Prizes:buyPrize.html.twig', array(
            'prize' => $prize,
            'errorMessage' => $errorMessage,
            'isEnoughBalls' => $isEnoughBalls,
            'message' => $message,
        ));

        return new Response(json_encode(array(
            'success' => $success,
            'template' => $template,
        )));
    }

}
