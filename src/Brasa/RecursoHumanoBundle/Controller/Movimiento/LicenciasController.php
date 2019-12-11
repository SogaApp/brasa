<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLicenciaType;

class LicenciasController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/licencias/lista", name="brs_rhu_licencias_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 11, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }

            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoLicencia) {
                        $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
                        $arLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($codigoLicencia);
                        $em->remove($arLicencia);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_licencias_lista'));
                }
            }
        }
        $arLicencias = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Licencias:lista.html.twig', array(
            'arLicencias' => $arLicencias,
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/rhu/licencias/nuevo/{codigoLicencia}", name="brs_rhu_licencias_nuevo")
     */
    public function nuevoAction($codigoLicencia = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();        
        $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        
        if($codigoLicencia == 0) {
            $arLicencia->setAfectaTransporte(1);
            $arLicencia->setFechaDesde(new \DateTime('now'));
            $arLicencia->setFechaHasta(new \DateTime('now'));                  
        } else {
            $arLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($codigoLicencia);
        }
        $form = $this->createForm(new RhuLicenciaType(), $arLicencia);                     
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();            
            $arLicencia = $form->getData(); 
            $arrControles = $request->request->All();
            
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));                
                if(count($arEmpleado) > 0) {
                    // fin validacion
                    $arLicencia->setEmpleadoRel($arEmpleado);
                    if($arLicencia->getFechaDesde() <= $arLicencia->getFechaHasta()) {
                        if($em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->validarFecha($arLicencia->getFechaDesde(), $arLicencia->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(),"")) {                    
                            if($em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->validarFecha($arLicencia->getFechaDesde(), $arLicencia->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arLicencia->getCodigoLicenciaPk())) {
                                if($arEmpleado->getFechaContrato() <= $arLicencia->getFechaDesde()) {
                                    $arLicencia->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                                    $intDias = $arLicencia->getFechaDesde()->diff($arLicencia->getFechaHasta());
                                    $intDias = $intDias->format('%a');
                                    $intDias = $intDias + 1;
                                    $arLicencia->setCantidad($intDias);
                                    if($codigoLicencia == 0) {
                                        $arLicencia->setCodigoUsuario($arUsuario->getUserName());
                                        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
                                        if($arEmpleado->getCodigoContratoActivoFk() != '') {
                                            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoActivoFk());
                                        } else {
                                            $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($arEmpleado->getCodigoContratoUltimoFk());
                                        }
                                        $arLicencia->setContratoRel($arContrato);                                        
                                    }
                                    $em->persist($arLicencia);
                                    $em->flush();                        
                                    if($form->get('guardarnuevo')->isClicked()) {
                                        return $this->redirect($this->generateUrl('brs_rhu_licencias_nuevo', array('codigoLicencia' => 0)));                                        
                                    } else {
                                        return $this->redirect($this->generateUrl('brs_rhu_licencias_lista'));
                                    }   
                                } else {
                                    $objMensaje->Mensaje("error", "La fecha de inicio del contrato es mayor a la licencia", $this); 
                                }                        
                            } else {                                
                                $objMensaje->Mensaje("error", "Existe otra licencia en este rango de fechas", $this); 
                            }                                           
                        } else {
                            $objMensaje->Mensaje("error", "Hay incapacidades que se cruzan con la fecha de la licencia", $this); 
                        }
                    } else {                        
                        $objMensaje->Mensaje("error", "La fecha desde debe ser inferior o igual a la fecha hasta", $this);
                    }                     
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this); 
                }                
            }           
        }                

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Licencias:nuevo.html.twig', array(            
            'arLicencia' => $arLicencia,
            'form' => $form->createView()));
    }
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
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
        $arrayPropiedadesLicenciaTipo = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuLicenciaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('lt')                                        
                    ->orderBy('lt.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroLicenciaTipo')) {
            $arrayPropiedadesLicenciaTipo['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuLicenciaTipo", $session->get('filtroLicenciaTipo'));                                    
        }
        $strNombreEmpleado = "";
        if($session->get('filtroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroIdentificacion')));
            if($arEmpleado) {                
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroIdentificacion', null);
            }          
        }
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                           
            ->add('licenciaTipoRel', 'entity', $arrayPropiedadesLicenciaTipo)                                               
            ->add('txtNumeroIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('txtNombreCorto', 'text', array('label'  => 'Nombre','data' => $strNombreEmpleado))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();        
        return $form;
    }      
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();                
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->listaDQL(                   
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroIdentificacion'),
                $session->get('filtroLicenciaTipo')
                );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroIdentificacion', $form->get('txtNumeroIdentificacion')->getData());
        $session->set('filtroLicenciaTipo', $controles['licenciaTipoRel']);
    }         
    
    private function generarExcel() {
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'LICENCIA')
                    ->setCellValue('F1', 'DESDE')
                    ->setCellValue('G1', 'HASTA')
                    ->setCellValue('H1', 'DÍAS');

        $i = 2;
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $query = $em->createQuery($this->strSqlLista);        
        $arLicencias = $query->getResult();
        foreach ($arLicencias as $arLicencia) {
            $centroCosto = "";
            if ($arLicencia->getCodigoCentroCostoFk() != null){
                $centroCosto = $arLicencia->getCentroCostoRel()->getNombre();
                
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arLicencia->getCodigoLicenciaPk())
                    ->setCellValue('B' . $i, $arLicencia->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('C' . $i, $arLicencia->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $centroCosto)
                    ->setCellValue('E' . $i, $arLicencia->getLicenciaTipoRel()->getNombre() )
                    ->setCellValue('F' . $i, $arLicencia->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('G' . $i, $arLicencia->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $arLicencia->getCantidad());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Licencias');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Licencias.xlsx"');
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
