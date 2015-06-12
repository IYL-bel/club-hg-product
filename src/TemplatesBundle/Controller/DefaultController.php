<?php

namespace TemplatesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TemplatesBundle:Default:index.html.twig', array('name' => $name));
    }
}
