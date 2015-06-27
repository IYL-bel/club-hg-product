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
use Application\ScoresBundle\Entity\ScoresUsers;


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
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('_home'));
        }

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
                $url = 'http://www.odnoklassniki.ru/oauth/authorize';
                $redirectLink = $this->generateUrl('application_base_security_login_ok', array(), true);
                $clientId = $this->container->getParameter('odnoklassniki_app_id'); // Client ID
                $params = array(
                    'client_id'     => $clientId,
                    'redirect_uri'  => $redirectLink,
                    'response_type' => 'code',
                );
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
            $user = $userManager->findUserBy(array(
                'fbId' => $fbUser['id'],
                'typeUser' => UsersRepository::TYPE_USER_FB
            ));

            $registration = false;
            if (!$user) {
                $registration = true;
                /** @var $user \Application\UsersBundle\Entity\Users */
                $user = $userManager->createUser();

                $user->setFbId( $fbUser['id'] );
                $user->setTypeUser( UsersRepository::TYPE_USER_FB );
                $user->setFirstName( $fbUser['first_name'] );
                $user->setLastName( $fbUser['last_name'] );
                $user->setPhotoLink('https://graph.facebook.com/' . $fbUser['id'] . '/picture?type=large');
                $user->setUsername( 'fb_'. $fbUser['id'] );
                $user->setEmail( 'fb_'. $fbUser['id'] );
                $user->setEnabled(true);
                $user->setConfirmationToken(null);
                $user->setPassword('not-password');

            }

            //$user->addRole( 'ROLE_ADMIN' );
            //$user->setFirstName( $fbUser['first_name'] );
            //$user->setLastName( $fbUser['last_name'] );
            $user->setLink( $fbUser['link'] );

            $userManager->updateUser($user);
            if ($registration) {
                $user->setUsername( $user->getSlug() );
                $userManager->updateUser($user);
            }

            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->addUserRegistrationScore($user);

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
            $user = $userManager->findUserBy(array(
                'vkId' => $vkUser['uid'],
                'typeUser' => UsersRepository::TYPE_USER_VK
            ));

            $registration = false;
            if (!$user) {
                $registration = true;
                /** @var $user \Application\UsersBundle\Entity\Users */
                $user = $userManager->createUser();

                $user->setVkId( $vkUser['uid'] );
                $user->setTypeUser( UsersRepository::TYPE_USER_VK );
                $user->setFirstName( $vkUser['first_name'] );
                $user->setLastName( $vkUser['last_name'] );
                $user->setUsername( 'vk_'. $vkUser['uid'] );
                $user->setEmail( 'vk_'. $vkUser['uid'] );
                $user->setEnabled(true);
                $user->setConfirmationToken(null);
                $user->setPassword('not-password');
                $user->setLink( 'http://vk.com/' . $vkUser['screen_name'] );
            }
            //$user->setFirstName( $vkUser['first_name'] );
            //$user->setLastName( $vkUser['last_name'] );
            $user->setPhotoLink( $vkUser['photo_big'] );

            $userManager->updateUser($user);
            if ($registration) {
                $user->setUsername( $user->getSlug() );
                $userManager->updateUser($user);
            }

            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->addUserRegistrationScore($user);

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
    public function loginOkAction(Request $request)
    {
        $okUser = array();
        $result = false;
        $error = true;

        $code = $request->query->get('code');
        if ($code) {

            $container = $this->container;
            $clientId = $container->getParameter('odnoklassniki_app_id');
            $publicKey = $container->getParameter('odnoklassniki_public_key');
            $clientSecret = $container->getParameter('odnoklassniki_app_secret');
            $redirectUri = $this->generateUrl('application_base_security_login_ok', array(), true);
            $params = array(
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'code'          => $code,
                'redirect_uri'  => $redirectUri,
                'grant_type' => 'authorization_code',
            );

            $url = 'http://api.odnoklassniki.ru/oauth/token.do';

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, urldecode(http_build_query($params)));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($curl);
            curl_close($curl);

            $tokenInfo = json_decode($result, true);

            if (isset($tokenInfo['access_token']) && isset($publicKey)) {

                $sign = md5("application_key={$publicKey}format=jsonmethod=users.getCurrentUser" . md5("{$tokenInfo['access_token']}{$clientSecret}"));

                $params = array(
                    'method'          => 'users.getCurrentUser',
                    'access_token'    => $tokenInfo['access_token'],
                    'application_key' => $publicKey,
                    'format'          => 'json',
                    'sig'             => $sign
                );

                $userInfo = json_decode(file_get_contents('http://api.odnoklassniki.ru/fb.do' . '?' . urldecode(http_build_query($params))), true);

                if (isset($userInfo['uid'])) {
                    $okUser = $userInfo;
                    $result = true;
                    $error = false;
                }
            }
        }

        if ($result == true) {

            /** @var $userManager \FOS\UserBundle\Model\UserManager */
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $userManager->findUserBy(array(
                'okId' => $okUser['uid'],
                'typeUser' => UsersRepository::TYPE_USER_OK
            ));

            $registration = false;
            if (!$user) {
                $registration = true;
                /** @var $user \Application\UsersBundle\Entity\Users */
                $user = $userManager->createUser();

                $user->setOkId( $okUser['uid'] );
                $user->setTypeUser( UsersRepository::TYPE_USER_OK );
                $user->setFirstName( $okUser['first_name'] );
                $user->setLastName( $okUser['last_name'] );
                $user->setUsername( 'ok_'. $okUser['uid'] );
                $user->setEmail( 'ok_'. $okUser['uid'] );
                $user->setEnabled(true);
                $user->setConfirmationToken(null);
                $user->setPassword('not-password');
                $user->setLink( 'http://ok.ru/profile/' . $okUser['uid'] );
            }
            //$user->setFirstName( $okUser['first_name'] );
            //$user->setLastName( $okUser['last_name'] );

            if ( isset($okUser['pic_2']) && $okUser['pic_2'] ) {
                $user->setPhotoLink( $okUser['pic_2'] );
            }

            $userManager->updateUser($user);
            if ($registration) {
                $user->setUsername( $user->getSlug() );
                $userManager->updateUser($user);
            }

            // add Balls for User
            /** @var $serviceScoresAction \Application\ScoresBundle\Service\ScoresActionService */
            $serviceScoresAction = $this->get('scores_action.service');
            $serviceScoresAction->addUserRegistrationScore($user);

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
