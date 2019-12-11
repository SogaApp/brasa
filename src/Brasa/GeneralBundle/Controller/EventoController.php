<?php

namespace Brasa\GeneralBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\GeneralBundle\Form\Type\GenEventoType;

class EventoController extends Controller
{
    var $strSqlLista = "";    
    
    /**
     * @Route("/gen/evento/nuevo/", name="brs_gen_evento_nuevo")
     */
    public function nuevoAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arEvento = new \Brasa\GeneralBundle\Entity\GenEvento();       
        $arEvento->setFecha(new \DateTime('now'));
        $form = $this->createForm(new GenEventoType(), $arEvento);                     
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arEvento = $form->getData();
            $em->persist($arEvento);
            $em->flush();
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
        }                

        return $this->render('BrasaGeneralBundle:Evento:nuevo.html.twig', array(
            'form' => $form->createView()));
    }
  
}
