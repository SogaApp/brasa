<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSaludTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuSaludTipo controller.
 *
 */
class SaludTipoController extends Controller
{
    /**
     * @Route("/rhu/base/salud/tipo/lista", name="brs_rhu_base_salud_tipo_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 65, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arSaludTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoSaludTipo) {
                        $arSaludTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud();
                        $arSaludTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoSalud')->find($codigoSaludTipo);
                        $em->remove($arSaludTipo);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_salud_tipo_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de salud porque esta siendo utilizado', $this);
                  }    
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arSaludTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoSalud')->findAll();
        $arSaludTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/SaludTipo:listar.html.twig', array(
                    'arSaludTipos' => $arSaludTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/salud/tipo/nuevo/{codigoSaludTipo}", name="brs_rhu_base_salud_tipo_nuevo")
     */
    public function nuevoAction($codigoSaludTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSaludTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuTipoSalud();
        if ($codigoSaludTipo != 0)
        {
            $arSaludTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoSalud')->find($codigoSaludTipo);
        }    
        $form = $this->createForm(new RhuSaludTipoType(), $arSaludTipo);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arSaludTipo = $form->getData();
            $em->persist($arSaludTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_salud_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/SaludTipo:nuevo.html.twig', array(
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
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CODIGO')
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'PORCENTAJE EMPLEADO')
                    ->setCellValue('D1', 'PORCENTAJE EMPLEADOR')
                    ->setCellValue('E1', 'CONCEPTO');
        $i = 2;
        $arSaludTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuTipoSalud')->findAll();

        foreach ($arSaludTipos as $arSaludTipo) {
            if ($arSaludTipo->getCodigoPagoConceptoFk() == null){
                $pagoConcepto = "";
            } else {
                $pagoConcepto = $arSaludTipo->getPagoConceptoRel()->getNombre();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arSaludTipo->getCodigoTipoSaludPk())
                    ->setCellValue('B' . $i, $arSaludTipo->getNombre())
                    ->setCellValue('C' . $i, $arSaludTipo->getPorcentajeEmpleado())
                    ->setCellValue('D' . $i, $arSaludTipo->getPorcentajeEmpleador())
                    ->setCellValue('E' . $i, $pagoConcepto);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('SaludTipos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SaludTipos.xlsx"');
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
