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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * SocialNetworksBundle\Controller\ConnectionController
 */
class ConnectionController extends Controller
{

    /**
     * @Template()
     *
     * @param string $url
     * @param string $title
     * @param string $description
     * @return array
     */
    public function sharingAction($url, $title, $description)
    {
        $fb = 'http://www.facebook.com/sharer.php';
        $params = array(
            's' => 100,
            'p[title]' => $title,
            'p[url]' => $url,
            //'p[summary]' => $description,
        );

        //$fbLink = $fb . '?' . http_build_query($params);


        $url = array(
            'fb' => '', //$fbLink,
            'vk' => '',
            'ok' => '',
        );

        return array(
            'url' => $url
        );
    }

}
