<?php

namespace Brasa\RecursoHumanoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class GenerarHorarioPeriodoController extends Controller
{
    /**
     * @Route("/rhu/proceso/control/acceso/periodo/horario/lista", name="brs_rhu_proceso_control_acceso_horario_periodo_listar")
     */
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 65)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arHorariosPeriodos = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        if($form->isValid()) {
            if($request->request->get('OpCerrar')) {
                $codigoHorarioPeriodo = $request->request->get('OpCerrar');
                $strResultado = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->cerrar($codigoHorarioPeriodo);
                if($strResultado == "") {
                    return $this->redirect($this->generateUrl('brs_rhu_proceso_control_acceso_horario_periodo_listar'));
                } else {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
            }
            
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoHorarioPeriodo) {
                        $arHorarioPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
                        $arHorarioPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->find($codigoHorarioPeriodo);
                        if ($arHorarioPeriodo->getEstadoGenerado() == 0){
                            if ($arHorarioPeriodo->getEstadoCerrado() == 0){
                                $em->remove($arHorarioPeriodo);
                            } else {
                            $objMensaje->Mensaje("error", "No se puede eliminar el registro " . $codigoHorarioPeriodo . ", ya fue cerrado", $this);
                        }
                        } else {
                            $objMensaje->Mensaje("error", "No se puede eliminar el registro " . $codigoHorarioPeriodo . ", ya fue generado", $this);
                        }
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_proceso_control_acceso_horario_periodo_listar'));
                }
            }    
        
        }
        $arHorariosPeriodos = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->findAll();
        $arHorariosPeriodos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),40);

        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarHorarioPeriodo:generar.html.twig', array(
                    'arHorariosPeriodos' => $arHorariosPeriodos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/proceso/control/acceso/periodo/horario/nuevo/{codigoHorarioPeriodo}", name="brs_rhu_proceso_control_acceso_horario_periodo_nuevo")
     */
    public function nuevoAction($codigoHorarioPeriodo = 0) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arHorarioPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        if ($codigoHorarioPeriodo != 0) {
            $arHorarioPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->find($codigoHorarioPeriodo);
        }    
        $form = $this->createFormBuilder()
            ->add('fechaPeriodo', 'date', array('data' => new \DateTime('now')))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arHorarioPeriodoValidar = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->findOneBy(array('fechaPeriodo' => $form->get('fechaPeriodo')->getData()));
            if (count($arHorarioPeriodoValidar) > 0){
                $objMensaje->Mensaje("error", "Ya existe el periodo", $this);
            }else {
                $fecha = $form->get('fechaPeriodo')->getData();                
                $strSql = "CALL spRhuHorarioAcceso('" . $fecha->format('Y/m/d') . "')";           
                $em->getConnection()->executeQuery($strSql); 
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
            }            
        }
        return $this->render('BrasaRecursoHumanoBundle:Procesos/GenerarHorarioPeriodo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    
    
}