<?php
namespace Brasa\TurnoBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurNovedadType;

class NovedadController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/movimiento/novedad", name="brs_tur_movimiento_novedad")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();                
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 30, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {              
            if ($form->get('BtnAplicar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {                                
                        $em->getRepository('BrasaTurnoBundle:TurNovedad')->aplicar($codigo);                                                            
                    }            
                }                
                return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad'));                
            }            
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad'));                
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
        }

        $arNovedades = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Movimientos/Novedad:lista.html.twig', array(
            'arNovedades' => $arNovedades,            
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/tur/movimiento/novedad/nuevo/{codigoNovedad}", name="brs_tur_movimiento_novedad_nuevo")
     */
    public function nuevoAction(Request $request, $codigoNovedad) {        
        $em = $this->getDoctrine()->getManager();        
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();
        if($codigoNovedad != 0) {
            $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);
        }else{
            $arNovedad->setFechaDesde(new \DateTime('now'));
            $arNovedad->setFechaHasta(new \DateTime('now'));
        }
        $form = $this->createForm(TurNovedadType::class, $arNovedad);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arNovedad = $form->getData();
            $arrControles = $request->request->All();
            $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
            if($arrControles['txtCodigoRecurso'] != '') {                
                $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['txtCodigoRecurso']);                
                if(count($arRecurso) > 0) {
                    $arNovedad->setRecursoRel($arRecurso);
                }
            }
            $arRecursoReemplazo = new \Brasa\TurnoBundle\Entity\TurRecurso();
            if($arrControles['txtCodigoRecursoReemplazo'] != '') {                
                $arRecursoReemplazo = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($arrControles['txtCodigoRecursoReemplazo']);                
                if(count($arRecursoReemplazo) > 0) {
                    $arNovedad->setRecursoReemplazoRel($arRecursoReemplazo);
                }
            } else {
                $arNovedad->setRecursoReemplazoRel(null);
            }    
            if($arNovedad->getRecursoRel()) {
                $arUsuario = $this->getUser();
                $arNovedad->setUsuario($arUsuario->getUserName());            
                $em->persist($arNovedad);
                $em->flush();

                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad_nuevo', array('codigoNovedad' => 0 )));
                } else {
                    return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad'));
                }                 
            } else {
                $objMensaje->Mensaje('error', 'Debe seleccionar un recurso', $this);
            }                                                                              
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Novedad:nuevo.html.twig', array(
            'arNovedad' => $arNovedad,
            'form' => $form->createView()));
    }

    public function detalleAction(Request $request, $codigoNovedad) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = $this->get('mensajes_brasa');
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();
        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);
        $form = $this->formularioDetalle($arNovedad);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnAutorizar')->isClicked()) {  
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoNovedad);                
                if($arNovedad->getEstadoAutorizado() == 0) {
                    if($em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->numeroRegistros($codigoNovedad) > 0) {
                        $arNovedad->setEstadoAutorizado(1);
                        $em->persist($arNovedad);
                        $em->flush();                        
                    } else {
                        $objMensaje->Mensaje('error', 'Debe adicionar detalles a la novedad', $this);
                    }                    
                }
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));                
            }
            if($form->get('BtnDesAutorizar')->isClicked()) {            
                if($arNovedad->getEstadoAutorizado() == 1) {
                    $arNovedad->setEstadoAutorizado(0);
                    $em->persist($arNovedad);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));                
                }
            }   
            if($form->get('BtnAprobar')->isClicked()) {            
                if($arNovedad->getEstadoAutorizado() == 1) {
                    $arNovedad->setEstadoAprobado(1);
                    $em->persist($arNovedad);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));                
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoNovedad);                
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }
            if($form->get('BtnDetalleEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }  
            if($form->get('BtnOtroActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                foreach ($arrControles['LblCodigoNovedadOtro'] as $intCodigo) {
                    $arNovedadOtro = new \Brasa\TurnoBundle\Entity\TurNovedadOtro();
                    $arNovedadOtro = $em->getRepository('BrasaTurnoBundle:TurNovedadOtro')->find($intCodigo);
                    $arNovedadDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);                                                          
                    $em->persist($arNovedadDetalle);
                }
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }
            if($form->get('BtnOtroEliminar')->isClicked()) {   
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->eliminarSeleccionados($arrSeleccionados);
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);
                return $this->redirect($this->generateUrl('brs_tur_novedad_detalle', array('codigoNovedad' => $codigoNovedad)));
            }   
            if($form->get('BtnImprimir')->isClicked()) {
                $strResultado = $em->getRepository('BrasaTurnoBundle:TurNovedad')->imprimir($codigoNovedad);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    $objNovedad = new \Brasa\TurnoBundle\Formatos\FormatoNovedad();
                    $objNovedad->Generar($this, $codigoNovedad);                   
                }                
            }                        
        }

        $arNovedadDetalle = new \Brasa\TurnoBundle\Entity\TurNovedadDetalle();
        $arNovedadDetalle = $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->findBy(array ('codigoNovedadFk' => $codigoNovedad));
        $arNovedadOtros = new \Brasa\TurnoBundle\Entity\TurNovedadOtro();
        $arNovedadOtros = $em->getRepository('BrasaTurnoBundle:TurNovedadOtro')->findBy(array ('codigoNovedadFk' => $codigoNovedad));        
        return $this->render('BrasaTurnoBundle:Movimientos/Novedad:detalle.html.twig', array(
                    'arNovedad' => $arNovedad,
                    'arNovedadDetalle' => $arNovedadDetalle,
                    'arNovedadOtros' => $arNovedadOtros,
                    'form' => $form->createView()
                    ));
    }       

    /**
     * @Route("/tur/movimiento/novedad/cambiar/tipo{codigoNovedad}", name="brs_tur_movimiento_novedad_cambiar_tipo")
     */    
    public function cambiarTipoAction(Request $request, $codigoNovedad) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arNovedad = new \Brasa\TurnoBundle\Entity\TurNovedad();
        $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedad')->find($codigoNovedad);
        $formNovedad = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_tur_movimiento_novedad_cambiar_tipo', array('codigoNovedad' => $codigoNovedad)))            
            ->add('novedadTipoRel', 'entity', array(
                'class' => 'BrasaTurnoBundle:TurNovedadTipo',
                        'choice_label' => 'nombre',
            ))            
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $formNovedad->handleRequest($request);                
        if ($formNovedad->isValid()) {                        
            $arNovedadTipo = new \Brasa\TurnoBundle\Entity\TurNovedadTipo();            
            $arNovedadTipo = $formNovedad->get('novedadTipoRel')->getData(); 
            if($arNovedad->getCodigoNovedadTipoFk() != $arNovedadTipo->getCodigoNovedadTipoPk()) {
                $arNovedad->setNovedadTipoRel($arNovedadTipo);
                $em->persist($arNovedad);
                $em->flush();
                $em->getRepository('BrasaTurnoBundle:TurNovedad')->aplicar($codigoNovedad, 0, 1); 
            }
            return $this->redirect($this->generateUrl('brs_tur_movimiento_novedad'));
        }
        return $this->render('BrasaTurnoBundle:Movimientos/Novedad:cambiarTipo.html.twig', array(
            'arNovedad' => $arNovedad,
            'formNovedad' => $formNovedad->createView()
        ));
    }    
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurNovedad')->listaDQL(
                $session->get('filtroNovedadNumero'),
                $session->get('filtroCodigoRecurso'),
                $session->get('filtroNovedadEstadoAplicado'),
                $session->get('filtroCodigoNovedad'));
    }

    private function filtrar ($form) {       
        $session = new session;     
        $session->set('filtroNovedadNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigoRecurso')->getData());        
        $session->set('filtroCodigoNovedad', $form->get('TxtCodigoNovedad')->getData());
        $session->set('filtroNovedadEstadoAplicado', $form->get('estadoAplicado')->getData());  
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreRecurso = "";
        if($session->get('filtroCodigoRecurso')) {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($session->get('filtroCodigoRecurso'));
            if($arRecurso) {                
                $strNombreRecurso = $arRecurso->getNombreCorto();
            }  else {
                $session->set('filtroCodigoRecurso', null);
            }          
        }
        $strNombreNovedad = "";
        if($session->get('filtroCodigoNovedad')) {
            $arNovedad = $em->getRepository('BrasaTurnoBundle:TurNovedadTipo')->find($session->get('filtroCodigoNovedad'));
            if($arNovedad) {                
                $strNombreNovedad = $arNovedad->getNombre();
            }  else {
                $session->set('filtroCodigoNovedad', null);
            }          
        }        
        $form = $this->createFormBuilder()
            ->add('TxtNumero', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroNovedadNumero')))
            ->add('TxtCodigoRecurso', TextType::class, array('label'  => 'Nit','data' => $session->get('filtroCodigoRecurso')))
            ->add('TxtNombreRecurso', TextType::class, array('label'  => 'NombreCliente','data' => $strNombreRecurso))                                
            ->add('TxtCodigoNovedad', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroCodigoNovedad')))
            ->add('TxtNombreNovedad', TextType::class, array('label'  => 'Nombre','data' => $strNombreNovedad))                                                
            ->add('estadoAplicado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'APLICADAS', '0' => 'SIN APLICAR'), 'data' => $session->get('filtroNovedadEstadoAplicado')))                
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnAplicar', SubmitType::class, array('label'  => 'Aplicar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);        
        $arrBotonAprobar = array('label' => 'Aprobar', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonOtroActualizar = array('label' => 'Actualizar', 'disabled' => false);
        $arrBotonOtroEliminar = array('label' => 'Eliminar', 'disabled' => false);
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = false;            
            $arrBotonDetalleEliminar['disabled'] = true;
            $arrBotonOtroEliminar['disabled'] = true;
            $arrBotonDetalleActualizar['disabled'] = true;
            $arrBotonOtroActualizar['disabled'] = true;
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
        if($ar->getEstadoAprobado() == 1) {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonAprobar['disabled'] = true;            
        } 
        $form = $this->createFormBuilder()
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                 
                    ->add('BtnAprobar', SubmitType::class, $arrBotonAprobar)                 
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->add('BtnOtroActualizar', SubmitType::class, $arrBotonOtroActualizar)
                    ->add('BtnOtroEliminar', SubmitType::class, $arrBotonOtroEliminar)
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                           
        }     
        for($col = 'H'; $col !== 'N'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'DESDE')                
                    ->setCellValue('D1', 'HASTA')
                    ->setCellValue('E1', 'CODIGO')
                    ->setCellValue('F1', 'DOCUMENTO')
                    ->setCellValue('G1', 'RECURSO')
                    ->setCellValue('H1', 'APLICADA');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arNovedades = new \Brasa\TurnoBundle\Entity\TurNovedad();
        $arNovedades = $query->getResult();

        foreach ($arNovedades as $arNovedad) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arNovedad->getCodigoNovedadPk())
                    ->setCellValue('B' . $i, $arNovedad->getNovedadTipoRel()->getNombre())
                    ->setCellValue('C' . $i, $arNovedad->getFechaDesde()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arNovedad->getFechaHasta()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arNovedad->getCodigoRecursoFk())
                    ->setCellValue('F' . $i, $arNovedad->getRecursoRel()->getNumeroIdentificacion())
                    ->setCellValue('G' . $i, $arNovedad->getRecursoRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arNovedad->getEstadoAplicada()));          
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Novedades');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Novedades.xlsx"');
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

    private function actualizarDetalle($arrControles, $codigoNovedad) {
        $em = $this->getDoctrine()->getManager();
        $intIndice = 0;
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arNovedadDetalle = new \Brasa\TurnoBundle\Entity\TurNovedadDetalle();
                $arNovedadDetalle = $em->getRepository('BrasaTurnoBundle:TurNovedadDetalle')->find($intCodigo);
                $arNovedadDetalle->setCantidad($arrControles['TxtCantidad'.$intCodigo]);
                if($arrControles['TxtValorAjustado'.$intCodigo] != '') {
                    $arNovedadDetalle->setVrPrecioAjustado($arrControles['TxtValorAjustado'.$intCodigo]);                
                }                     
                if(isset($arrControles['chkLunes'.$intCodigo])) {
                    $arNovedadDetalle->setLunes(1);
                } else {
                    $arNovedadDetalle->setLunes(0);
                }
                if(isset($arrControles['chkMartes'.$intCodigo])) {
                    $arNovedadDetalle->setMartes(1);
                } else {
                    $arNovedadDetalle->setMartes(0);
                }
                if(isset($arrControles['chkMiercoles'.$intCodigo])) {
                    $arNovedadDetalle->setMiercoles(1);
                } else {
                    $arNovedadDetalle->setMiercoles(0);
                }
                if(isset($arrControles['chkJueves'.$intCodigo])) {
                    $arNovedadDetalle->setJueves(1);
                } else {
                    $arNovedadDetalle->setJueves(0);
                }
                if(isset($arrControles['chkViernes'.$intCodigo])) {
                    $arNovedadDetalle->setViernes(1);
                } else {
                    $arNovedadDetalle->setViernes(0);
                }
                if(isset($arrControles['chkSabado'.$intCodigo])) {
                    $arNovedadDetalle->setSabado(1);
                } else {
                    $arNovedadDetalle->setSabado(0);
                }
                if(isset($arrControles['chkDomingo'.$intCodigo])) {
                    $arNovedadDetalle->setDomingo(1);
                } else {
                    $arNovedadDetalle->setDomingo(0);
                }
                if(isset($arrControles['chkFestivo'.$intCodigo])) {
                    $arNovedadDetalle->setFestivo(1);
                } else {
                    $arNovedadDetalle->setFestivo(0);
                }                    
                $em->persist($arNovedadDetalle);
            }
            $em->flush();
            $em->getRepository('BrasaTurnoBundle:TurNovedad')->liquidar($codigoNovedad);                   
        }
    }
    
}