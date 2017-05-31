<?php

namespace WEIT\EspectacularesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WEITEspectacularesBundle:Default:index.html.twig', array('name' => $name));
    }
}
