<?php
/**
 * Club Hg-Product
 *
 * Social NetworksService service
 *
 * @package    SocialNetworksBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace SocialNetworksBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * SocialNetworksBundle\Service\SocialNetworksService
 */
class SocialNetworksService
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;


    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @return \SocialNetworksBundle\Service\SocialNetworksService
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $url
     * @param string $redirectUri
     * @return string
     */
    public function getSharingFb($url, $redirectUri)
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
            'redirect_uri' => $redirectUri,
        );
        $fbLink = $fb . '?' . http_build_query($paramsFb);

        return $fbLink;
    }

    /**
     * @param string $url
     * @return string
     */
    public function getSharingVk($url)
    {
        $vk = 'http://vk.com/share.php';
        $paramsVk = array(
            'url' => $url,
        );
        $vkLink = $vk . '?' . http_build_query($paramsVk);

        return $vkLink;
    }

    /**
     * @param string $url
     * @return string
     */
    public function getSharingOk($url)
    {
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

        return $okLink;
    }

}
