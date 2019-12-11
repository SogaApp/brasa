<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuZonaType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
/**
 * RhuZona controller.
 *
 */
class ZonaController extends Controller
{
    var $strDqlLista = "";     
    var $strNombre = "";
    
    /**
     * @Route("/rhu/base/zona/", name="brs_rhu_base_zona")
     */     
    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 72, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arZonas = new \Brasa\RecursoHumanoBundle\Entity\RhuZona();
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoZonaPk) {
                            $arZona = new \Brasa\RecursoHumanoBundle\Entity\RhuZona();
                            $arZona = $em->getRepository('BrasaRecursoHumanoBundle:RhuZona')->find($codigoZonaPk);
                            $em->remove($arZona);                        
                        }                    
                        $em->flush();                                            
                        return $this->redirect($this->generateUrl('brs_rhu_base_zona'));                        
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el zona porque esta siendo utilizado', $this);
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
                $objFormatoZona = new \Brasa\RecursoHumanoBundle\Formatos\FormatoZona();
                $objFormatoZona->Generar($this, $this->strDqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }    
        }
      
        $arZonas = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Base/Zona:listar.html.twig', array(
                    'arZonas' => $arZonas,
                    'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/rhu/base/zona/nuevo/{codigoZonaPk}", name="brs_rhu_base_zona_nuevo")
     */    
    public function nuevoAction($codigoZonaPk) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $arZona = new \Brasa\RecursoHumanoBundle\Entity\RhuZona();
        if ($codigoZonaPk != 0)
        {
            $arZona = $em->getRepository('BrasaRecursoHumanoBundle:RhuZona')->find($codigoZonaPk);
        }    
        $formZona = $this->createForm(new RhuZonaType(), $arZona);
        $formZona->handleRequest($request);
        if ($formZona->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arZona);
            $arZona = $formZona->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_zona'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Zona:nuevo.html.twig', array(
            'formZona' => $formZona->createView(),
        ));
    }
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuZona')->listaDQL(
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
        $arZonas = new \Brasa\RecursoHumanoBundle\Entity\RhuZona();
        $arZonas = $query->getResult();
        foreach ($arZonas as $arZonas) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arZonas->getCodigoZonaPk())
                            ->setCellValue('B' . $i, $arZonas->getNombre());
                    $i++;
                }

        $objPHPExcel->getActiveSheet()->setTitle('Zonas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Zonas.xlsx"');
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
