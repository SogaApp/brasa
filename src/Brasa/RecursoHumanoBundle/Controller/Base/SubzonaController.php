<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSubzonaType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
/**
 * RhuSubzona controller.
 *
 */
class SubzonaController extends Controller
{
    var $strDqlLista = "";     
    var $strNombre = "";
    
    /**
     * @Route("/rhu/base/subzona/", name="brs_rhu_base_subzona")
     */     
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 73, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arSubZonas = new \Brasa\RecursoHumanoBundle\Entity\RhuSubZona();
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoSubZonaPk) {
                            $arSubZona = new \Brasa\RecursoHumanoBundle\Entity\RhuSubZona();
                            $arSubZona = $em->getRepository('BrasaRecursoHumanoBundle:RhuSubZona')->find($codigoSubZonaPk);
                            $em->remove($arSubZona);                        
                        }                    
                        $em->flush();                                            
                        return $this->redirect($this->generateUrl('brs_rhu_base_subzona'));                        
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el subzona porque esta siendo utilizado', $this);
                    }

                }                
            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            /*if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoSubZona = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSubZona();
                $objFormatoSubZona->Generar($this, $this->strDqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }    
        }
      
        $arSubZonas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Base/Subzona:listar.html.twig', array(
                    'arSubZonas' => $arSubZonas,
                    'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/rhu/base/subzona/nuevo/{codigoSubZonaPk}", name="brs_rhu_base_subzona_nuevo")
     */    
    public function nuevoAction($codigoSubZonaPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arSubZona = new \Brasa\RecursoHumanoBundle\Entity\RhuSubzona();
        if ($codigoSubZonaPk != 0)
        {
            $arSubZona = $em->getRepository('BrasaRecursoHumanoBundle:RhuSubzona')->find($codigoSubZonaPk);
        }    
        $formSubZona = $this->createForm(new RhuSubzonaType(), $arSubZona);
        $formSubZona->handleRequest($request);
        if ($formSubZona->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arSubZona);
            $arSubZona = $formSubZona->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_subzona'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Subzona:nuevo.html.twig', array(
            'formSubZona' => $formSubZona->createView(),
        ));
    }
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSubzona')->listaDQL(
                $this->strNombre                   
                ); 
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $form = $this->createFormBuilder()                                    
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => "", 'required' => false))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            //->add('BtnPdf', 'submit', array('label'  => 'Pdf',))    
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))                                            
            ->getForm();        
        return $form;
    }           

    private function filtrarLista($form) {
        
        $this->strNombre = $form->get('TxtNombre')->getData();
    }
    
    private function generarExcel() {
        $em = $this->getDoctrine()->getManager();
        ob_clean();
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
        $query = $em->createQuery($this->strDqlLista);
        $arSubZonas = new \Brasa\RecursoHumanoBundle\Entity\RhuSubzona();
        $arSubZonas = $query->getResult();
        foreach ($arSubZonas as $arSubZonas) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSubZonas->getCodigoSubZonaPk())
                            ->setCellValue('B' . $i, $arSubZonas->getNombre());
                    $i++;
                }

        $objPHPExcel->getActiveSheet()->setTitle('SubZonas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="SubZonas.xlsx"');
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
