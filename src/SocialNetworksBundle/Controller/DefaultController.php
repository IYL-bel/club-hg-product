<?php

namespace SocialNetworksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SocialNetworksBundle:Default:index.html.twig', array('name' => $name));
    }
}
