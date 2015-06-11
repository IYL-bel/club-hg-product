<?php
/**
 * Club Hg-Product
 *
 * Template controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Security("has_role('ROLE_ADMIN')")
 *
 * Application\AdminBundle\Controller\TemplateController
 */
class TemplatesController extends Controller
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
     *
     * @return array
     */
    public function changeMainSliderAction()
    {
        return array();
    }


}
