<?php

namespace Brasa\RecursoHumanoBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuLicenciaType;

class LicenciasController extends Controller
{
    var $strSqlLista = "";
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
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
            
            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoLicencias = new \Brasa\RecursoHumanoBundle\Formatos\FormatoLicencia();
                $objFormatoLicencias->Generar($this, $this->strSqlLista);
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
        return $this->render('BrasaRecursoHumanoBundle:Licencias:lista.html.twig', array(
            'arLicencias' => $arLicencias,
            'form' => $form->createView()
            ));
    }
    
    public function nuevoAction($codigoCentroCosto, $codigoEmpleado, $codigoLicencia = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        if($codigoEmpleado != 0) {            
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        }
        $arCentroCosto = $em->getRepository('BrasaRecursoHumanoBundle:RhuCentroCosto')->find($codigoCentroCosto);
        $arLicencia = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();       
        if($codigoLicencia != 0) {
            $arLicencia = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->find($codigoLicencia);
        } else {
        $arLicencia->setAfectaTransporte(1);
        $arLicencia->setFechaDesde(new \DateTime('now'));
        $arLicencia->setFechaHasta(new \DateTime('now'));                
        $arLicencia->setCentroCostoRel($arCentroCosto);   
        }
        //$arLicencia->setEstadoCerrada(0);        
        $form = $this->createForm(new RhuLicenciaType(), $arLicencia); 
                    
        $form->handleRequest($request);
        if ($form->isValid()) {            
            $arLicencia = $form->getData(); 
            if($arLicencia->getFechaDesde() <= $arLicencia->getFechaHasta()) {
                if($em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->validarFecha($arLicencia->getFechaDesde(), $arLicencia->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(),"")) {                    
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->validarFecha($arLicencia->getFechaDesde(), $arLicencia->getFechaHasta(), $arEmpleado->getCodigoEmpleadoPk(), $arLicencia->getCodigoLicenciaPk())) {
                        if($arEmpleado->getFechaContrato() <= $arLicencia->getFechaDesde()) {
                            if($codigoEmpleado != 0) { 
                                $arLicencia->setEmpleadoRel($arEmpleado);                
                            }
                            $intDias = $arLicencia->getFechaDesde()->diff($arLicencia->getFechaHasta());
                            $intDias = $intDias->format('%a');
                            $intDias = $intDias + 1;

                            $arLicencia->setCantidad($intDias);                            
                            $em->persist($arLicencia);
                            $em->flush();                        
                            if($form->get('guardarnuevo')->isClicked()) {
                                return $this->redirect($this->generateUrl('brs_rhu_pagos_adicionales_agregar_licencia', array('codigoCentroCosto' => $codigoCentroCosto)));
                            } else {
                                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                            }                            
                        } else {
                            echo "La fecha de inicio del contrato es mayor a la licencia";
                        }                        
                    } else {
                        echo "existe otra licencia en este rango de fechas";
                    }                                           
                } else {
                    echo "Hay incapacidades que se cruzan con la fecha de la licencia";
                }
            } else {
                echo "La fecha desde debe ser inferior o igual a la fecha hasta";
            }            
        }                

        return $this->render('BrasaRecursoHumanoBundle:Licencias:nuevo.html.twig', array(
            'arCentroCosto' => $arCentroCosto,
            'arEmpleado' => $arEmpleado,
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
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                           
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))                            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
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
                $session->get('filtroIdentificacion')
                );  
    }         
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');        
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);                
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }         
    
    private function generarExcel() {
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

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'CENTRO COSTO')
                    ->setCellValue('E1', 'DESDE')
                    ->setCellValue('F1', 'HASTA')
                    ->setCellValue('G1', 'DÍAS');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);        
        $arLicencias = $query->getResult();
        foreach ($arLicencias as $arLicencia) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arLicencia->getCodigoIncapacidadPk())
                    ->setCellValue('B' . $i, $arLicencia->getEmpleadoRel()->getnumeroIdentificacion())
                    ->setCellValue('C' . $i, $arLicencia->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arLicencia->getCentroCostoRel()->getNombre())
                    ->setCellValue('E' . $i, $arLicencia->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('F' . $i, $arLicencia->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('G' . $i, $arLicencia->getCantidad());
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
