<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuContratoTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuContratosTipo controller.
 *
 */
class ContratosTipoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/base/contrato/tipo/lista", name="brs_rhu_base_contrato_tipo_lista")
     */ 
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 43, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoContratoTipo) {
                        $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo();
                        $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->find($codigoContratoTipo);
                        $em->remove($arContratoTipo);                   
                    }
                    $em->flush(); 
                    return $this->redirect($this->generateUrl('brs_rhu_base_contrato_tipo_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el tipo de contrato porque esta siendo utilizado', $this);
                  }    
            }                        
        }
        
        $arContratosTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoTipo:listar.html.twig', array(
                    'arContratosTipos' => $arContratosTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/contrato/tipo/nuevo/{codigoContratoTipo}", name="brs_rhu_base_contrato_tipo_nuevo")
     */ 
    public function nuevoAction(Request $request, $codigoContratoTipo) {
        $em = $this->getDoctrine()->getManager();
        $arContratoTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo();
        if ($codigoContratoTipo != 0) {
            $arContratoTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->find($codigoContratoTipo);
        }    
        $form = $this->createForm(RhuContratoTipoType::class, $arContratoTipo); 
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arContratoTipo = $form->getData();
            $em->persist($arContratoTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_contrato_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/ContratoTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->listaDql();         
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
                    ->setCellValue('A1', 'Codigo')
                    ->setCellValue('B1', 'Nombre');
        $i = 2;
        $arContratoTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoTipo')->findAll();

        foreach ($arContratoTipos as $arContratoTipos) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arContratoTipos->getcodigoContratoTipoPk())
                    ->setCellValue('B' . $i, $arContratoTipos->getnombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Contrato_tipos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Contratos_Tipos.xlsx"');
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
