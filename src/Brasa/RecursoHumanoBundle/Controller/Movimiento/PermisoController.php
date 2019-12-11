<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuPermisoType;


class PermisoController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/rhu/permiso/lista", name="brs_rhu_permiso_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 24, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSelecionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnEliminar')->isClicked()){
                if(count($arrSelecionados) > 0) {
                    foreach ($arrSelecionados AS $codigoPermiso) {
                        $arPermiso = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
                        $arPermiso = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->find($codigoPermiso);
                        if ($arPermiso->getEstadoAutorizado() == 0){
                            $em->remove($arPermiso);
                        }else{
                            $objMensaje->Mensaje("error", "El permiso número ".$codigoPermiso. ", no se puede eliminar, se encuentra autorizado", $this);
                        }   
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_permiso_lista'));
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

        $arPermisos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Permisos:lista.html.twig', array('arPermisos' => $arPermisos, 'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/permiso/nuevo/{codigoPermiso}", name="brs_rhu_permiso_nuevo")
     */
    public function nuevoAction($codigoPermiso = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arPermiso = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
        if($codigoPermiso != 0) {
            $arPermiso = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->find($codigoPermiso);
        } else {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arPermiso->setFechaPermiso(new \DateTime('now'));
        }
        $form = $this->createForm(new RhuPermisoType, $arPermiso);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arrControles = $request->request->All();
            $arPermiso = $form->getData();
            if($arrControles['form_txtNumeroIdentificacion'] != '') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['form_txtNumeroIdentificacion']));
                if(count($arEmpleado) > 0) {
                    $arPermiso->setEmpleadoRel($arEmpleado);
                    if($arEmpleado->getCodigoContratoActivoFk() != '') {
                        $arPermiso->setCentroCostoRel($arEmpleado->getCentroCostoRel());
                        $arPermiso->setDepartamentoEmpresaRel($arEmpleado->getDepartamentoEmpresaRel());
                        $arPermiso->setCargoRel($arEmpleado->getCargoRel());
                        $srtTotalHoras = date_diff($arPermiso->getHoraLlegada(),$arPermiso->getHoraSalida());
                        $arPermiso->setHorasPermiso($srtTotalHoras->format('%H'));
                        if ($codigoPermiso == 0){
                            $arPermiso->setCodigoUsuario($arUsuario->getUserName());
                        }
                        $em->persist($arPermiso);
                        $em->flush();
                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_rhu_permiso_nuevo', array('codigoPermiso' => 0 )));
                        } else {
                            if ($codigoPermiso == 0){
                                return $this->redirect($this->generateUrl('brs_rhu_permiso_detalle', array('codigoPermiso' => $arPermiso->getCodigoPermisoPk())));
                            } else {
                                return $this->redirect($this->generateUrl('brs_rhu_permiso_lista'));
                            }
                            
                        }
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no tiene contrato activo", $this);
                    }
                } else {
                    $objMensaje->Mensaje("error", "El empleado no existe", $this);
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Permisos:nuevo.html.twig', array(
            'arPermiso' => $arPermiso,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/permiso/detalle/{codigoPermiso}", name="brs_rhu_permiso_detalle")
     */
    public function detalleAction($codigoPermiso) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = $this->get('mensajes_brasa');
        $arPermiso = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
        $arPermiso = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->find($codigoPermiso);
        $form = $this->formularioDetalle($arPermiso);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {            
                if($arPermiso->getEstadoAutorizado() == 0) {
                    $arPermiso->setEstadoAutorizado(1);
                    $em->persist($arPermiso);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_permiso_detalle', array('codigoPermiso' => $codigoPermiso)));                                                
                }
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arPermiso->getEstadoAutorizado() == 1) {
                    $arPermiso->setEstadoAutorizado(0);
                    $em->persist($arPermiso);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_permiso_detalle', array('codigoPermiso' => $codigoPermiso)));                                                
                }
            }
            if($form->get('BtnImprimir')->isClicked()) {
                if($arPermiso->getEstadoAutorizado() == 1) {
                    $objFormatoPermiso = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPermiso();
                    $objFormatoPermiso->Generar($this, $codigoPermiso);
                }
            }
        }
        $arPermiso = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->find($codigoPermiso);
        return $this->render('BrasaRecursoHumanoBundle:Movimientos/Permisos:detalle.html.twig', array(
                    'arPermiso' => $arPermiso,
                    'form' => $form->createView()
                    ));
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strListaDql = $em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->listaDQL(
                $session->get('filtroIdentificacion'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroDesde'),
                $session->get('filtroHasta')
                );
    }

    private function formularioFiltro() {
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
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrar ($form) {
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
    
    private function formularioDetalle($ar) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);               
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
        } else {
            $arrBotonDesAutorizar['disabled'] = true;
            $arrBotonImprimir['disabled'] = true;
        }
        $form = $this->createFormBuilder()    
                    ->add('BtnDesAutorizar', 'submit', $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', 'submit', $arrBotonAutorizar)            
                    ->add('BtnImprimir', 'submit', $arrBotonImprimir)                                            
                    ->getForm();  
        return $form;
    }

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'CENTRO COSTOS')
                            ->setCellValue('D1', 'IDENTIFICACIÓN')
                            ->setCellValue('E1', 'EMPLEADO')
                            ->setCellValue('F1', 'CARGO')
                            ->setCellValue('G1', 'DEPARTAMENTO EMPRESA')
                            ->setCellValue('H1', 'JEFE PERMISO')
                            ->setCellValue('I1', 'TIPO PERMISO')
                            ->setCellValue('J1', 'MOTIVO')
                            ->setCellValue('K1', 'HORA SALIDA')
                            ->setCellValue('L1', 'HORA LLEGADA')
                            ->setCellValue('M1', 'HORAS')
                            ->setCellValue('N1', 'AFECTA HORARIO')
                            ->setCellValue('O1', 'AUTORIZADO')
                            ->setCellValue('P1', 'OBSERVACIONES');

                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arPermisos = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
                $arPermisos = $query->getResult();

                foreach ($arPermisos as $arPermisos) {
                    $centroCosto = "";
                    if ($arPermisos->getCodigoCentroCostoFk() != null){
                        $centroCosto = $arPermisos->getCentroCostoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arPermisos->getCodigoPermisoPk())
                            ->setCellValue('B' . $i, $arPermisos->getFechaPermiso()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $centroCosto)
                            ->setCellValue('D' . $i, $arPermisos->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arPermisos->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arPermisos->getCargoRel()->getNombre())
                            ->setCellValue('G' . $i, $arPermisos->getDepartamentoEmpresaRel()->getNombre())
                            ->setCellValue('H' . $i, $arPermisos->getJefeAutoriza())
                            ->setCellValue('I' . $i, $arPermisos->getPermisoTipoRel()->getNombre())
                            ->setCellValue('J' . $i, $arPermisos->getMotivo())
                            ->setCellValue('K' . $i, $arPermisos->getHoraSalida()->format('H:i'))
                            ->setCellValue('L' . $i, $arPermisos->getHoraLlegada()->format('H:i'))
                            ->setCellValue('M' . $i, $arPermisos->getHorasPermiso())
                            ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arPermisos->getAfectaHorario()))
                            ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arPermisos->getEstadoAutorizado()))
                            ->setCellValue('P' . $i, $arPermisos->getObservaciones());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Permisos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Permisos.xlsx"');
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
