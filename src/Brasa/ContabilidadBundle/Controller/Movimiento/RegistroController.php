<?php
namespace Brasa\ContabilidadBundle\Controller\Movimiento;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class RegistroController extends Controller
{
    var $strListaDql = "";   
    
    /**
     * @Route("/ctb/movimiento/registro", name="brs_ctb_movimiento_registro")
     */    
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 112, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $this->estadoAnulado = 0;
        $form = $this->formularioFiltro();
        $form->handleRequest($request);        
        $this->lista();        
        if ($form->isValid()) {             
            if ($form->get('BtnEliminar')->isClicked()) {                
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_ctb_movimiento_registro'));                 
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form, $request);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form, $request);
                $this->lista();
                $this->generarExcel();
            }
        }
        $arRegistros = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaContabilidadBundle:Movimiento/Registro:lista.html.twig', array(
            'arRegistros' => $arRegistros,            
            'form' => $form->createView()));
    }
    
    private function lista() {   
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $session->get('filtroCtbRegistroFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroCtbRegistroFechaDesde');
            $strFechaHasta = $session->get('filtroCtbRegistroFechaHasta');
        }        
        $this->strListaDql =  $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->listaDQL(
                    $session->get('filtroCtbCodigoComprobante'),    
                    $session->get('filtroCtbNumero'),
                    $session->get('filtroCtbNumeroReferencia'),
                    $strFechaDesde,
                    $strFechaHasta
                    );
    }        
    
    private function filtrar ($form, Request $request) {
        $session = $this->get('session');                
        $session->set('filtroCtbNumero', $form->get('TxtNumero')->getData());                
        $session->set('filtroCtbNumeroReferencia',$form->get('TxtNumeroReferencia')->getData());                
        $session->set('filtroCtbCodigoComprobante', $form->get('TxtComprobante')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroCtbRegistroFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroCtbRegistroFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroCtbRegistroFiltrarFecha', $form->get('filtrarFecha')->getData());        
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;  
        if($session->get('filtroCtbRegistroFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroCtbRegistroFechaDesde');
        }
        if($session->get('filtroCtbRegistroFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroCtbRegistroFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCtbNumero')))
            ->add('TxtNumeroReferencia', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCtbNumeroReferencia')))
            ->add('TxtComprobante', 'text', array('label'  => 'Codigo','data' => $session->get('filtroCtbCodigoComprobante')))                
            ->add('fechaDesde','date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                
            ->add('fechaHasta','date',  array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                                                            
            ->add('filtrarFecha', 'checkbox', array('required'  => false, 'data' => $session->get('filtroCtbRegistroFiltrarFecha')))                 
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }   
    
    private function generarExcel() {
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'NÚMERO')
                    ->setCellValue('C1', 'NÚMERO REFERENCIA')
                    ->setCellValue('D1', 'COMPROBANTE')
                    ->setCellValue('E1', 'CUENTA')
                    ->setCellValue('F1', 'TERCERO')
                    ->setCellValue('G1', 'DEBITO')
                    ->setCellValue('H1', 'CREDITO')
                    ->setCellValue('I1', 'BASE')
                    ->setCellValue('J1', 'DETALLE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
        $arRegistros = $query->getResult();
        foreach ($arRegistros as $arRegistro) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRegistro->getCodigoRegistroPk())
                    ->setCellValue('B' . $i, $arRegistro->getNumero())
                    ->setCellValue('C' . $i, $arRegistro->getNumeroReferencia())
                    ->setCellValue('D' . $i, $arRegistro->getFecha()->format('Y-m-d'))
                    ->setCellValue('E' . $i, $arRegistro->getCodigoComprobanteFk())
                    ->setCellValue('F' . $i, $arRegistro->getCodigoCuentaFk())
                    ->setCellValue('G' . $i, $arRegistro->getTerceroRel()->getNombreCorto())
                    ->setCellValue('H' . $i, $arRegistro->getDebito())
                    ->setCellValue('I' . $i, $arRegistro->getCredito())
                    ->setCellValue('J' . $i, $arRegistro->getDescripcionContable());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Registros');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Registros.xlsx"');
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
    
    /**
     * @Route("/ctb/movimiento/registro/eliminar", name="brs_ctb_movimiento_registro_eliminar")
     */    
    public function eliminarMasivoAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession(); 
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('TxtNumeroRegistro', 'text', array('label'  => 'Codigo'))
            ->add('comprobanteRel', 'entity', array(
                'class' => 'BrasaContabilidadBundle:CtbComprobante',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                                                            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))    
            ->getForm();
        $form->handleRequest($request);        
        if ($form->isValid()) {             
            if ($form->get('BtnEliminar')->isClicked()) {
                $intNumeroRegistro = $form->get('TxtNumeroRegistro')->getData();
                $arComprobante = $form->get('comprobanteRel')->getData();
                if ($arComprobante == null){
                    $codigoComprobante = "";
                } else {
                    $codigoComprobante = $arComprobante->getCodigoComprobantePk();
                }
                
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                if($intNumeroRegistro != "" || $codigoComprobante != "") {
                    $arRegistros = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                    $arRegistros = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->listaEliminarRegistrosMasivosDql($intNumeroRegistro,$codigoComprobante,$dateFechaDesde,$dateFechaHasta);                
                    foreach ($arRegistros as $codigoRegistro) {
                        $arRegistro = new \Brasa\ContabilidadBundle\Entity\CtbRegistro();
                        $arRegistro = $em->getRepository('BrasaContabilidadBundle:CtbRegistro')->find($codigoRegistro);
                        $em->remove($arRegistro);                    
                    }
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $objMensaje->Mensaje('error', 'Debe seleccionar un filtro', $this);
                }                               
            }
        }
        //$arRegistros = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 50);
        return $this->render('BrasaContabilidadBundle:Movimiento/Registro:eliminarMasivo.html.twig', array(        
            'form' => $form->createView()));
    }
    
    
    
}