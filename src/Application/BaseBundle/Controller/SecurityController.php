<?php
/**
 * Club Hg-Product
 *
 * Security controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Application\UsersBundle\Entity\Users;
use Application\UsersBundle\Repository\Users as UsersRepository;


/**
 * Application\BaseBundle\Controller\SecurityController
 */
class SecurityController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function loginAction()
    {
        return array();
    }

    /**
     * @Template()
     *
     * @param string $typeNetwork
     * @throws \Exception
     * @return array
     */
    public function buttonSocNetworkAction($typeNetwork)
    {
        switch ( $typeNetwork ) {
            case 'fb':
                $url = 'https://www.facebook.com/dialog/oauth';
                $redirectLink = $this->generateUrl('application_base_security_login_fb', array(), true);
                $clientId = $this->container->getParameter('facebook_app_id');
                $params = array(
                    'client_id'     => $clientId,
                    'redirect_uri'  => $redirectLink,
                    'display'       => 'popup',
                    'response_type' => 'code',
                    'scope'         => 'email,user_birthday'
                );
                break;

            case 'vk':
                $url = 'https://oauth.vk.com/authorize';
                $redirectLink = $this->generateUrl('application_base_security_login_vk', array(), true);
                $clientId = $this->container->getParameter('vkontakte_app_id');
                $params = array(
                    'client_id'     => $clientId,
                    'scope'         => 'notify',
                    'redirect_uri'  => $redirectLink,
                    'display'       => 'popup',
                    //'v'             => '5.2',
                    'response_type' => 'code',
                );
                break;

            case 'ok':
                $url = '';
                $params = array();
                break;

            default:
                throw new \Exception('Set the wrong type of social network');
                break;
        }

        $linkAuth = $url . '?' . urldecode(http_build_query($params));

        return array(
            'typeNetwork' => $typeNetwork,
            'linkAuth' => $linkAuth,
        );
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function loginFbAction(Request $request)
    {
        $fbUser = array();
        $result = false;
        $error = true;

        $code = $request->query->get('code');
        if ($code) {

            $container = $this->container;
            $clientId = $container->getParameter('facebook_app_id');
            $clientSecret = $container->getParameter('facebook_app_secret');
            $redirectUri = $this->generateUrl('application_base_security_login_fb', array(), true);
            $params = array(
                'client_id'     => $clientId,
                'redirect_uri'  => $redirectUri,
                'client_secret' => $clientSecret,
                'code'          => $code
            );

            $url = 'https://graph.facebook.com/oauth/access_token';

            $tokenInfo = null;
            parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);

            if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
                $params = array(
                    'access_token' => $tokenInfo['access_token'],
                    'locale' => 'ru_RU'
                );

                $userInfo = json_decode(file_get_contents('https://graph.facebook.com/me' . '?' . urldecode(http_build_query($params))), true);

                if (isset($userInfo['id'])) {
                    $fbUser = $userInfo;
                    $result = true;
                    $error = false;
                }
            }
        }

        if ($result == true) {

            /** @var $userManager \FOS\UserBundle\Model\UserManager */
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->findUserBy(array('fbId' => $fbUser['id']));

            $registration = false;
            if (!$user) {
                $registration = true;
                /** @var $user \Application\UsersBundle\Entity\Users */
                $user = $userManager->createUser();

                $user->setFbId( $fbUser['id'] );
                $user->setTypeUser( UsersRepository::TYPE_USER_FB );
                $user->setPhotoLink('https://graph.facebook.com/' . $fbUser['id'] . '/picture?type=large');
                $user->setUsername( 'fb_'. $fbUser['id'] );
                $user->setEmail( 'fb_'. $fbUser['id'] );
                $user->setEnabled(true);
                $user->setConfirmationToken(null);
                $user->setPassword('not-password');
            }
            //$user->addRole( 'ROLE_MEGA' );
            $user->setFirstName( $fbUser['first_name'] );
            $user->setLastName( $fbUser['last_name'] );
            $user->setLink( $fbUser['link'] );

            $userManager->updateUser($user);
            if ($registration) {
                $user->setUsername( $user->getSlug() );
                $userManager->updateUser($user);
            }
            $this->authenticateUser($user);
        }

        $content = array(
            'error' => $error,
            'linkRedirect' => $this->generateUrl('_home'),
        );

        return $content;
    }

    /**
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function loginVkAction(Request $request)
    {
        $vkUser = array();
        $result = false;
        $error = true;

        $code = $request->query->get('code');
        if ($code) {

            $container = $this->container;
            $clientId = $container->getParameter('vkontakte_app_id');
            $client_secret = $container->getParameter('vkontakte_app_secret');
            $redirectUri = $this->generateUrl('application_base_security_login_vk', array(), true);
            $params = array(
                'client_id'     => $clientId,
                'redirect_uri'  => $redirectUri,
                'client_secret' => $client_secret,
                'code'          => $code
            );

            $url = 'https://oauth.vk.com/access_token';
            $tokenInfo = json_decode(file_get_contents($url . '?' . urldecode(http_build_query($params))), true);

            if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])   ) {
                $params = array(
                    'uids'         => $tokenInfo['user_id'],
                    'fields'       => 'uid,first_name,last_name,nickname,screen_name,photo_big,online',
                    'access_token' => $tokenInfo['access_token']
                );

                $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);

                if (isset($userInfo['response'][0]['uid'])) {
                    $vkUser = $userInfo['response'][0];
                    $result = true;
                    $error = false;
                }
            }
        }

        if ($result == true) {

            /** @var $userManager \FOS\UserBundle\Model\UserManager */
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->findUserBy(array('vkId' => $vkUser['uid']));

            $registration = false;
            if (!$user) {
                $registration = true;
                /** @var $user \Application\UsersBundle\Entity\Users */
                $user = $userManager->createUser();

                $user->setVkId( $vkUser['uid'] );
                $user->setTypeUser( UsersRepository::TYPE_USER_VK );
                $user->setPhotoLink( $vkUser['photo_big'] );
                $user->setUsername( 'vk_'. $vkUser['uid'] );
                $user->setEmail( 'vk_'. $vkUser['uid'] );
                $user->setEnabled(true);
                $user->setConfirmationToken(null);
                $user->setPassword('not-password');
                $user->setLink( 'http://vk.com/' . $vkUser['screen_name'] );
            }
            $user->setFirstName( $vkUser['first_name'] );
            $user->setLastName( $vkUser['last_name'] );

            $userManager->updateUser($user);
            if ($registration) {
                $user->setUsername( $user->getSlug() );
                $userManager->updateUser($user);
            }
            $this->authenticateUser($user);
        }

        $content = array(
            'error' => $error,
            'linkRedirect' => $this->generateUrl('_home'),
        );

        return $content;
    }

    /**
     * Authenticate a user with Symfony Security
     *
     * @param \Application\UsersBundle\Entity\Users $user
     * @return bool
     */
    protected function authenticateUser(Users $user)
    {
        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $token = new UsernamePasswordToken( $user, null, $providerKey, $user->getRoles() );
        $this->container->get('security.context')->setToken($token);

        return true;
    }

}
