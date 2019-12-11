<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class AspiranteInconsistenciasController extends Controller
{
    var $strSqlLista = "";    
    /**
     * @Route("/rhu/consultas/aspirantes/inconsistencias", name="brs_rhu_consultas_aspirantes_inconsistencias")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 37)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = $this->getRequest()->getSession();
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
        }
        $arAspirantes = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/AspirantesInconsistencia:lista.html.twig', array(
            'arAspirantes' => $arAspirantes,
            'form' => $form->createView()
            ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->aspirantesInconsistenciaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroIdentificacion'),
                ""
                );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
    }    

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'FECHA')
                    ->setCellValue('C1', 'CIUDAD')
                    ->setCellValue('D1', 'TIPO IDENTIFICACION')
                    ->setCellValue('E1', 'IDENTIFICACION')
                    ->setCellValue('F1', 'CIUDAD NACIMIENTO')
                    ->setCellValue('G1', 'FECHA NACIMIENTO')
                    ->setCellValue('H1', 'CIUDAD EXPEDICION')
                    ->setCellValue('I1', 'RH')
                    ->setCellValue('J1', 'NOMBRE')
                    ->setCellValue('K1', 'TELEFONO')
                    ->setCellValue('L1', 'CELULAR')
                    ->setCellValue('M1', 'DIRECCION')
                    ->setCellValue('N1', 'BARRIO')
                    ->setCellValue('O1', 'ESTADO CIVIL')
                    ->setCellValue('P1', 'SEXO')
                    ->setCellValue('Q1', 'CORREO')
                    ->setCellValue('R1', 'DISPONIBILIDAD')
                    ->setCellValue('S1', 'INCONSISTENCIA')
                    ->setCellValue('T1', 'COMENTARIOS');

        $i = 2;
                
        $query = $em->createQuery($this->strSqlLista);
        $arAspirantes = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        $arAspirantes = $query->getResult();
        foreach ($arAspirantes as $arAspirantes) {
            
            $ciudad = "";
            if ($arAspirantes->getCodigoCiudadFk() <> null){
                $ciudad = $arAspirantes->getCiudadRel()->getNombre();
            }
            $ciudadNacimiento = "";
            if ($arAspirantes->getCodigoCiudadNacimientoFk() <> null){
                $ciudadNacimiento = $arAspirantes->getCiudadNacimientoRel()->getNombre();
            }
            $ciudadExpedicion = "";
            if ($arAspirantes->getCodigoCiudadNacimientoFk() <> null){
                $ciudadExpedicion = $arAspirantes->getCiudadExpedicionRel()->getNombre();
            }
            $estadoCivil = "";
            if ($arAspirantes->getCodigoEstadoCivilFk() <> null){
                $estadoCivil = $arAspirantes->getEstadoCivilRel()->getNombre();
            }
            $sexo = "";
            if ($arAspirantes->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            } else {
                $sexo = "FEMENINO";
            }
            $disponibilidad = "";
            if ($arAspirantes->getCodigoDisponibilidadFk() == "1"){
                $disponibilidad = "TIEMPO COMPLETO";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "2"){
                $disponibilidad = "MEDIO TIEMPO";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "3"){
                $disponibilidad = "POR HORAS";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "4"){
                $disponibilidad = "DESDE CASA";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "5"){
                $disponibilidad = "PRACTICAS";
            }
            if ($arAspirantes->getCodigoDisponibilidadFk() == "0"){
                $disponibilidad = "NO APLICA";
            }
            $inconsistencia = "NO";
            if ($arAspirantes->getBloqueado() == 1){
                $inconsistencia = "SI";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arAspirantes->getCodigoAspirantePk())
                    ->setCellValue('B' . $i, $arAspirantes->getFecha()->format('Y-m-d'))
                    ->setCellValue('C' . $i, $ciudad)
                    ->setCellValue('D' . $i, $arAspirantes->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('E' . $i, $arAspirantes->getNumeroIdentificacion())
                    ->setCellValue('F' . $i, $ciudadNacimiento)
                    ->setCellValue('G' . $i, $arAspirantes->getFechaNacimiento()->format('Y-m-d'))
                    ->setCellValue('H' . $i, $ciudadExpedicion)
                    ->setCellValue('I' . $i, $arAspirantes->getRhRel()->getTipo())
                    ->setCellValue('J' . $i, $arAspirantes->getNombreCorto())
                    ->setCellValue('K' . $i, $arAspirantes->getTelefono())
                    ->setCellValue('L' . $i, $arAspirantes->getCelular())
                    ->setCellValue('M' . $i, $arAspirantes->getDireccion())
                    ->setCellValue('N' . $i, $arAspirantes->getBarrio())
                    ->setCellValue('O' . $i, $estadoCivil)
                    ->setCellValue('P' . $i, $sexo)
                    ->setCellValue('Q' . $i, $arAspirantes->getCorreo())
                    ->setCellValue('R' . $i, $disponibilidad)
                    ->setCellValue('S' . $i, $inconsistencia)
                    ->setCellValue('T' . $i, $arAspirantes->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Aspirantes');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Aspirantes.xlsx"');
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
