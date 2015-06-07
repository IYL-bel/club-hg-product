<?php
/**
 * Club Hg-Product
 *
 * Default controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Application\BaseBundle\Controller\DefaultController
 */
class DefaultController extends Controller
{

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {



        return array();
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function contestsAction()
    {
        return array();
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function prizesAction()
    {
        return array();
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     *
     * @return array
     */
    public function catalogProductsAction()
    {
        return array();
    }

}
