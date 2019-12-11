<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoInformacionInternaTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * EmpleadoInformacionInternaTipo  Controller.
 *
 */
class EmpleadoInformacionInternaTipoController extends Controller
{

    /**
     * @Route("/rhu/base/empleado/informacion/interna/tipo/lista", name="brs_rhu_base_empleado_informacion_interna_tipo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 46, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arEmpleadoInformacionInternaTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoEmpleadoInformacionInternaTipo) {
                        $arEmpleadoInformacionInternaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
                        $arEmpleadoInformacionInternaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->find($codigoEmpleadoInformacionInternaTipo);
                        $em->remove($arEmpleadoInformacionInternaTipo);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de informacion interna porque esta siendo utilizado', $this);
                  }    
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arEmpleadoInformacionInternaTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->findAll();
        $arEmpleadoInformacionInternaTipos = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoInformacionInternaTipo:listar.html.twig', array(
                    'arEmpleadoInformacionInternaTipos' => $arEmpleadoInformacionInternaTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/empleado/informacion/interna/tipo/nuevo/{codigoInformacionInternaTipo}", name="brs_rhu_base_empleado_informacion_interna_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoInformacionInternaTipo) {
        $em = $this->getDoctrine()->getManager();
        $arEmpleadoInformacionInternaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
        if ($codigoInformacionInternaTipo != 0)
        {
            $arEmpleadoInformacionInternaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->find($codigoInformacionInternaTipo);
        }    
        $form = $this->createForm(new RhuEmpleadoInformacionInternaTipoType(), $arEmpleadoInformacionInternaTipo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arEmpleadoInformacionInternaTipo = $form->getData();
            $em->persist($arEmpleadoInformacionInternaTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_empleado_informacion_interna_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoInformacionInternaTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'INFORMACIÓN INTERNA TIPO')
                    ->setCellValue('C1', 'ACCION');
        $i = 2;
        $arEmpleadoInformacionInternaTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->findAll();

        foreach ($arEmpleadoInformacionInternaTipos as $arEmpleadoInformacionInternaTipo) {
            $accion = "DESBLOQUEADO";
            if ($arEmpleadoInformacionInternaTipo->getAccion() == 1){
                $accion = "BLOQUEADO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleadoInformacionInternaTipo->getCodigoEmpleadoInformacionInternaTipoPk())
                    ->setCellValue('B' . $i, $arEmpleadoInformacionInternaTipo->getNombre())
                    ->setCellValue('C' . $i, $accion);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('InformacionInternaTipo');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="InformacionInternaTipo.xlsx"');
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
