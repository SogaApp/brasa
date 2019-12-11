<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCapacitacionTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * CapacitacionTipoController controller.
 *
 */
class CapacitacionTipoController extends Controller
{
    /**
     * @Route("/rhu/capacitacion/tipo/lista", name="brs_rhu_base_capacitacion_tipo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 42, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arCapacitacionTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                
            try{
                foreach ($arrSeleccionados AS $codigoCapacitacionTipo) {
                    $arCapacitacionTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionTipo();
                    $arCapacitacionTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionTipo')->find($codigoCapacitacionTipo);
                    $em->remove($arCapacitacionTipo);
                }
                $em->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de capacitacion porque esta siendo utilizado', $this);
              }
            }   
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }    
            
        }
        $arCapacitacionTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionTipo')->findAll();
        $arCapacitacionTipos = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/CapacitacionTipo:listar.html.twig', array(
                    'arCapacitacionTipos' => $arCapacitacionTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/capacitacion/tipo/nuevo/{codigoTipoCapacitacion}", name="brs_rhu_base_capacitacion_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoTipoCapacitacion) {
        $em = $this->getDoctrine()->getManager();
        $arCapacitacionTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacionTipo();
        if ($codigoTipoCapacitacion != 0)
        {
            $arCapacitacionTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionTipo')->find($codigoTipoCapacitacion);
        }    
        $form = $this->createForm(RhuCapacitacionTipoType::class, $arCapacitacionTipos);   
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arCapacitacionTipos = $form->getData();
            $em->persist($arCapacitacionTipos);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_capacitacion_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/CapacitacionTipo:nuevo.html.twig', array(
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
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'CAPACITACIÓN');
        $i = 2;
        $arCapacitacionTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCapacitacionTipo')->findAll();

        foreach ($arCapacitacionTipos as $arCapacitacionTipo) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCapacitacionTipo->getCodigoCapacitacionTipoPk())
                    ->setCellValue('B' . $i, $arCapacitacionTipo->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('TiposCapacitacion');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="TiposCapacitacion.xlsx"');
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
