<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoEstudioTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuEmpleadoEstudioTipo controller.
 *
 */
class EmpleadoEstudioTipoController extends Controller
{

    /**
     * @Route("/rhu/empleado/estudio/tipo/lista", name="brs_rhu_base_empleado_estudio_tipo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 38, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arTipoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoTipoEstudio) {
                        $arTipoEstudio = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo();
                        $arTipoEstudio = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo')->find($codigoTipoEstudio);
                        $em->remove($arTipoEstudio);
                    }
                    $em->flush();
                } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de estudio porque esta siendo utilizado', $this);
                    }    
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arTipoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo')->findAll();
        $arTipoEstudios = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoEstudioTipo:listar.html.twig', array(
                    'arTipoEstudios' => $arTipoEstudios,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/empleado/estudio/tipo/nuevo/{codigoTipoEstudio}", name="brs_rhu_base_empleado_estudio_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoTipoEstudio) {
        $em = $this->getDoctrine()->getManager();
        $arTipoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudioTipo();
        if ($codigoTipoEstudio != 0)
        {
            $arTipoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo')->find($codigoTipoEstudio);
        }    
        $form = $this->createForm(new RhuEmpleadoEstudioTipoType(), $arTipoEstudios);
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arTipoEstudios = $form->getData();
            $em->persist($arTipoEstudios);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_empleado_estudio_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/EmpleadoEstudioTipo:nuevo.html.twig', array(
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
                    ->setCellValue('A1', 'Código')
                    ->setCellValue('B1', 'Estudio');
        $i = 2;
        $arTipoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudioTipo')->findAll();

        foreach ($arTipoEstudios as $arTipoEstudio) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTipoEstudio->getEmpleadoEstudioTipoPk())
                    ->setCellValue('B' . $i, $arTipoEstudio->getNombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('TipoEstudios');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="TipoEstudios.xlsx"');
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
