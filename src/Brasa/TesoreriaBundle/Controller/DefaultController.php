<?php

namespace Brasa\TesoreriaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BrasaTesoreriaBundle:Default:index.html.twig', array('name' => $name));
    }
}
