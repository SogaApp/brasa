<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAspiranteType;


class AspiranteController extends Controller
{
    /**
     * @Route("/rhu/movimientos/aspirante/lista", name="brs_rhu_movimiento_aspirante_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 35, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arAspirantes = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arAspirantes) > 0) {
                    foreach ($arAspirantes AS $id) {
                        $arAspirantes = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
                        $arAspirantes = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($id);
                        if ($arAspirantes->getEstadoCerrado() == 0 and $arAspirantes->getEstadoAutorizado()== 0){
                             $em->remove($arAspirantes);
                             $em->flush();
                        } else {
                            $objMensaje->Mensaje("error", "No se puede eliminar esta aprobado o autorizado", $this);
                        }     
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_aspirante_lista'));
                }
            }

            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->listar();
                $this->generarExcel();
            }
        }

        $arAspirantes = $paginator->paginate($em->createQuery($session->get('dqlAspiranteLista')), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:lista.html.twig', array('arAspirantes' => $arAspirantes, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimientos/aspirante/nuevo/{codigoAspirante}", name="brs_rhu_movimiento_aspirante_nuevo")
     */
    public function nuevoAction($codigoAspirante) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        if($codigoAspirante != 0) {
            $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
        } else {
            $arAspirante->setFecha(new \DateTime('now'));
            $arAspirante->setFechaNacimiento(new \DateTime('now'));
        } 
        $form = $this->createForm(new RhuAspiranteType, $arAspirante);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arAspirante = $form->getData();
            $arAspirante->setNombreCorto($arAspirante->getNombre1() . " " . $arAspirante->getNombre2() . " " .$arAspirante->getApellido1() . " " . $arAspirante->getApellido2());
            
            if($codigoAspirante == 0) {
                $arAspirante->setCodigoUsuario($arUsuario->getUserName());
            }
            if ($arAspirante->getCodigoTipoLibreta() != 0){
                $arAspirante->setLibretaMilitar($arAspirante->getNumeroIdentificacion());
            }
            else {
                $arAspirante->setLibretaMilitar("");
            }
            $arAspirante->setCodigoTipoLibreta($arAspirante->getCodigoTipoLibreta());
            $em->persist($arAspirante);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_movimiento_aspirante_nuevo', array('codigoAspirante' => 0)));
            } else {
                if ($codigoAspirante == 0){
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_aspirante_detalle', array('codigoAspirante' => $arAspirante->getCodigoAspirantePk())));
                }else {
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_aspirante_lista'));
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:nuevo.html.twig', array(
            'arAspirante' => $arAspirante,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/movimientos/aspirante/aplicar/{codigoAspirante}", name="brs_rhu_movimiento_aspirante_aplicar")
     */
    public function aplicarAction($codigoAspirante) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
        $form = $this->createFormBuilder()
            ->add('seleccionRequisicionRel', 'entity',
                array('class' => 'BrasaRecursoHumanoBundle:RhuSeleccionRequisito',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sr')
                    ->where('sr.estadoCerrado = 0')        
                    ->orderBy('sr.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => true
                ))                              
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRequisicionAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
            $arRequisicionAspirante->setSeleccionRequisitoRel($form->get('seleccionRequisicionRel')->getData());
            $arRequisicionAspirante->setAspiranteRel($arAspirante);
            $em->persist($arRequisicionAspirante);
            $arRequisicionDato = $form->get('seleccionRequisicionRel')->getData();
            $arRequisicionAspiranteValidar = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->findBy(array('codigoSeleccionRequisitoFk' => $arRequisicionDato, 'codigoAspiranteFk' => $codigoAspirante));
            if ($arAspirante->getBloqueado() == 0){
                if ($arRequisicionDato->getEstadoCerrado() == 0){
                    if ($arRequisicionAspiranteValidar == null){
                        //Calculo edad
                            $varFechaNacimientoAnio = $arAspirante->getFechaNacimiento()->format('Y');
                            $varFechaNacimientoMes = $arAspirante->getFechaNacimiento()->format('m');
                            $varMesActual = date('m');
                            if ($varMesActual >= $varFechaNacimientoMes){
                                $varEdad = date('Y') - $varFechaNacimientoAnio;
                            } else {
                                $varEdad = date('Y') - $varFechaNacimientoAnio -1;
                            }
                        //Fin calculo edad
                        $edadMinima = $arRequisicionDato->getEdadMinima();
                        $edadMaxima = $arRequisicionDato->getEdadMaxima();
                        if ($edadMinima != "" && $edadMaxima != ""){
                            if ($varEdad <= $edadMaxima && $varEdad >= $edadMinima){
                                $em->flush();
                                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                     
                            } else {
                                $objMensaje->Mensaje("error", "El aspirante debe tener una edad entre " .$edadMinima. " y " .$edadMaxima . " años para aplicar a la requisicion", $this);
                            }
                        } else {
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                     
                        }    
                    } else {
                        $objMensaje->Mensaje("error", "El aspirante ya se encuenta en la requisicion", $this);
                    }
                } else {
                    $objMensaje->Mensaje('error','La requisicion esta cerrada, no puede aplicar',$this);
                }
            } else {
                $objMensaje->Mensaje('error','El aspirante no puede aplicar a la requisición, tiene inconsistencias',$this);
            }    
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:aplicar.html.twig', array(
            'arAspirante' => $arAspirante,
            'form' => $form->createView()));
    }
    
     /**
     * @Route("/rhu/movimientos/aspirante/desbloquear/{codigoAspirante}", name="brs_rhu_movimiento_aspirante_desbloquear")
     */
    public function desbloquearAction($codigoAspirante) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),110)){
            $objMensaje->Mensaje("error", "No tiene permisos para desbloquear aspirantes, comuniquese con el administrador", $this);
        }
            $form = $this->createFormBuilder()
                ->add('comentarios', 'textarea', array('data' =>$arAspirante->getComentarios() ,'required' => false))                          
                ->add('BtnDesbloquear', 'submit', array('label'  => 'Desbloquear'))                              
                ->add('BtnCancelar', 'submit', array('label'  => 'Cancelar'))
                ->getForm();
        
        
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),110)){
            $objMensaje->Mensaje("error", "No tiene permisos para desbloquear aspirantes, comuniquese con el administrador", $this);
            } else {
                if($form->get('BtnDesbloquear')->isClicked()) {
                    $arAspirante->setBloqueado(0);
                    $arAspirante->setComentarios($form->get('comentarios')->getData());
                    $em->persist($arAspirante);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                     
                } 
                
            } 
            if($form->get('BtnCancelar')->isClicked()) {
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                     
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:desbloquear.html.twig', array(
            'arAspirante' => $arAspirante,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/movimientos/aspirante/historial/{codigoAspirante}", name="brs_rhu_movimiento_aspirante_historial")
     */
    public function historialAction($codigoAspirante) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('numeroIdentificacion' => $arAspirante->getNumeroIdentificacion()));
        $arRequisicionAplicada = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
        $arRequisicionAplicada = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->findBy(array('codigoAspiranteFk' => $codigoAspirante));
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arAspirante->getNumeroIdentificacion()));
        if ($arEmpleado == null){
            $codigoEmpleado = 0;
        } else {
            $codigoEmpleado = $arEmpleado->getCodigoEmpleadoPk();
        }
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {    
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:historial.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'arRequisicionAplicada' => $arRequisicionAplicada,
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/movimientos/aspirante/detalle/{codigoAspirante}", name="brs_rhu_movimiento_aspirante_detalle")
     */
    public function detalleAction($codigoAspirante) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspirante);
        $form = $this->formularioDetalle($arAspirante);
        $form->handleRequest($request);
        if($form->isValid()) {
            $arrAspiranteados = $request->request->get('ChkAspirante');
            if($form->get('BtnAutorizar')->isClicked()) {
                if($arAspirante->getEstadoAutorizado() == 0) {
                    $arAspirante->setEstadoAutorizado(1);
                    $em->persist($arAspirante);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_aspirante_detalle', array('codigoAspirante' => $codigoAspirante)));   
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if($arAspirante->getEstadoAutorizado() == 1) {
                    $arAspirante->setEstadoAutorizado(0);
                    $em->persist($arAspirante);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_aspirante_detalle', array('codigoAspirante' => $codigoAspirante)));
                }
            }

            if($form->get('BtnAprobar')->isClicked()){
                if($arAspirante->getEstadoAutorizado() == 1) {
                    $strRespuesta = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->estadoAprobadoAspirantes($codigoAspirante);
                    if ($strRespuesta == ''){
                        return $this->redirect($this->generateUrl('brs_rhu_movimiento_aspirante_detalle', array('codigoAspirante' => $codigoAspirante)));
                    }else{
                        $objMensaje->Mensaje('error', $strRespuesta, $this);
                    }
                }    
            }

            if($form->get('BtnCerrar')->isClicked()){
                $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->cerrarAspirante($codigoAspirante);
                return $this->redirect($this->generateUrl('brs_rhu_aspitante_detalle', array('codigoAspirante' => $codigoAspirante)));
            }
        }
        $arRequisicionAplicada = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->findBy(array('codigoAspiranteFk' => $codigoAspirante));
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Aspirante:detalle.html.twig', array(
                    'arAspirante' => $arAspirante,
                    'arRequisicionAplicada' => $arRequisicionAplicada,
                    'form' => $form->createView()
                    ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $session->set('dqlAspiranteLista', $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->listaDQL(
                $session->get('filtroNombreAspirante'),
                $session->get('filtroIdentificacionAspirante'),
                $session->get('filtroBloqueado'),
                $session->get('filtroReintegro'),
                $session->get('filtroCodigoZona')
                ));
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                    ->orderBy('z.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoZona')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuZona", $session->get('filtroCodigoZona'));
        }            
        $form = $this->createFormBuilder()
            ->add('zonaRel', 'entity', $arrayPropiedades)
            ->add('reintegro', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroReintegro')))
            ->add('bloqueado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'), 'data' => $session->get('filtroBloqueado')))    
            ->add('TxtNombre', 'text', array('label'  => 'Nombre', 'data' => $session->get('filtroNombreAspirante')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionAspirante')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {
        
        $form = $this->createFormBuilder()
                    
                    ->getForm();
        return $form;
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroNombreAspirante', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacionAspirante', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroBloqueado', $form->get('bloqueado')->getData());
        $session->set('filtroReintegro', $form->get('reintegro')->getData());
        $session->set('filtroCodigoZona', $controles['zonaRel']);
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        ob_clean();        
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'CIUDAD')
                    ->setCellValue('D1', 'TIPO IDENTIFICACION')
                    ->setCellValue('E1', 'IDENTIFICACION')
                    ->setCellValue('F1', 'CIUDAD NACIMIENTO')
                    ->setCellValue('G1', 'FECHA NACIMIENTO')
                    ->setCellValue('H1', 'CIUDAD EXPEDICION')
                    ->setCellValue('I1', 'RH')
                    ->setCellValue('J1', 'ESTATURA')
                    ->setCellValue('K1', 'PESO')
                    ->setCellValue('L1', 'NOMBRE')
                    ->setCellValue('M1', 'TELEFONO')
                    ->setCellValue('N1', 'CELULAR')
                    ->setCellValue('O1', 'DIRECCION')
                    ->setCellValue('P1', 'BARRIO')
                    ->setCellValue('Q1', 'ESTADO CIVIL')
                    ->setCellValue('R1', 'SEXO')
                    ->setCellValue('S1', 'CORREO')
                    ->setCellValue('T1', 'DISPONIBILIDAD')
                    ->setCellValue('U1', 'BLOQUEADO')
                    ->setCellValue('V1', 'CARGO ASPIRA')
                    ->setCellValue('W1', 'RECOMENDADO')
                    ->setCellValue('X1', 'OPERACION')
                    ->setCellValue('Y1', 'REINTEGRO')
                    ->setCellValue('Z1', 'COMENTARIOS');

        $i = 2;
        $query = $em->createQuery($session->get('dqlAspiranteLista'));
        $arAspirantes = $query->getResult();
        foreach ($arAspirantes as $arAspirantes) {
            
            $ciudad = "";
            if ($arAspirantes->getCodigoCiudadFk() <> null){
                $ciudad = $arAspirantes->getCiudadRel()->getNombre();
            }
            $ciudadNacimiento = "";
            if ($arAspirantes->getCodigoCiudadNacimientoFk() <> null){
                $ciudadNacimiento = $arAspirantes->getCiudadNacimientoRel()->getNombre();
            }
            $ciudadExpedicion = "";
            if ($arAspirantes->getCodigoCiudadNacimientoFk() <> null){
                $ciudadExpedicion = $arAspirantes->getCiudadExpedicionRel()->getNombre();
            }
            $estadoCivil = "";
            if ($arAspirantes->getCodigoEstadoCivilFk() <> null){
                $estadoCivil = $arAspirantes->getEstadoCivilRel()->getNombre();
            }
            $sexo = "";
            if ($arAspirantes->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            } else {
                $sexo = "FEMENINO";
            }
            $disponibilidad = "";
            if ($arAspirantes->getCodigoDisponibilidadFk() == "1"){
                $disponibilidad = "TIEMPO COMPLETO";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "2"){
                $disponibilidad = "MEDIO TIEMPO";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "3"){
                $disponibilidad = "POR HORAS";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "4"){
                $disponibilidad = "DESDE CASA";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "5"){
                $disponibilidad = "PRACTICAS";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "0"){
                $disponibilidad = "NO APLICA";
            }
            $inconsistencia = "NO";
            if ($arAspirantes->getBloqueado() == 1){
                $inconsistencia = "SI";
            }
            $reingreso = "NO";
            if ($arAspirantes->getReintegro() == 1){
                $reingreso = "SI";
            }
            $fecha = "";
            if ($arAspirantes->getFecha() != null){
                $fecha = $arAspirantes->getFecha()->format('Y-m-d');
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAspirantes->getCodigoAspirantePk())
                    ->setCellValue('B' . $i, $fecha)
                    ->setCellValue('C' . $i, $ciudad)
                    ->setCellValue('D' . $i, $arAspirantes->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('E' . $i, $arAspirantes->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $ciudadNacimiento)
                    ->setCellValue('G' . $i, $arAspirantes->getFechaNacimiento()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $ciudadExpedicion)
                    ->setCellValue('I' . $i, $arAspirantes->getRhRel()->getTipo())
                    ->setCellValue('J' . $i, $arAspirantes->getEstatura())
                    ->setCellValue('K' . $i, $arAspirantes->getPeso())
                    ->setCellValue('L' . $i, $arAspirantes->getNombreCorto())
                    ->setCellValue('M' . $i, $arAspirantes->getTelefono())
                    ->setCellValue('N' . $i, $arAspirantes->getCelular())
                    ->setCellValue('O' . $i, $arAspirantes->getDireccion())
                    ->setCellValue('P' . $i, $arAspirantes->getBarrio())
                    ->setCellValue('Q' . $i, $estadoCivil)
                    ->setCellValue('R' . $i, $sexo)
                    ->setCellValue('S' . $i, $arAspirantes->getCorreo())
                    ->setCellValue('T' . $i, $disponibilidad)
                    ->setCellValue('U' . $i, $inconsistencia)
                    ->setCellValue('V' . $i, $arAspirantes->getCargoAspira())
                    ->setCellValue('W' . $i, $arAspirantes->getRecomendado())
                    ->setCellValue('X' . $i, $arAspirantes->getOperacion())
                    ->setCellValue('Y' . $i, $reingreso)
                    ->setCellValue('Z' . $i, $arAspirantes->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Aspirantes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Aspirantes.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }
}
