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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;


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
     * @return array
     */
    public function buttonSocNetworkAction($typeNetwork)
    {
        $redirectLink = $this->generateUrl('application_base_security_login_fb', array(), true);
        $clientId = $this->container->getParameter('facebook_app_id');
        $params = array(
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectLink,
            'display'       => 'popup',
            'response_type' => 'code',
            'scope'         => 'email,user_birthday'
        );

        $url = 'https://www.facebook.com/dialog/oauth';
        $linkAuth = $url . '?' . urldecode(http_build_query($params));

        return array(
            'typeNetwork' => $typeNetwork,
            'linkAuth' => $linkAuth,
        );
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function loginFbAction()
    {
        $fbUser = array();
        $result = false;
        $error = true;

        $request = $this->getRequest();
        $code = $request->query->get('code');
        if ($code) {

            $container = $this->container;
            $clientId = $container->getParameter('facebook_app_id'); // Client ID
            $client_secret = $container->getParameter('facebook_app_secret'); // Client secret
            $redirectUri = $this->generateUrl('application_base_security_login_fb', array(), true);
            $params = array(
                'client_id'     => $clientId,
                'redirect_uri'  => $redirectUri,
                'client_secret' => $client_secret,
                'code'          => $code
            );

            $url = 'https://graph.facebook.com/oauth/access_token';

            $tokenInfo = null;
            parse_str(file_get_contents($url . '?' . http_build_query($params)), $tokenInfo);

            if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
                $params = array('access_token' => $tokenInfo['access_token']);

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
