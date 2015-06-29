<?php
/**
 * Club Hg-Product
 *
 * Connection controller
 *
 * @package    SocialNetworksBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace SocialNetworksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;


/**
 * SocialNetworksBundle\Controller\ConnectionController
 */
class ConnectionController extends Controller
{

    /**
     * @Template()
     *
     * @param string $url
     * @return array
     */
    public function sharingAction($url)
    {
        // FACEBOOK SHARE LINK
        // https://www.facebook.com/dialog/share?
        //  app_id=145634995501895
        //  &display=popup
        //  &href=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2F
        //  &redirect_uri=https%3A%2F%2Fdevelopers.facebook.com%2Ftools%2Fexplorer

        $fb = 'https://www.facebook.com/dialog/share';
        $paramsFb = array(
            'app_id' => $this->container->getParameter('facebook_app_id'),
            'display' => 'popup',
            'href' => $url,
            'redirect_uri' => $this->generateUrl('social_networks_connection_after_share_fb', array(), true),
        );
        $fbLink = $fb . '?' . http_build_query($paramsFb);


        // VKONTAKTE SHARE LINK
        $vk = 'http://vk.com/share.php';
        $paramsVk = array(
            'url' => $url,
        );
        $vkLink = $vk . '?' . http_build_query($paramsVk);


        //ODNOKLASSNIKI SHARE LINK
        //  http://www.odnoklassniki.ru/dk?
        //      st.cmd=addShare&st.s=1
        //      &st.comments=hello
        //      &st._surl=http://club.hg-product.ru/login
        $ok = 'http://www.odnoklassniki.ru/dk';
        $paramsOk = array(
            'st.cmd' => 'addShare&st.s=1',
            'st.comments' => 'Hello',
            'st._surl' => $url,
        );
        $okLink = $ok . '?' . http_build_query($paramsOk);


        $url = array(
            'fb' => $fbLink,
            'vk' => $vkLink,
            'ok' => $okLink,
        );

        return array(
            'url' => $url
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function afterShareFbAction(Request $request)
    {
        $sharing = false;
        $errorCode = $request->query->get('error_code');
        $errorMessage = $request->query->get('error_message');

        if (!$errorMessage) {
            $sharing = true;
        }

        if ($errorCode == 100 && $request->getHost() == 'virtual.club-hg-product' ) {
            $sharing = true;
        }

        if ($sharing) {
            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->addShareScore($this->getUser(), 'fb');
        }

        return array(
            'error_code' => $errorCode,
            'error_message' => $errorMessage,
        );
    }

    /**
     * @param string $type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addShareScoreAction($type)
    {
        $success = true;
        if ($type == 'vk' || $type == 'ok') {

            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->addShareScore($this->getUser(), $type);
        }

        return new Response(json_encode(array(
            'success' => $success,
        )));
    }

}
