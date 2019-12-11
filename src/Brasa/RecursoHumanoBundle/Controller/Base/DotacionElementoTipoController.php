<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuDotacionElementoTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuDotacionElementoTipo controller.
 *
 */
class DotacionElementoTipoController extends Controller
{
    /**
     * @Route("/rhu/base/dotacion/elemento/tipo/lista", name="brs_rhu_base_dotacion_elemento_tipo_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 95, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arDotacionElementosTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElementoTipo();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoDotacionElementoTipoPk) {
                        $arDotacionElementosTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElementoTipo();
                        $arDotacionElementosTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElementoTipo')->find($codigoDotacionElementoTipoPk);
                        $em->remove($arDotacionElementosTipos);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_elemento_tipo_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el elemento tipo porque esta siendo utilizado', $this);
                  }    
            } 
        
        if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('B1', 'NOMBRE');

                $i = 2;
                $arDotacionElementosTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElementoTipo')->findAll();
                
                foreach ($arDotacionElementosTipos as $arDotacionElementosTipos) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arDotacionElementosTipos->getCodigoDotacionElementoTipoPk())
                            ->setCellValue('B' . $i, $arDotacionElementosTipos->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Dotacion Elementos Tipos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="DotacionElementosTipos.xlsx"');
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
        $arDotacionElementosTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElementoTipo();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElementoTipo')->findAll();
        $arDotacionElementosTipos = $paginator->paginate($query, $this->get('request')->query->get('page', 1),50);

        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionElementosTipos:listar.html.twig', array(
                    'arDotacionElementosTipos' => $arDotacionElementosTipos,
                    'form'=> $form->createView()
        ));
    }
    
    /**
     * @Route("/rhu/base/dotacion/elemento/tipo/nuevo/{codigoDotacionElementoTipo}", name="brs_rhu_base_dotacion_elemento_tipo_nuevo")
     */
    public function nuevoAction($codigoDotacionElementoTipo) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arDotacionElementoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElementoTipo();
        if ($codigoDotacionElementoTipo != 0)
        {
            $arDotacionElementoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacionElementoTipo')->find($codigoDotacionElementoTipo);
        }    
        $formDotacionElementoTipo = $this->createForm(new RhuDotacionElementoTipoType(), $arDotacionElementoTipo);
        $formDotacionElementoTipo->handleRequest($request);
        if ($formDotacionElementoTipo->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arDotacionElementoTipo);
            $arDotacionElementoTipo = $formDotacionElementoTipo->getData();
            $em->flush();
            if($formDotacionElementoTipo->get('guardarynuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_elemento_tipo_nuevo', array('codigoDotacionElementoTipo' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_base_dotacion_elemento_tipo_lista'));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/DotacionElementosTipos:nuevo.html.twig', array(
            'formDotacionElementoTipo' => $formDotacionElementoTipo->createView(),
        ));
    }
    
}