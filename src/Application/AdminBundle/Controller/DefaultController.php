<?php
/**
 * Club Hg-Product
 *
 * Default controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Application\AdminBundle\Controller\DefaultController
 */
class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('application_admin_templates');
        }

        return $this->redirectToRoute('_home');
    }

}
