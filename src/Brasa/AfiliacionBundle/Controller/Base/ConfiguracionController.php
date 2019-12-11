<?php

namespace Brasa\AfiliacionBundle\Controller\Base;


use Brasa\AfiliacionBundle\Form\Type\AfiConfiguracionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiClienteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ConfiguracionController extends Controller
{
    /**
     * @Route("/afi/base/configuracion", name="brs_afi_base_configuracion")
     */
    public function listaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $arConfiguracion = $em->getRepository("BrasaAfiliacionBundle:AfiConfiguracion")->find(1);
        $form = $this->createForm(new AfiConfiguracionType(), $arConfiguracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $arConfiguracion = $form->getData();
            $em->persist($arConfiguracion);
            $em->flush();
        }

        return $this->render('BrasaAfiliacionBundle:Base/Configuracion:nuevo.html.twig', array(
            'arConfiguracion' => $arConfiguracion,
            'form' => $form->createView()));
    }


}