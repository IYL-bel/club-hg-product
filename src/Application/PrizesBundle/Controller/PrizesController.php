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
        $isEnoughBalls = true;

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        /** @var $prizesRepository \Application\PrizesBundle\Repository\Prizes */
        $prizesRepository = $em->getRepository('ApplicationPrizesBundle:Prizes');
        /** @var $prize \Application\PrizesBundle\Entity\Prizes */
        $prize = $prizesRepository->find($idPrize);


        $template = $this->renderView('ApplicationPrizesBundle:Prizes:buyPrize.html.twig', array(
            'prize' => $prize,
            'isEnoughBalls' => $isEnoughBalls,
        ));


        return new Response(json_encode(array(
            'success' => $success,
            'template' => $template,
        )));
    }

}
