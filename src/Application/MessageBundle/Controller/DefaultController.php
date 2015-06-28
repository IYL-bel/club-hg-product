<?php

namespace Application\MessageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationMessageBundle:Default:index.html.twig', array('name' => $name));
    }
}
