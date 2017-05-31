<?php

namespace WEIT\PersonalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WEITPersonalBundle:Default:index.html.twig', array('name' => $name));
    }
}
