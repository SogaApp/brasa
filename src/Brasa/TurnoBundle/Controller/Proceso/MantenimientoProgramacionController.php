<?php
namespace Brasa\TurnoBundle\Controller\Proceso;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MantenimientoProgramacionController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/proceso/mantenimiento/programacion", name="brs_tur_proceso_mantenimiento_programacion")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();                                        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 10)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $mensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $anio = $form->get('anio')->getData();
            $mes = $form->get('mes')->getData();
            $fecha = date_create($anio . "/" . $mes . "/01");            
            $strUltimoDiaMes = date("d",(mktime(0,0,0,$mes+1,1,$anio)-1)); 
            $dateFechaHasta = date_create($anio . "/" . $mes . "/" . $strUltimoDiaMes); 
            $dateFechaDesde = $fecha;            
            
            if ($form->get('BtnActualizarHorasProgramadas')->isClicked()) {        
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->listaDql('', '', '', $dateFechaDesde->format('Y/m/d'), $dateFechaHasta->format('Y/m/d'), 0);                
                $query = $em->createQuery($dql);
                $arProgramaciones = $query->getResult();
                foreach ($arProgramaciones as $arProgramacion) {
                    $em->getRepository('BrasaTurnoBundle:TurProgramacion')->actualizarHorasProgramadas($arProgramacion->getCodigoProgramacionPk());
                }
                $em->flush();
            } 
            
        }
                
        return $this->render('BrasaTurnoBundle:Procesos/MantenimientoProgramacion:lista.html.twig', array(        
            'form' => $form->createView()));
    }           
    
    private function formularioLista() {  
        $fecha = new \DateTime('now');
        $anio = $fecha->format('Y');
        $mes = $fecha->format('m');
        $form = $this->createFormBuilder()
            ->add('mes', ChoiceType::class, array(
                'choices'  => array(
                    '01' => 'Enero','02' => 'Febrero','03' => 'Marzo','04' => 'Abril','05' => 'Mayo','06' => 'Junio','07' => 'Julio',
                    '08' => 'Agosto','09' => 'Septiembre','10' => 'Octubre','11' => 'Noviembre','12' => 'Diciembre',
                ),
                'data' => $mes,
            ))   
            ->add('anio', ChoiceType::class, array(
                'choices'  => array(
                    $anio -1 => $anio -1, $anio => $anio, $anio +1 =>$anio+1
                ),
                'data' => $anio,
            ))                            
            ->add('BtnActualizarHorasProgramadas', SubmitType::class, array('label'  => 'Actualizar horas programadas'))                        
            ->getForm();
        return $form;
    }        
    
}