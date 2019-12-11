<?php
namespace Brasa\TurnoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ServiciosDetallesRecursosController extends Controller
{
    var $strListaDql = "";
    var $codigoCliente = "";
    
    /**
     * @Route("/tur/consulta/servicios/detalles/recursos", name="brs_tur_consulta_servicios_detalles_recursos")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 44)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arCliente = new \Brasa\TurnoBundle\Entity\TurCliente();
        if ($form->isValid()) {  
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {                
                $arCliente = $em->getRepository('BrasaTurnoBundle:TurCliente')->findOneBy(array('nit' => $arrControles['txtNit']));
                if($arCliente) {
                    $this->codigoCliente = $arCliente->getCodigoClientePk();
                }
            }            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->lista();
                $this->generarExcel();
            }
        }
        $arServiciosDetallesRecursos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 100);
        return $this->render('BrasaTurnoBundle:Consultas/Servicio:detalleRecurso.html.twig', array(
            'arServiciosDetallesRecursos' => $arServiciosDetallesRecursos,
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }
            
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->listaConsultaDql(
                $this->codigoCliente);
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $form = $this->createFormBuilder()            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
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
        for($col = 'A'; $col !== 'AD'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }  
        $objPHPExcel->setActiveSheetIndex(0)              
                    ->setCellValue('A1', 'CLIENTE')
                    ->setCellValue('B1', 'PUESTO')
                    ->setCellValue('C1', 'SERVICIO')
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'POSICION')
                    ->setCellValue('F1', 'CLIENTE')
                    ->setCellValue('G1', 'SECTOR')             
                    ->setCellValue('H1', 'PUESTO')
                    ->setCellValue('I1', 'SERVICIO')
                    ->setCellValue('J1', 'MODALIDAD')
                    ->setCellValue('K1', 'PERIODO')
                    ->setCellValue('L1', 'PLANTILLA')
                    ->setCellValue('M1', 'DESDE')
                    ->setCellValue('N1', 'HASTA')
                    ->setCellValue('O1', 'CANT')
                    ->setCellValue('P1', 'CANT.R')
                    ->setCellValue('Q1', 'LU')
                    ->setCellValue('R1', 'MA')
                    ->setCellValue('S1', 'MI')
                    ->setCellValue('T1', 'JU')
                    ->setCellValue('U1', 'VI')
                    ->setCellValue('V1', 'SA')
                    ->setCellValue('W1', 'DO')
                    ->setCellValue('X1', 'FE')
                    ->setCellValue('Y1', 'H')
                    ->setCellValue('Z1', 'H.D')
                    ->setCellValue('AA1', 'H.N')
                    ->setCellValue('AB1', 'DIAS')
                    ->setCellValue('AC1', 'M');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arServiciosDetallesRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
        $arServiciosDetallesRecursos = $query->getResult();

        foreach ($arServiciosDetallesRecursos as $arServicioDetalleRecurso) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicioDetalleRecurso->getCodigoServicioDetalleRecursoPk())                    
                    ->setCellValue('B' . $i, $arServicioDetalleRecurso->getCodigoRecursoFk())
                    ->setCellValue('C' . $i, $arServicioDetalleRecurso->getCodigoServicioDetalleFk())
                    ->setCellValue('D' . $i, $arServicioDetalleRecurso->getRecursoRel()->getNombreCorto())                    
                    ->setCellValue('E' . $i, $arServicioDetalleRecurso->getPosicion())
                    ->setCellValue('F' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getServicioRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('G' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getServicioRel()->getSectorRel()->getNombre())                    
                    ->setCellValue('I' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getConceptoServicioRel()->getNombre())
                    ->setCellValue('J' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getModalidadServicioRel()->getNombre())
                    ->setCellValue('K' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPeriodoRel()->getNombre())
                    ->setCellValue('M' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getFechaDesde()->format('Y-m-d'))
                    ->setCellValue('N' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getFechaHasta()->format('Y-m-d'))
                    ->setCellValue('O' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getCantidad())
                    ->setCellValue('P' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getCantidadRecurso())
                    ->setCellValue('Q' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getLunes()))
                    ->setCellValue('R' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getMartes()))
                    ->setCellValue('S' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getMiercoles()))
                    ->setCellValue('T' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getJueves()))
                    ->setCellValue('U' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getViernes()))
                    ->setCellValue('V' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getSabado()))
                    ->setCellValue('W' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getDomingo()))
                    ->setCellValue('X' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getFestivo()))
                    ->setCellValue('Y' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getHoras())
                    ->setCellValue('Z' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getHorasDiurnas())
                    ->setCellValue('AA' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getHorasNocturnas())
                    ->setCellValue('AB' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getDias())
                    ->setCellValue('AC' . $i, $objFunciones->devuelveBoolean($arServicioDetalleRecurso->getServicioDetalleRel()->getMarca()));
            
            if($arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('H' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()->getNombre());
            }
            if($arServicioDetalleRecurso->getServicioDetalleRel()->getPlantillaRel()) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('L' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPlantillaRel()->getNombre());
            }
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('DetRecurso');     
        $objPHPExcel->createSheet(2)->setTitle('Detalle')
                    ->setCellValue('A1', 'CLIENTE')
                    ->setCellValue('B1', 'SERVICIO')
                    ->setCellValue('C1', 'PUESTO')                
                    ->setCellValue('D1', 'RECURSO')
                    ->setCellValue('E1', 'POSICION');
        $i = 2;
        foreach ($arServiciosDetallesRecursos as $arServicioDetalleRecurso) {   
            $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('A' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getServicioRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('B' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getConceptoServicioRel()->getNombre())
                    ->setCellValue('D' . $i, $arServicioDetalleRecurso->getRecursoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $arServicioDetalleRecurso->getPosicion());
            if($arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()) {
                $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('C' . $i, $arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()->getNombre());
            }
            $i++;
        }        
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);        
 
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="ServiciosDetalles.xlsx"');
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