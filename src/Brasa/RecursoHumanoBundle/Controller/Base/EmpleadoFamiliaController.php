<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoFamiliaType;


class EmpleadoFamiliaController extends Controller
{
    /**
     * @Route("/rhu/base/empleadoFamilia/nuevo/{codigoEmpleado}/{codigoEmpleadoFamilia}", name="brs_rhu_base_empleado_familia_nuevo")
     */
    public function nuevoAction(Request $request, $codigoEmpleado, $codigoEmpleadoFamilia = 0) {
        $em = $this->getDoctrine()->getManager();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        if($codigoEmpleadoFamilia != 0) {
            $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->find($codigoEmpleadoFamilia);
        } else {
            $arEmpleadoFamilia->setEntidadCajaRel($arEmpleado->getEntidadCajaRel());
            $arEmpleadoFamilia->setEntidadSaludRel($arEmpleado->getEntidadSaludRel());
        }            
        
        $form = $this->createForm(new RhuEmpleadoFamiliaType, $arEmpleadoFamilia);
        $form->handleRequest($request);
        if ($form->isValid()) {           
            $arEmpleadoFamilia = $form->getData();            
            $arEmpleadoFamilia->setEmpleadoRel($arEmpleado);
            $em->persist($arEmpleadoFamilia);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_empleado_familia_nuevo', array('codigoEmpleado' => $codigoEmpleado, 'codigoEmpleadoFamilia' => 0 )));
            } else {
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoFamilia:nuevo.html.twig', array(
            'arEmpleadoFamilia' => $arEmpleadoFamilia,
            'form' => $form->createView()));
    }
  
}
