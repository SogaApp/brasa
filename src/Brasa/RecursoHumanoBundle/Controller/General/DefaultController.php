<?php

namespace Brasa\RecursoHumanoBundle\Controller\General;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class DefaultController extends Controller
{
    /**
     * @Route("/rhu/inicio ", name="brs_rhu_inicio")
     */
    public function inicioAction() {
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnInactivar', 'submit', array('label'  => 'Activar / Inactivar',))

            ->getForm();
        $form->handleRequest($request);
        return $this->render('BrasaRecursoHumanoBundle:Default:inicio.html.twig', array (
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/rhu/como/iniciar", name="brs_rhu_como_iniciar")
     */
    public function comoIniciarAction() {
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnInactivar', 'submit', array('label'  => 'Activar / Inactivar',))

            ->getForm();
        $form->handleRequest($request);
        return $this->render('BrasaRecursoHumanoBundle:Default:comoIniciar.html.twig', array (
            'form' => $form->createView()
        ));
    }

}
