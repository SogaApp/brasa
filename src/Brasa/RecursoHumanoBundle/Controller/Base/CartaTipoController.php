<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuCartaTipoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * RhuCartaTipo controller.
 *
 */
class CartaTipoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/base/carta/tipo/lista", name="brs_rhu_base_carta_tipo_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 45, 1)) {
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
                    foreach ($arrSeleccionados AS $codigoCartaTipo) {
                        $arCartaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo();
                        $arCartaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCartaTipo')->find($codigoCartaTipo);
                        $em->remove($arCartaTipo);         
                    }
                    $em->flush(); 
                    return $this->redirect($this->generateUrl('brs_rhu_base_carta_tipo_lista'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar la carta tipo porque esta siendo utilizado', $this);
                  }    
            }                        
        }
        
        $arCartaTipos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaRecursoHumanoBundle:Base/CartaTipo:listar.html.twig', array(
                    'arCartaTipos' => $arCartaTipos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/carta/tipo/nuevo/{codigoCartaTipo}", name="brs_rhu_base_carta_tipo_nuevo")
     */
    public function nuevoAction(Request $request, $codigoCartaTipo) {
        $em = $this->getDoctrine()->getManager();
        $arCartaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuCartaTipo();
        if ($codigoCartaTipo != 0) {
            $arCartaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuCartaTipo')->find($codigoCartaTipo);
        }    
        $form = $this->createForm(RhuCartaTipoType::class, $arCartaTipo); 
        $form->handleRequest($request);
        if ($form->isValid()) {                        
            $arCartaTipo = $form->getData();
            $em->persist($arCartaTipo);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_carta_tipo_lista'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/CartaTipo:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();        
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCartaTipo')->listaDql();         
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
        $arCartaTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCartaTipo')->findAll();

        foreach ($arCartaTipos as $arCartaTipos) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCartaTipos->getcodigoCartaTipoPk())
                    ->setCellValue('B' . $i, $arCartaTipos->getnombre());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Carta_Tipos');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Carta_Tipos.xlsx"');
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
