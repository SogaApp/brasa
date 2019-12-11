<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuIncapacidadType;

class IncapacidadController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/movimiento/incapacidad/", name="brs_rhu_movimiento_incapacidad")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 12, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->formularioLista();
                $this->listar();
                $this->generarExcel();
            }
            
            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoIncapacidades = new \Brasa\RecursoHumanoBundle\Formatos\FormatoIncapacidad();
                $objFormatoIncapacidades->Generar($this, $this->strSqlLista);
            }

            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $em->remove($arIncapacidad);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad'));
                }
            }
            if($form->get('BtnLegalizar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoIncapacidad) {
                        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
                        $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
                        $arIncapacidad->setEstadoLegalizado(1);
                        $em->persist($arIncapacidad);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad'));
                }
            }            
            
        }
        $arIncapacidades = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Incapacidades:lista.html.twig', array(
            'arIncapacidades' => $arIncapacidades,
            'form' => $form->createView()
            ));
    }    

    /**
     * @Route("/rhu/movimiento/incapacidad/nuevo/{codigoIncapacidad}", name="brs_rhu_movimiento_incapacidad_nuevo")
     */    
    public function nuevoAction($codigoIncapacidad = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();                 
        $arIncapacidad = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();       
        if($codigoIncapacidad != 0) {
            $arIncapacidad = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->find($codigoIncapacidad);
        } else {
            $arIncapacidad->setEstadoCobrar(true);
            $arIncapacidad->setFecha(new \DateTime('now'));
            $arIncapacidad->setFechaDesde(new \DateTime('now'));
            $arIncapacidad->setFechaHasta(new \DateTime('now'));                
        }        

        $form = $this->createForm(new RhuIncapacidadType(), $arIncapacidad);                     
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arIncapacidad = $form->getData();                          
            $arrControles = $request->request->All();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));                
                if(count($arEmpleado) > 0) {                                            
                    $arIncapacidad->setEmpleadoRel($arEmpleado);
                    if($arrControles['form_txtCodigoIncapacidadDiagnostico'] != '') {       
                        $arIncapacidadDiagnostico = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadDiagnostico();
                        $arIncapacidadDiagnostico = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidadDiagnostico')->findOneBy(array('codigo' => $arrControles['form_txtCodigoIncapacidadDiagnostico']));                                        
                        if(count($arIncapacidadDiagnostico) > 0) { 
                            $arIncapacidad->setIncapacidadDiagnosticoRel($arIncapacidadDiagnostico);
                            $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);                        
                            if($arIncapacidad->getFechaDesde() <= $arIncapacidad->getFechaHasta()) {
                                $diasIncapacidad = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
                                $diasIncapacidad = $diasIncapacidad->format('%a');
                                $diasIncapacidad = $diasIncapacidad + 1;
                                if ($diasIncapacidad < 180){
                                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arIncapacidad->getCodigoIncapacidadPk())) {                    
                                        if($em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->validarFecha($arIncapacidad->getFechaDesde(), $arIncapacidad->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(),"")) {
                                            if($arIncapacidad->getFechaDesde() >= $arEmpleado->getFechaContrato()) {
                                                $intDias = $arIncapacidad->getFechaDesde()->diff($arIncapacidad->getFechaHasta());
                                                $intDias = $intDias->format('%a');
                                                $intDias = $intDias + 1;
                                                $intDiasCobro = $this->diasCobro($intDias, $arIncapacidad->getEstadoProrroga(), $arIncapacidad->getIncapacidadTipoRel()->getTipo());
                                                $arIncapacidad->setDiasCobro($intDiasCobro);
                                                $arIncapacidad->setCantidad($intDias);                                                                                                                                    
                                                $arIncapacidad->setEntidadSaludRel($arEmpleado->getEntidadSaludRel());
                                                $floVrIncapacidad = 0;
                                                $douVrDia = $arEmpleado->getVrSalario() / 30;
                                                $douVrDiaSalarioMinimo = $arConfiguracion->getVrSalario() / 30;
                                                $douPorcentajePago = $arIncapacidad->getIncapacidadTipoRel()->getPagoConceptoRel()->getPorPorcentaje();
                                                $arIncapacidad->setPorcentajePago($douPorcentajePago);
                                                if($arIncapacidad->getIncapacidadTipoRel()->getCodigoIncapacidadTipoPk() == 1) {
                                                    if($arEmpleado->getVrSalario() <= $arConfiguracion->getVrSalario()) {
                                                        $floVrIncapacidad = $intDiasCobro * $douVrDia;                    
                                                    }
                                                    if($arEmpleado->getVrSalario() > $arConfiguracion->getVrSalario() && $arEmpleado->getVrSalario() <= $arConfiguracion->getVrSalario() * 1.5) {
                                                        $floVrIncapacidad = $intDiasCobro * $douVrDiaSalarioMinimo;                    
                                                    }
                                                    if($arEmpleado->getVrSalario() > ($arConfiguracion->getVrSalario() * 1.5)) {
                                                        $floVrIncapacidad = $intDiasCobro * $douVrDia;
                                                        $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago)/100;                    
                                                    }
                                                } else {
                                                    $floVrIncapacidad = $intDiasCobro * $douVrDia;
                                                    $floVrIncapacidad = ($floVrIncapacidad * $douPorcentajePago)/100;                
                                                }     
                                                $arIncapacidad->setVrCobro($floVrIncapacidad);
                                                $arIncapacidad->setVrIncapacidad($floVrIncapacidad);
                                                $arIncapacidad->setVrSaldo($floVrIncapacidad);
                                                $arIncapacidad->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                                                if($codigoIncapacidad == 0) {
                                                    $arIncapacidad->setCodigoUsuario($arUsuario->getUserName());
                                                    $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                                                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                                                    } else {
                                                        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                                                    }
                                                    $arIncapacidad->setContratoRel($arContrato);                                            
                                                }
                                                $em->persist($arIncapacidad);
                                                $em->flush();

                                                if($form->get('guardarnuevo')->isClicked()) {                                                        
                                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad_nuevo', array('codigoIncapacidad' => 0)));                                        
                                                } else {
                                                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_incapacidad'));
                                                }                             
                                            } else {                                    
                                                $objMensaje->Mensaje("error", "No puede ingresar novedades antes de la fecha de inicio del contrato", $this);
                                            }                  
                                        } else {
                                            $objMensaje->Mensaje("error", "Existe una licencia en este periodo de fechas", $this);
                                        }                                                           
                                    } else {
                                        $objMensaje->Mensaje("error", "Existe otra incapaciad del empleado en esta fecha", $this);
                                    }               
                                } else {
                                    $objMensaje->Mensaje("error", "La incapacidad no debe ser mayor 180 dias", $this);
                                }
                            }else {
                                $objMensaje->Mensaje("error", "La fecha desde debe ser inferior o igual a la fecha hasta de la incapacidad", $this);
                            }                            
                        } else {
                            $objMensaje->Mensaje("error", "El diagnostico no existe", $this);                                    
                        }                        
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);                                    
                }
            }            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Incapacidades:nuevo.html.twig', array(
            'arIncapacidad' => $arIncapacidad,
            'form' => $form->createView()));
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();    
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
                $session->set('filtroRhuCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
            }  else {
                $session->set('filtroIdentificacion', null);
                $session->set('filtroRhuCodigoEmpleado', null);
            }
        } else {
            $session->set('filtroRhuCodigoEmpleado', null);            
        }        
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')                                        
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));                                    
        }
        $arrayPropiedadesIncapacidadTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuIncapacidadTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')                                        
                    ->orderBy('it.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroRhuIncapacidadTipo')) {
            $arrayPropiedadesIncapacidadTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuIncapacidadTipo", $session->get('filtroRhuIncapacidadTipo'));                                    
        }        
        $form = $this->createFormBuilder()                        
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', 'text', array('label'  => 'Nombre','data' => $strNombreEmpleado))                
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                                       
            ->add('incapacidadTipoRel', 'entity', $arrayPropiedadesIncapacidadTipo)                                                                       
            ->add('TxtNumeroEps', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIncapacidadNumeroEps')))                                                        
            ->add('estadoTranscripcion', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO'),'data' => $session->get('filtroIncapacidadEstadoTranscripcion')))                                                    
            ->add('estadoLegalizado', 'choice', array('choices'   => array('2' => 'TODOS', '1' => 'LEGALIZADA', '0' => 'SIN LEGALIZAR'),'data' => $session->get('filtroIncapacidadEstadoLegalizado')))                                                    
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnLegalizar', 'submit', array('label'  => 'Legalizar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->listaDQL(                   
                "",
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroIncapacidadEstadoTranscripcion'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroIncapacidadNumeroEps'),
                $session->get('filtroRhuIncapacidadTipo'),
                $session->get('filtroIncapacidadEstadoLegalizado')
                );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroRhuIncapacidadTipo', $controles['incapacidadTipoRel']);                
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());        
        $session->set('filtroIncapacidadNumeroEps', $form->get('TxtNumeroEps')->getData());                
        $session->set('filtroIncapacidadEstadoTranscripcion', $form->get('estadoTranscripcion')->getData());                        
        $session->set('filtroIncapacidadEstadoLegalizado', $form->get('estadoLegalizado')->getData());                        
    }         
    
    private function generarExcel() {
        $objFuncinoes = new \Brasa\GeneralBundle\MisClases\Funciones();        
        ob_clean();
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
        for($col = 'A'; $col !== 'L'; $col++) {            
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        } 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'ID')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'EPS')
                    ->setCellValue('D1', 'TIPO')
                    ->setCellValue('E1', 'DOCUMENTO')
                    ->setCellValue('F1', 'NOMBRE')
                    ->setCellValue('G1', 'CENTRO COSTO')
                    ->setCellValue('H1', 'DESDE')
                    ->setCellValue('I1', 'HASTA')
                    ->setCellValue('J1', 'DÍAS')
                    ->setCellValue('K1', 'LEG')
                    ->setCellValue('L1', 'COD')
                    ->setCellValue('M1', 'DIAGNOSTICO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);        
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $query->getResult();
        foreach ($arIncapacidades as $arIncapacidad) {
        $centroCosto = "";
        if ($arIncapacidad->getCodigoCentroCostoFk() != null){
            $centroCosto = $arIncapacidad->getCentroCostoRel()->getNombre();

        }
        $salud = "";
        if ($arIncapacidad->getCodigoEntidadSaludFk() != null){
            $salud = $arIncapacidad->getEntidadSaludRel()->getNombre();

        }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIncapacidad->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arIncapacidad->getNumeroEps())
                    ->setCellValue('C' . $i, $salud)
                    ->setCellValue('D' . $i, $arIncapacidad->getIncapacidadTipoRel()->getNombre())
                    ->setCellValue('E' . $i, $arIncapacidad->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('F' . $i, $arIncapacidad->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $centroCosto)
                    ->setCellValue('H' . $i, $arIncapacidad->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('I' . $i, $arIncapacidad->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('J' . $i, $arIncapacidad->getCantidad())
                    ->setCellValue('K' . $i, $objFuncinoes->devuelveBoolean($arIncapacidad->getEstadoLegalizado()))
                    ->setCellValue('L' . $i, $arIncapacidad->getIncapacidadDiagnosticoRel()->getCodigo());
            if($arIncapacidad->getCodigoIncapacidadDiagnosticoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $i, $arIncapacidad->getIncapacidadDiagnosticoRel()->getNombre());
            }
            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Incapacidades');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Incapacidades.xlsx"');
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
    
    private function diasCobro($diasIncapacidad = 0, $prorroga = false, $tipo = 1) {
        $dias = 0;        
        if($tipo == 1) {
            if($prorroga == 0) {
                if($diasIncapacidad >= 3) {
                    $dias = $diasIncapacidad - 2;
                }                
            } else {
                $dias = $diasIncapacidad;
            }
        }
        if($tipo == 2) {
            if($prorroga == 0) {
                if($diasIncapacidad >= 2) {
                    $dias = $diasIncapacidad - 1;
                }                
            } else {
                $dias = $diasIncapacidad;
            }
        }        
        return $dias;
    }
}
