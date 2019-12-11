<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class HorarioAccesoController extends Controller
{
    
    /**
     * @Route("/rhu/utilidad/horario/acceso/empleado", name="brs_rhu_utilidad_horario_acceso_empleado")
     */ 
    public function listaAction() {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 85)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $strDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->listaDql(1,0);
        $arHorarioPeriodo = $paginator->paginate($em->createQuery($strDql), $request->query->get('page', 1), 20);                
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:lista.html.twig', array(
            'arHorarioPeriodos' => $arHorarioPeriodo
                ));
    }            
    
    /**
     * @Route("/rhu/utilidad/horario/acceso/empleado/detalle/{codigoHorarioPeriodo}", name="brs_rhu_utilidad_horario_acceso_empleado_detalle")
     */     
    public function detalleAction($codigoHorarioPeriodo) {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arHorarioPeriodo = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        $arHorarioPeriodo = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->find($codigoHorarioPeriodo);        
        $dateFechaDiaAnterior = date_create($arHorarioPeriodo->getFechaPeriodo()->format('Y/m/d'));
        $dateFechaDiaAnterior = date_add($dateFechaDiaAnterior, date_interval_create_from_date_string('-1 days'));        
        $arHorarioPeriodoDiaAnterior = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioPeriodo();
        $arHorarioPeriodoDiaAnterior = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioPeriodo')->findOneBy(array('fechaPeriodo' => $dateFechaDiaAnterior));                        
        $codigoHorarioPeriodoDiaAnterior = 0;
        if($arHorarioPeriodoDiaAnterior) {
            $codigoHorarioPeriodoDiaAnterior = $arHorarioPeriodoDiaAnterior->getCodigoHorarioPeriodoPk();
        }
        $form = $this->createFormBuilder()                  
                    ->add('tipo', 'choice', array('choices' => array('0' => 'Entrada', '1' => 'Salida'),'data' => 1,'expanded' => true,))
                    ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
                    ->getForm();               
        $form->handleRequest($request);
        if ($form->isValid()) {
            $fecha = new \DateTime('now');
            $arrControles = $request->request->All();            
            if($arrControles['txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                if($arEmpleado) {                    
                    $strSql = "CALL spRhuHorarioRegistro(" . $arEmpleado->getCodigoEmpleadoPk() . ", '" . $fecha->format('Y/m/d') . "', '" . $fecha->format('H:i:s') . "', " . $form->get('tipo')->getData() . ")";           
                    $em->getConnection()->executeQuery($strSql); 
                }
            }else {
                    $objMensaje->Mensaje("error", "Digite por favor el numero de identificación", $this);
            }                 
        }
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->listaDql2($codigoHorarioPeriodo);        
        $arHorarioAccesos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 40);   
        $arHorarioAccesosDiaAnterior = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
        if($codigoHorarioPeriodoDiaAnterior != 0) {
            $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->listaDql3($codigoHorarioPeriodoDiaAnterior);        
            $arHorarioAccesosDiaAnterior = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 40);                       
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:detalle.html.twig', array(        
        'arHorarioAccesos' => $arHorarioAccesos,
        'arHorarioAccesosAnt' => $arHorarioAccesosDiaAnterior,
        'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/utilidad/cargar/control/acceso/empleados", name="brs_rhu_utilidad_cargar_control_acceso_empleados")
     */
    public function cargarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $rutaTemporal = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
        $rutaTemporal = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
        $form = $this->createFormBuilder()
            ->add('attachment', 'file')
            ->add('BtnCargar', 'submit', array('label'  => 'Cargar'))
            ->getForm();
        $form->handleRequest($request);
        $arrErrores = array();
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $form['attachment']->getData()->move($rutaTemporal->getRutaTemporal(), "carga.txt");
                $fp = fopen($rutaTemporal->getRutaTemporal()."carga.txt", "r");
                $arrRegistros = array();
                while(!feof($fp)) {
                    $linea = fgets($fp);
                    if($linea){
                        $arrayDetalle = explode(";", $linea);
                        if($arrayDetalle[0] != "") {
                            $arrRegistros[] = array('identificacion' => $arrayDetalle[0], 
                                'fechaIngreso' => $arrayDetalle[1],
                                'fechaSalida' => $arrayDetalle[2]);
                        }
                    }
                }                
                fclose($fp);                
                /*foreach ($arrRegistros as $arrRegistro) { 
                    $i = 0;
                    foreach ($arrRegistros as $arrRegistroValidar) {                         
                        if($arrRegistro['identificacion'] == $arrRegistroValidar['identificacion']) {
                            $i++;
                            if($i > 1) {
                                $arrErrores[] = array('error' => "La identificacion " . $arrRegistro['identificacion'] . " esta ducplicada en el archivo");                                
                            }                            
                        }
                    }
                }*/
                foreach ($arrRegistros as $arrRegistro) {                    
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrRegistro['identificacion']));
                    if(count($arEmpleado) > 0) {
                        if($arEmpleado->getCodigoContratoActivoFk() != ''){
                            $dateFechaIngreso = $arrRegistro['fechaIngreso'];
                            $dateFechaIngreso = new \DateTime($dateFechaIngreso);
                            $dateFechaSalida = $arrRegistro['fechaSalida'];
                            $dateFechaSalida = new \DateTime($dateFechaSalida);                            
                            if($em->getRepository('BrasaRecursoHumanoBundle:RhuHorarioAcceso')->validarIngreso($dateFechaIngreso->format('Y/m/d'), $arEmpleado->getCodigoEmpleadoPk()) == FALSE) {
                                $arHorarioAcceso = new \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso();
                                $arHorarioAcceso->setEmpleadoRel($arEmpleado);
                                $arHorarioAcceso->setFechaEntrada($dateFechaIngreso);
                                $arHorarioAcceso->setFechaSalida($dateFechaSalida);
                                $arHorarioAcceso->setEstadoSalida(1);
                                $arHorarioAcceso->setDuracionRegistro('0');
                                $em->persist($arHorarioAcceso);                                                                    
                            } else {
                                $arrErrores[] = array('error' => "El empleado " . $arrRegistro['identificacion'] . " ya registra ingreso");                                    
                            }
                        }else {
                            $arrErrores[] = array('error' => "El empleado " . $arrRegistro['identificacion'] . " " . $arEmpleado->getNombreCorto() . " no tiene contrato");                                
                        }                                                                                               
                    }else{
                        $arrErrores[] = array('error' => "El numero de identificación " . $arrRegistro['identificacion'] . " no existe");                                
                    }                                     
                }
                                
                if(count($arrErrores) <= 0) {
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                            
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/HorarioAcceso:cargarRegistro.html.twig', array(
            'arrErrores' => $arrErrores,
            'form' => $form->createView()
            ));
    }     
          
}
