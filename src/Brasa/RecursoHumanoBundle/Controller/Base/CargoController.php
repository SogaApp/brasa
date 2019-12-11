<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCargoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
/**
 * RhuCargo controller.
 *
 */
class CargoController extends Controller
{
    var $strDqlLista = "";     
    var $strNombre = "";
    
    /**
     * @Route("/rhu/base/cargo/", name="brs_rhu_base_cargo")
     */     
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 54, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
        if($form->isValid()) {
            if($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoCargoPk) {
                            $arCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
                            $arCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->find($codigoCargoPk);
                            $em->remove($arCargo);                        
                        }                    
                        $em->flush();                                            
                        return $this->redirect($this->generateUrl('brs_rhu_base_cargo'));                        
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $objMensaje->Mensaje('error', 'No se puede eliminar el cargo porque esta siendo utilizado', $this);
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
                $objFormatoCargo = new \Brasa\RecursoHumanoBundle\Formatos\FormatoCargo();
                $objFormatoCargo->Generar($this, $this->strDqlLista);
            }*/
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }    
        }
      
        $arCargos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Base/Cargo:listar.html.twig', array(
                    'arCargos' => $arCargos,
                    'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/rhu/base/cargo/nuevo/{codigoCargoPk}", name="brs_rhu_base_cargo_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCargoPk) {
        $em = $this->getDoctrine()->getManager();
        $arCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
        if ($codigoCargoPk != 0)
        {
            $arCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->find($codigoCargoPk);
        }    
        $form = $this->createForm(RhuCargoType::class, $arCargo); 
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $em->persist($arCargo);
            $arCargo = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_cargo'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Cargo:nuevo.html.twig', array(
            'formCargo' => $form->createView(),
        ));
    }
    
    private function listar() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCargo')->listaDQL(
                $this->strNombre                   
                ); 
    }       
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new Session;     
        $form = $this->createFormBuilder()                                    
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => "", 'required' => false))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            //->add('BtnPdf', 'submit', array('label'  => 'Pdf',))    
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))                                            
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
        for($col = 'A'; $col !== 'D'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        } 
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CÓDIGO')
            ->setCellValue('B1', 'NOMBRE')
            ->setCellValue('C1', 'SUPERVIGILANCIA')    ;
        $i = 2;
        $query = $em->createQuery($this->strDqlLista);
        $arCargos = new \Brasa\RecursoHumanoBundle\Entity\RhuCargo();
        $arCargos = $query->getResult();
        foreach ($arCargos as $arCargos) {
            if ($arCargos->getCodigoCargoSupervigilanciaFk() != null){                
                $supervigilancia = $arCargos->getCargoSupervigilanciaRel()->getNombre();
            } else {
                $supervigilancia = "";
            }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCargos->getCodigoCargoPk())
                            ->setCellValue('B' . $i, $arCargos->getNombre())
                            ->setCellValue('C' . $i, $supervigilancia);
                    $i++;
                }

        $objPHPExcel->getActiveSheet()->setTitle('Cargos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cargos.xlsx"');
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
