<?php

namespace Application\TestProductionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationTestProductionBundle:Default:index.html.twig', array('name' => $name));
    }
}
