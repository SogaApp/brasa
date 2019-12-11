<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Programacion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class InconsistenciaController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/programacion/inconsistencias", name="brs_tur_utilidad_programacion_inconsistencias")
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
                $strSql = "DELETE FROM tur_programacion_inconsistencia WHERE 1";           
                $em->getConnection()->executeQuery($strSql);                
                $dateFecha = $form->get('fecha')->getData();
                $strAnio = $dateFecha->format('Y');
                $strMes = $dateFecha->format('m'); 
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('estadoActivo' => 1));                
                $this->recursosTurnoDoble($strAnio, $strMes);
                $this->recursosSinProgramacionMes($arRecursos, $strAnio, $strMes);
                $this->recursosSinTurno($arRecursos, $strAnio, $strMes);
                set_time_limit(60);
                return $this->redirect($this->generateUrl('brs_tur_utilidad_programacion_inconsistencias')); 
            } 
            if($form->get('BtnEliminar')->isClicked()) {            
                $strSql = "DELETE FROM tur_programacion_inconsistencia WHERE 1";           
                $em->getConnection()->executeQuery($strSql);
                return $this->redirect($this->generateUrl('brs_tur_utilidad_programacion_inconsistencias')); 
            }
            if($form->get('BtnExportar')->isClicked()) {
                $this->generarExcel();
            }
        }                 
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        $arProgramacionInconsistencias = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:inconsistencias.html.twig', array(            
            'arProgramacionInconsistencias' => $arProgramacionInconsistencias,
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
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionInconsistencia')->listaDql();
        $query = $em->createQuery($dql);
        $arProgramacionInconsistencias = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
        $arProgramacionInconsistencias = $query->getResult();
        foreach ($arProgramacionInconsistencias as $arProgramacionInconsistencia) {   
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arProgramacionInconsistencia->getCodigoProgramacionInconsistenciaPk())
                    ->setCellValue('B' . $i, $arProgramacionInconsistencia->getInconsistencia())
                    ->setCellValue('C' . $i, $arProgramacionInconsistencia->getDetalle())
                    ->setCellValue('D' . $i, $arProgramacionInconsistencia->getNumeroIdentificacion())
                    ->setCellValue('E' . $i, $arProgramacionInconsistencia->getCodigoRecursoGrupoFk())
                    ->setCellValue('F' . $i, $arProgramacionInconsistencia->getZona())
                    ->setCellValue('G' . $i, $arProgramacionInconsistencia->getDia());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inconsistencias');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="TurnoProgramacionInconsistencias.xlsx"');
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
    
    private function recursosSinProgramacionMes($arRecursos, $strAnio, $strMes) {
        $em = $this->getDoctrine()->getManager();
        foreach ($arRecursos as $arRecurso) {
            $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
            $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('codigoRecursoFk' => $arRecurso->getCodigoRecursoPk(), 'anio' => $strAnio, 'mes' => $strMes));                
            if(count($arProgramacionDetalle) <= 0) {
                $arProgramacionInconsistencia = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
                $arProgramacionInconsistencia->setInconsistencia('Recurso sin programacion en el mes');
                $arProgramacionInconsistencia->setDetalle("El recurso " . $arRecurso->getCodigoRecursoPk() . " " . $arRecurso->getNombreCorto() . " no registra programaciones para el mes");
                $arProgramacionInconsistencia->setMes($strMes);
                $arProgramacionInconsistencia->setAnio($strAnio);
                $arProgramacionInconsistencia->setCodigoRecursoFk($arRecurso->getCodigoRecursoPk());
                $arProgramacionInconsistencia->setNumeroIdentificacion($arRecurso->getNumeroIdentificacion());
                $em->persist($arProgramacionInconsistencia);                         
            }
        }
        $em->flush();    
        return TRUE;
    }
    
    private function recursosSinTurno($arRecursos, $strAnio, $strMes) {
        $em = $this->getDoctrine()->getManager();
        $strUltimoDiaMes = date("d",(mktime(0,0,0,$strMes+1,1,$strAnio)-1));
        //Recursos sin turno en programacion
        foreach ($arRecursos as $arRecurso) {
            for($i = 1; $i <= $strUltimoDiaMes; $i++) {
                $codigoRecurso = $arRecurso->getCodigoRecursoPk();
                $strSql = "SELECT
                            codigo_recurso_fk AS codigoRecursoFk, 
                            codigo_empleado_fk as codigoEmpleadoFk,
                            tur_recurso.nombre_corto AS nombreCorto,
                            tur_recurso.numero_identificacion AS numeroIdentificacion,
                            tur_recurso.codigo_recurso_grupo_fk AS recursoGrupo,
                            COUNT(dia_$i) AS numero
                            FROM
                            tur_programacion_detalle
                            LEFT JOIN tur_recurso ON tur_programacion_detalle.codigo_recurso_fk = tur_recurso.codigo_recurso_pk                                
                            WHERE
                            anio = $strAnio AND mes = $strMes AND codigo_recurso_fk = $codigoRecurso
                            GROUP BY
                            codigo_recurso_fk";   
                $connection = $em->getConnection();
                $statement = $connection->prepare($strSql);        
                $statement->execute();
                $results = $statement->fetchAll();
                if(count($results) > 0) {
                    foreach ($results as $registro) {
                        if($registro['numero'] <= 0) {
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($registro['codigoEmpleadoFk']);
                            $arProgramacionInconsistencia = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
                            $arProgramacionInconsistencia->setInconsistencia('Sin turno asignado');
                            $arProgramacionInconsistencia->setDetalle("Recurso " . $registro['codigoRecursoFk'] . " " .
                            "Identificacion: " . $registro['numeroIdentificacion'] . " " .
                            $registro['nombreCorto'] . " dia " . $i);                            
                            $arProgramacionInconsistencia->setNumeroIdentificacion($registro['numeroIdentificacion']);
                            $arProgramacionInconsistencia->setDia($i);
                            $arProgramacionInconsistencia->setMes($strMes);
                            $arProgramacionInconsistencia->setAnio($strAnio);
                            $arProgramacionInconsistencia->setCodigoRecursoFk($registro['codigoRecursoFk']);
                            $arProgramacionInconsistencia->setCodigoRecursoGrupoFk($registro['recursoGrupo']);
                            if($arEmpleado->getCodigoZonaFk()) {
                                $arProgramacionInconsistencia->setZona($arEmpleado->getZonaRel()->getNombre());
                            }                            
                            $em->persist($arProgramacionInconsistencia);                                
                        }
                    }                        
                }                         
            }                    
        }
        $em->flush();               
        return TRUE;
    }
    
    private function recursosTurnoDoble($strAnio, $strMes) {
        $em = $this->getDoctrine()->getManager();                               
        //Verificar turnos dobles
        for($i = 1; $i <= 31; $i++) {
            $strSql = "SELECT
                        codigo_recurso_fk as codigoRecursoFk,
                        codigo_empleado_fk as codigoEmpleadoFk,
                        tur_recurso.nombre_corto as nombreCorto,
                        tur_recurso.numero_identificacion as numeroIdentificacion,
                        tur_recurso.codigo_recurso_grupo_fk AS recursoGrupo,
                        COUNT(dia_$i) AS numero
                        FROM
                        tur_programacion_detalle
                        LEFT JOIN tur_recurso ON tur_programacion_detalle.codigo_recurso_fk = tur_recurso.codigo_recurso_pk 
                        LEFT JOIN tur_turno ON tur_programacion_detalle.dia_$i = tur_turno.codigo_turno_pk 
                        WHERE
                        dia_$i IS NOT NULL AND anio = $strAnio AND mes = $strMes AND tur_turno.complementario = 0 
                        GROUP BY
                        codigo_recurso_fk"; 
            $connection = $em->getConnection();
            $statement = $connection->prepare($strSql);        
            $statement->execute();
            $results = $statement->fetchAll();
            if(count($results) > 0) {
                foreach ($results as $registro) {
                    if($registro['numero'] > 1) {
                        //$arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        if($registro['codigoEmpleadoFk'] != "") {
                            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($registro['codigoEmpleadoFk']);
                            $arProgramacionInconsistencia = new \Brasa\TurnoBundle\Entity\TurProgramacionInconsistencia();
                            $arProgramacionInconsistencia->setInconsistencia('Asignacion doble de turno');
                            $arProgramacionInconsistencia->setDetalle("Recurso " . $registro['codigoRecursoFk'] . " " . 
                                    $registro['nombreCorto'] . " dia " . $i);
                            $arProgramacionInconsistencia->setDia($i);
                            $arProgramacionInconsistencia->setMes($strMes);
                            $arProgramacionInconsistencia->setAnio($strAnio);
                            $arProgramacionInconsistencia->setCodigoRecursoFk($registro['codigoRecursoFk']);
                            $arProgramacionInconsistencia->setCodigoRecursoGrupoFk($registro['recursoGrupo']);
                            $arProgramacionInconsistencia->setNumeroIdentificacion($registro['numeroIdentificacion']);
                            if($arEmpleado->getCodigoZonaFk()) {
                                $arProgramacionInconsistencia->setZona($arEmpleado->getZonaRel()->getNombre());
                            }
                            $em->persist($arProgramacionInconsistencia);                              
                        }
                              
                    }
                }                        
            }                        
        }
        $em->flush();        
        return TRUE;
    }

}
