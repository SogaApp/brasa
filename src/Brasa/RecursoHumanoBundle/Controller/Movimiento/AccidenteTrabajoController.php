<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuAccidenteTrabajoType;
use Doctrine\ORM\EntityRepository;


class AccidenteTrabajoController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/movimiento/accidente/trabajo/lista", name="brs_rhu_movimiento_accidente_trabajo_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 18, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoAccidenteTrabajo) {
                        $arAccidentesTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
                        $arAccidentesTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
                        if ($arAccidentesTrabajo->getEstadoAccidente() == 1 ) {
                            $objMensaje->Mensaje("error", "No se puede Eliminar el registro, por que ya fue cerrada!", $this);
                        }
                        else {
                            $em->remove($arAccidentesTrabajo);
                            $em->flush();
                        }
                    }
                    return $this->redirect($this->generateUrl('brs_rhu_movimiento_accidente_trabajo_lista'));
                }
                $this->filtrarLista($form);
                $this->listar();
            }
            if ($form->get('BtnCerrar')->isClicked()) {
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoAccidenteTrabajo) {
                        $arAccidentesTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
                        $arAccidentesTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
                        if ($arAccidentesTrabajo->getEstadoAccidente() == 0 ) {
                            $arAccidentesTrabajo->setEstadoAccidente(1);
                            $em->persist($arAccidentesTrabajo);
                            $em->flush();
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_accidente_trabajo_lista'));
                        }
                        else {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_accidente_trabajo_lista'));
                        }
                    }
                }
                $this->filtrarLista($form);
                $this->listar();
            }

            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoCredito = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCredito();
                $objFormatoCredito->Generar($this, $this->strSqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }
        }
        $arAccidentesTrabajo = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/AccidentesTrabajo:lista.html.twig', array(
            'arAccidentesTrabajo' => $arAccidentesTrabajo,
            'form' => $form->createView()
            ));
    }

    /**
     * @Route("/rhu/movimiento/accidente/trabajo/nuevo/{codigoAccidenteTrabajo}", name="brs_rhu_movimiento_accidente_trabajo_nuevo")
     */
    public function nuevoAction($codigoAccidenteTrabajo = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arAccidenteTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();    
        if($codigoAccidenteTrabajo != 0) {
            $arAccidenteTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
        } 
        $form = $this->createForm(new RhuAccidenteTrabajoType, $arAccidenteTrabajo);         
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arAccidenteTrabajo = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                $arEntidadRiesgo = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadRiesgoProfesional();
                $arEntidadRiesgo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadRiesgoProfesional')->find($arConfiguracion->getCodigoEntidadRiesgoFk());
                if(count($arEmpleado) > 0) {
                    $arAccidenteTrabajo->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {                        
                        $arAccidenteTrabajo->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                        $arAccidenteTrabajo->setEntidadRiesgoProfesionalRel(($arEntidadRiesgo));
                        if($codigoAccidenteTrabajo == 0) {
                            $arAccidenteTrabajo->setCodigoUsuario($arUsuario->getUserName());
                        }
                        $em->persist($arAccidenteTrabajo);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_accidente_trabajo_nuevo', array('codigoAccidenteTrabajo' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_rhu_movimiento_accidente_trabajo_lista'));
                        }                        
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }                
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/AccidentesTrabajo:nuevo.html.twig', array(
            'arAccidenteTrabajo' => $arAccidenteTrabajo,
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/movimiento/accidente/trabajo/detalle/{codigoAccidenteTrabajo}", name="brs_rhu_movimiento_accidente_trabajo_detalle")
     */
    public function detalleAction($codigoAccidenteTrabajo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))    
            ->getForm();
        $form->handleRequest($request);

        $arAccidenteTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
        $arAccidenteTrabajo = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->find($codigoAccidenteTrabajo);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoAccidenteTrabajo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoAccidenteTrabajo();
                $objFormatoAccidenteTrabajo->Generar($this, $codigoAccidenteTrabajo);
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/AccidentesTrabajo:detalle.html.twig', array(
                    'arAccidenteTrabajo' => $arAccidenteTrabajo,
                    'form' => $form->createView()
                    ));
    }    
    
    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuAccidenteTrabajo')->listaDql(
                    $session->get('filtroCodigoCentroCosto'),
                    $session->get('filtroIdentificacion'),
                    $session->get('filtroDesde'),
                    $session->get('filtroHasta')
                    );
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
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnCerrar', 'submit', array('label'  => 'Cerrar',))    
            //->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
                
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null){
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d')); 
        }
    }
    
    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();        
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
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CÓDIGO')
	    ->setCellValue('B1', 'IDENTIFICACIÓN')
            ->setCellValue('C1', 'EMPLEADO')
            ->setCellValue('D1', 'CARGO')
            ->setCellValue('E1', 'CENTRO COSTO')
            ->setCellValue('F1', 'FECHA ACCIDENTE')
            ->setCellValue('G1', 'CIUDAD ACCIDENTE')
            ->setCellValue('H1', 'INCAPACIDAD DESDE')
            ->setCellValue('I1', 'INCAPACIDAD HASTA')
            ->setCellValue('J1', 'DÍAS')
            ->setCellValue('K1', 'TIPO ACCIDENTE TRABAJO')
            ->setCellValue('L1', 'FECHA ENVÍA INVESTIGACIÓN')
            ->setCellValue('M1', 'CIE10')
            ->setCellValue('N1', 'DIAGNÓSTICO')
            ->setCellValue('O1', 'NATURALEZA DE LA LESIÓN')
            ->setCellValue('P1', 'PARTE DEL CUERPO AFECTADA')
            ->setCellValue('Q1', 'AGENTE')
            ->setCellValue('R1', 'MECANISMO DEL ACCIDENTE')
            ->setCellValue('S1', 'LUGAR DEL ACCIDENTE')
            ->setCellValue('T1', 'DESCRIPCIÓN DEL ACCIDENTE')
            ->setCellValue('U1', 'ACTO INSEGURO')
            ->setCellValue('V1', 'CONDICIÓN INSEGURA')
            ->setCellValue('W1', 'FACTOR PERSONAL')
            ->setCellValue('X1', 'FACTOR TRABAJO')
            ->setCellValue('Y1', 'PLAN ACCIÓN 1')
            ->setCellValue('Z1', 'TIPO CONTROL 1')
            ->setCellValue('AA1', 'FECHA VERIFICACIÓN 1')
            ->setCellValue('AB1', 'AREA RESPONSABLE')    
            ->setCellValue('AC1', 'PLAN ACCIÓN 2')
            ->setCellValue('AD1', 'TIPO CONTROL 2')    
            ->setCellValue('AE1', 'FECHA VERIFICACIÓN 2')    
            ->setCellValue('AF1', 'AREA RESPONSABLE')    
            ->setCellValue('AG1', 'PLAN ACCIÓN 3')
            ->setCellValue('AH1', 'TIPO CONTROL 3')    
            ->setCellValue('AI1', 'FECHA VERIFICACIÓN 3')
            ->setCellValue('AJ1', 'AREA RESPONSABLE')
            ->setCellValue('AK1', 'PARTICIPANTE DE LA INVESTIGACIÓN 1')
            ->setCellValue('AL1', 'CARGO PARTICIPANTE 1')
            ->setCellValue('AM1', 'PARTICIPANTE DE LA INVESTIGACIÓN 2')
            ->setCellValue('AN1', 'CARGO PARTICIPANTE 2')
            ->setCellValue('AO1', 'PARTICIPANTE DE LA INVESTIGACIÓN 3')
            ->setCellValue('AP1', 'CARGO PARTICIPANTE 3')    
            ->setCellValue('AQ1', 'REPRESENTANTE LEGAL')
            ->setCellValue('AR1', 'CARGO REPRESENTATE LEGAL')
            ->setCellValue('AS1', 'LICENCIA')
            ->setCellValue('AT1', 'RESPONSABLE')    
            ->setCellValue('AU1', 'FECHA VERIFICACIÓN');
                    
        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arAccidentesTrabajo = new \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo();
        $arAccidentesTrabajo = $query->getResult();
        foreach ($arAccidentesTrabajo as $arAccidenteTrabajo) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAccidenteTrabajo->getCodigoAccidenteTrabajoPk())		    
                    ->setCellValue('B' . $i, $arAccidenteTrabajo->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arAccidenteTrabajo->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arAccidenteTrabajo->getEmpleadoRel()->getCargoDescripcion())
                    ->setCellValue('E' . $i, $arAccidenteTrabajo->getCentroCostoRel()->getNombre())
                    ->setCellValue('F' . $i, $arAccidenteTrabajo->getFechaAccidente())
                    ->setCellValue('G' . $i, $arAccidenteTrabajo->getCiudadRel()->getNombre())
                    ->setCellValue('H' . $i, $arAccidenteTrabajo->getFechaIncapacidadDesde())
                    ->setCellValue('I' . $i, $arAccidenteTrabajo->getFechaIncapacidadHasta())
                    ->setCellValue('J' . $i, $arAccidenteTrabajo->getDias())
                    ->setCellValue('K' . $i, $arAccidenteTrabajo->getTipoAccidenteRel()->getNombre())
                    ->setCellValue('L' . $i, $arAccidenteTrabajo->getFechaEnviaInvestigacion())
                    ->setCellValue('M' . $i, $arAccidenteTrabajo->getCie10())
                    ->setCellValue('N' . $i, $arAccidenteTrabajo->getDiagnostico())
                    ->setCellValue('O' . $i, $arAccidenteTrabajo->getNaturalezaLesion())
                    ->setCellValue('P' . $i, $arAccidenteTrabajo->getCuerpoAfectado())
                    ->setCellValue('Q' . $i, $arAccidenteTrabajo->getAgente())
                    ->setCellValue('R' . $i, $arAccidenteTrabajo->getMecanismoAccidente())
                    ->setCellValue('S' . $i, $arAccidenteTrabajo->getLugarAccidente())
                    ->setCellValue('T' . $i, $arAccidenteTrabajo->getDescripcionAccidente())
                    ->setCellValue('U' . $i, $arAccidenteTrabajo->getActoInseguro())
                    ->setCellValue('V' . $i, $arAccidenteTrabajo->getCondicionInsegura())
                    ->setCellValue('W' . $i, $arAccidenteTrabajo->getFactorPersonal())
                    ->setCellValue('X' . $i, $arAccidenteTrabajo->getFactorTrabajo())
                    ->setCellValue('Y' . $i, $arAccidenteTrabajo->getPlanAccion1())
                    ->setCellValue('Z' . $i, $arAccidenteTrabajo->gettipoControlUnoRel()->getNombre())
                    ->setCellValue('AA' . $i, $arAccidenteTrabajo->getFechaVerificacion1())
                    ->setCellValue('AB' . $i, $arAccidenteTrabajo->getAreaResponsable1())
                    ->setCellValue('AC' . $i, $arAccidenteTrabajo->getPlanAccion1())
                    ->setCellValue('AD' . $i, $arAccidenteTrabajo->gettipoControlDosRel()->getNombre())
                    ->setCellValue('AE' . $i, $arAccidenteTrabajo->getFechaVerificacion2())
                    ->setCellValue('AF' . $i, $arAccidenteTrabajo->getAreaResponsable2())
                    ->setCellValue('AG' . $i, $arAccidenteTrabajo->getPlanAccion3())
                    ->setCellValue('AH' . $i, $arAccidenteTrabajo->gettipoControlTresRel()->getNombre())
                    ->setCellValue('AI' . $i, $arAccidenteTrabajo->getFechaVerificacion3())
                    ->setCellValue('AJ' . $i, $arAccidenteTrabajo->getAreaResponsable3())
                    ->setCellValue('AK' . $i, $arAccidenteTrabajo->getParticipanteInvestigacion1())
                    ->setCellValue('AL' . $i, $arAccidenteTrabajo->getCargoParticipanteInvestigacion1())
                    ->setCellValue('AM' . $i, $arAccidenteTrabajo->getParticipanteInvestigacion2())
                    ->setCellValue('AN' . $i, $arAccidenteTrabajo->getCargoParticipanteInvestigacion2())
                    ->setCellValue('AO' . $i, $arAccidenteTrabajo->getParticipanteInvestigacion3())
                    ->setCellValue('AP' . $i, $arAccidenteTrabajo->getCargoParticipanteInvestigacion3())
                    ->setCellValue('AQ' . $i, $arAccidenteTrabajo->getRepresentanteLegal())
                    ->setCellValue('AR' . $i, $arAccidenteTrabajo->getCargoRepresentanteLegal())
                    ->setCellValue('AS' . $i, $arAccidenteTrabajo->getLicencia())
                    ->setCellValue('AT' . $i, $arAccidenteTrabajo->getResponsableVerificacion())
                    ->setCellValue('AU' . $i, $arAccidenteTrabajo->getFechaVerificacion());            
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('Accidentes');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="AccidentesTrabajo.xlsx"');
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
