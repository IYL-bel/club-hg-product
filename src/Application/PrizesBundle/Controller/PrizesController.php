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


/**
 * Application\PrizesBundle\Controller\PrizesController
 */
class PrizesController extends Controller
{

    /**
     * @param int $idPrize
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function buyPrizeAction($idPrize)
    {
        $success = false;
        $errorMessage = false;
        $isEnoughBalls = true;

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\PrizesBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationPrizesBundle:Prizes');
        /** @var $prize \Application\PrizesBundle\Entity\Prizes */
        $prize = $prizesRepository->find($idPrize);

        if ($prize->getScoresBuy() && $prize->getScoresBuy()->getPoints() > 0) {
            /** @var $currentUser \Application\UsersBundle\Entity\Users */
            $currentUser = $this->getUser();

            if ($currentUser->getScorePoints() > $prize->getScoresBuy()->getPoints() ) {
                if ($currentUser->getPostcode() && $currentUser->getShippingAddress() ) {

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
                    $sendEmailService->send($emailAdmin, $subject, $body);

                } else {
                    $errorMessage = 'notShippingAddress';
                }
            } else {
                $errorMessage = 'isEnoughBalls';
            }
        }
        $template = $this->renderView('ApplicationPrizesBundle:Prizes:buyPrize.html.twig', array(
            'prize' => $prize,
            'errorMessage' => $errorMessage,
            'isEnoughBalls' => $isEnoughBalls,
        ));

        return new Response(json_encode(array(
            'success' => $success,
            'template' => $template,
        )));
    }

}
