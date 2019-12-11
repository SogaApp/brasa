<?php

namespace Brasa\TurnoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ServicioInconsistenciaController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/servicio/inconsistencias", name="brs_tur_utilidad_servicio_inconsistencias")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 87)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {  
                set_time_limit(0);
                ini_set("memory_limit", -1);
                $strSql = "DELETE FROM tur_servicio_inconsistencia WHERE 1";           
                $em->getConnection()->executeQuery($strSql);                
                $dateFecha = $form->get('fecha')->getData();
                $strAnio = $dateFecha->format('Y');
                $strMes = $dateFecha->format('m'); 
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('estadoActivo' => 1));                                
                $this->recursosInactivosAsignacion();                
                $this->recursosSinAsignacion($arRecursos);                
                set_time_limit(60);
                return $this->redirect($this->generateUrl('brs_tur_utilidad_servicio_inconsistencias')); 
            } 
            if($form->get('BtnEliminar')->isClicked()) {            
                $strSql = "DELETE FROM tur_servicio_inconsistencia WHERE 1";           
                $em->getConnection()->executeQuery($strSql);
                return $this->redirect($this->generateUrl('brs_tur_utilidad_servicio_inconsistencias')); 
            }
            if($form->get('BtnExportar')->isClicked()) {
                $this->generarExcel();
            }
        }                 
        $dql = $em->getRepository('BrasaTurnoBundle:TurServicioInconsistencia')->listaDql();
        $arServicioInconsistencias = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Utilidades/Servicios:inconsistencias.html.twig', array(            
            'arServicioInconsistencias' => $arServicioInconsistencias,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                        
            ->add('fecha', DateType::class, array('data' => new \DateTime('now'), 'format' => 'yyyyMMdd'))                            
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar'))    
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))    
            ->add('BtnExportar', SubmitType::class, array('label'  => 'Exportar'))    
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
        for($col = 'A'; $col !== 'E'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);                
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'INCONSISTENCIA')
                    ->setCellValue('C1', 'DETALLE')
                    ->setCellValue('D1', 'IDENTIFICACION')
                    ->setCellValue('E1', 'GRUPO')
                    ->setCellValue('F1', 'ZONA')
                    ->setCellValue('G1', 'DIA');

        $i = 2;
        $dql = $em->getRepository('BrasaTurnoBundle:TurServicioInconsistencia')->listaDql();
        $query = $em->createQuery($dql);
        $arServicioInconsistencias = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
        $arServicioInconsistencias = $query->getResult();
        foreach ($arServicioInconsistencias as $arServicioInconsistencia) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arServicioInconsistencia->getCodigoServicioInconsistenciaPk())
                    ->setCellValue('B' . $i, $arServicioInconsistencia->getInconsistencia())
                    ->setCellValue('C' . $i, $arServicioInconsistencia->getDetalle())
                    ->setCellValue('D' . $i, $arServicioInconsistencia->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arServicioInconsistencia->getCodigoRecursoGrupoFk())
                    ->setCellValue('F' . $i, $arServicioInconsistencia->getZona())
                    ->setCellValue('G' . $i, $arServicioInconsistencia->getDia());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inconsistencias');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="TurnoServicioInconsistencias.xlsx"');
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
    
    private function recursosInactivosAsignacion() {
        $em = $this->getDoctrine()->getManager();
        $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecursos =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('estadoActivo' => 0));                                        
        foreach ($arRecursos as $arRecurso) {            
            $arServicioDetalleRecursos = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
            $arServicioDetalleRecursos =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoRecursoFk' => $arRecurso->getCodigoRecursoPk()));                
            foreach ($arServicioDetalleRecursos as $arServicioDetalleRecurso) {
                $arServicioInconsistencia = new \Brasa\TurnoBundle\Entity\TurServicioInconsistencia();
                $arServicioInconsistencia->setInconsistencia('Inactivo CON asignacion en servicios permanentes');
                $arServicioInconsistencia->setDetalle("El recurso " . $arRecurso->getCodigoRecursoPk() . " " . $arRecurso->getNombreCorto() . " no tiene un contrato vigente y esta asignado a un servicio permanente de " . $arServicioDetalleRecurso->getServicioDetalleRel()->getServicioRel()->getClienteRel()->getNombreCorto() . " en el puesto " . $arServicioDetalleRecurso->getServicioDetalleRel()->getPuestoRel()->getNombre());
                $arServicioInconsistencia->setCodigoRecursoFk($arRecurso->getCodigoRecursoPk());
                $arServicioInconsistencia->setNumeroIdentificacion($arRecurso->getNumeroIdentificacion());
                $em->persist($arServicioInconsistencia);                 
            }
                //$arServicioDetalleRecursoAct = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
                //$arServicioDetalleRecursoAct =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->find($arServicioDetalleRecurso->getCodigoServicioDetalleRecursoPk());                                                    
        }
        $em->flush();    
        return TRUE;
    }     
    
    private function recursosSinAsignacion($arRecursos) {
        $em = $this->getDoctrine()->getManager();
        foreach ($arRecursos as $arRecurso) {
            $arServicioDetalleRecurso = new \Brasa\TurnoBundle\Entity\TurServicioDetalleRecurso();
            $arServicioDetalleRecurso =  $em->getRepository('BrasaTurnoBundle:TurServicioDetalleRecurso')->findBy(array('codigoRecursoFk' => $arRecurso->getCodigoRecursoPk()));                
            if(count($arServicioDetalleRecurso) <= 0) {
                $arServicioInconsistencia = new \Brasa\TurnoBundle\Entity\TurServicioInconsistencia();
                $arServicioInconsistencia->setInconsistencia('Recurso sin asignacion en servicios permanentes');
                $arServicioInconsistencia->setDetalle("El recurso " . $arRecurso->getCodigoRecursoPk() . " " . $arRecurso->getNombreCorto() . " no tiene asignacion en ningun puesto y esta activo");
                $arServicioInconsistencia->setCodigoRecursoFk($arRecurso->getCodigoRecursoPk());
                $arServicioInconsistencia->setNumeroIdentificacion($arRecurso->getNumeroIdentificacion());
                $em->persist($arServicioInconsistencia);                         
            }
        }
        $em->flush();    
        return TRUE;
    }    

}
