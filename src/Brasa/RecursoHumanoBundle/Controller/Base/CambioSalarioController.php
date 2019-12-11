<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class CambioSalarioController extends Controller
{
    /**
     * @Route("/rhu/cambiosalario/nuevo/{codigoContrato}/{codigoCambioSalario}", name="brs_rhu_cambio_salario_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContrato, $codigoCambioSalario = 0) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCambioSalario = new \Brasa\RecursoHumanoBundle\Entity\RhuCambioSalario();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoCambioSalario != 0)
        {
            $arCambioSalario = $em->getRepository('BrasaRecursoHumanoBundle:RhuCambioSalario')->find($codigoCambioSalario);
            $dateAplicacion = $arCambioSalario->getFecha();
        }else {
            $dateAplicacion = new \DateTime('now');
        }    
        $form = $this->createFormBuilder()
            ->add('salarioNuevo', NumberType::class, array('required' => true, 'data' => $arCambioSalario->getVrSalarioNuevo()))
            ->add('fechaAplicacion', DateType::class, array('data' => new \DateTime('now')))
            ->add('detalle', TextType::class, array('required' => true, 'data' => $arCambioSalario->getDetalle()))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if ($arContrato->getFechaUltimoPago() > $form->get('fechaAplicacion')->getData()){
                $objMensaje->Mensaje("error", "El cambio de salario se debe realizar despues del pago del ".$arContrato->getFechaUltimoPago()->format('Y/m/d')."", $this);
            } else {
                $arCambioSalario->setContratoRel($arContrato);
                $arCambioSalario->setEmpleadoRel($arContrato->getEmpleadoRel());
                $arCambioSalario->setFecha($form->get('fechaAplicacion')->getData());
                $arCambioSalario->setVrSalarioNuevo($form->get('salarioNuevo')->getData());    
                if ($codigoCambioSalario == 0){
                    $arCambioSalario->setVrSalarioAnterior($arContrato->getVrSalario());
                    $arCambioSalario->setCodigoUsuario($arUsuario->getUserName());
                }
                $arCambioSalario->setDetalle($form->get('detalle')->getData());
                $arEmpleadoActualizar = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoActualizar = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arContrato->getEmpleadoRel());
                $arEmpleadoActualizar->setVrSalario($form->get('salarioNuevo')->getData());
                $arContrato->setVrSalario($form->get('salarioNuevo')->getData());
                $arContrato->setVrSalarioPago($form->get('salarioNuevo')->getData());
                $em->persist($arCambioSalario);
                $em->persist($arContrato);
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
                $douSalarioMinimo = $arConfiguracion->getVrSalario();
                if($arContrato->getVrSalario() <= $douSalarioMinimo * 2) {
                    $arEmpleadoActualizar->setAuxilioTransporte(1);
                } else {
                    $arEmpleadoActualizar->setAuxilioTransporte(0);
                }
                $em->persist($arEmpleadoActualizar);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            }
            
        }
        return $this->render('BrasaRecursoHumanoBundle:CambioSalario:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
