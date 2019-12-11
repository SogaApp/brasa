<?php

namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


class EmpleadoCentroCostoMesController extends Controller
{
    var $strDqlLista = "";
    var $intNumero = 0;
    /**
     * @Route("/rhu/consulta/empleado/centro/costo/mes", name="brs_rhu_consulta_empleado_centro_costo_mes")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        //if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 94)) {
        //    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        //}
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
                $this->generarExcel();
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form, $request);
                $this->listar();
            }            
        }
        $arEmpleadoCentroCosto = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Empleados:centroCostoMes.html.twig', array(
            'arEmpleadoCentroCosto' => $arEmpleadoCentroCosto,
            'form' => $form->createView()
            ));
    }

    private function listar() {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoCentroCosto')->listaDql(
                $session->get('filtroRhuAnio'),
                $session->get('filtroRhuMes') );
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $form = $this->createFormBuilder()
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('TxtAnio', 'text')
            ->add('TxtMes', 'text')
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $session->set('filtroRhuMes', $form->get('TxtMes')->getData());
        $session->set('filtroRhuAnio', $form->get('TxtAnio')->getData());
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        for($col = 'A'; $col !== 'K'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'NIT')
                    ->setCellValue('B1', 'ANIO')
                    ->setCellValue('C1', 'MES')
                    ->setCellValue('D1', 'PUESTO')
                    ->setCellValue('E1', 'C_COSTO');

        $i = 2;
        $dql   = $this->strDqlLista;
        $query = $em->createQuery($dql);
        $arEmpleadosCentroCosto = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoCentroCosto();
        $arEmpleadosCentroCosto = $query->getResult();
        foreach ($arEmpleadosCentroCosto as $arEmpleadoCentroCosto) {
            $nit = "";
            if($arEmpleadoCentroCosto->getCodigoEmpleadoFk()) {
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arEmpleadoCentroCosto->getCodigoEmpleadoFk());
                $nit = $arEmpleado->getNumeroIdentificacion() . "-" . $arEmpleado->getDigitoVerificacion();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $nit)
                    ->setCellValue('B' . $i, $arEmpleadoCentroCosto->getAnio())
                    ->setCellValue('C' . $i, $arEmpleadoCentroCosto->getMes())
                    ->setCellValue('D' . $i, $arEmpleadoCentroCosto->getCodigoPuestoFk())
                    ->setCellValue('E' . $i, $arEmpleadoCentroCosto->getCodigoCentroCostoFk());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('empleadoCentroCosto');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="empleadoCentroCosto.xlsx"');
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
