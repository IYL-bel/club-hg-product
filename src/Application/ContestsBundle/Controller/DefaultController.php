<?php

namespace Application\ContestsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ApplicationContestsBundle:Default:index.html.twig', array('name' => $name));
    }
}
