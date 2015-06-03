<?php
/**
 * Test task
 *
 * Default controller
 *
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Test\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Test\BaseBundle\Controller\DefaultController
 */
class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $name = 'WORLD';


        return $this->render('TestBaseBundle:Default:index.html.twig', array('name' => $name));
    }

}
