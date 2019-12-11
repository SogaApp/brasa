<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuBancoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

//use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
//use Doctrine\DBAL\Driver\PDOException;

/**
 * RhuBanco controller.
 *
 */
class BancoController extends Controller
{
    /**
     * @Route("/rhu/base/banco/listar", name="brs_rhu_base_banco_listar")
     */
    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 57, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder() //
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel'))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        
        $arBancos = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                try{
                    foreach ($arrSeleccionados AS $codigoBancoPk) {
                        $arBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
                        $arBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->find($codigoBancoPk);
                        $em->remove($arBanco);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_banco_listar'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el banco porque esta siendo utilizado', $this);
                  }     
            }
            
        if($form->get('BtnPdf')->isClicked()) {
                $objFormatoBanco = new \Brasa\RecursoHumanoBundle\Formatos\FormatoBanco();
                $objFormatoBanco->Generar($this);
        }    
        
        if($form->get('BtnExcel')->isClicked()) {
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
                            ->setCellValue('A1', 'Código')
                            ->setCellValue('B1', 'Nombre')
                            ->setCellValue('C1', 'Nit')
                            ->setCellValue('D1', 'Código General')
                            ->setCellValue('E1', 'Convenio Nomina')
                            ->setCellValue('F1', 'Teléfono')
                            ->setCellValue('G1', 'Dirección')
                            ->setCellValue('H1', 'Digitos Cuenta');

                $i = 2;
                $arBancos = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->findAll();
                
                foreach ($arBancos as $arBancos) {
                    if ($arBancos->getConvenioNomina() == 1){
                        $convenio = "SI";
                    } else {
                        $convenio = "NO";
                    }
                        
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arBancos->getCodigoBancoPk())
                            ->setCellValue('B' . $i, $arBancos->getNombre())
                            ->setCellValue('C' . $i, $arBancos->getNit())
                            ->setCellValue('D' . $i, $arBancos->getCodigoGeneral())
                            ->setCellValue('E' . $i, $convenio)
                            ->setCellValue('F' . $i, $arBancos->getTelefono())
                            ->setCellValue('G' . $i, $arBancos->getDireccion())
                            ->setCellValue('H' . $i, $arBancos->getNumeroDigitos());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Entidades_Bancarias');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Bancos.xlsx"');
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
        $arBancos = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->findAll();
        $arBancos = $paginator->paginate($query, $this->get('Request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Base/Banco:listar.html.twig', array(
                    'arBancos' => $arBancos,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/base/banco/nuevo/{codigoBancoPk}", name="brs_rhu_base_banco_nuevo")
     */
    public function nuevoAction(Request $request, $codigoBancoPk) {
        $em = $this->getDoctrine()->getManager();
        $arBanco = new \Brasa\RecursoHumanoBundle\Entity\RhuBanco();
        if ($codigoBancoPk != 0)
        {
            $arBanco = $em->getRepository('BrasaRecursoHumanoBundle:RhuBanco')->find($codigoBancoPk);
        }    
        $form = $this->createForm(RhuBancoType::class, $arBanco);      
        $form->handleRequest($request);
        if ($form->isValid())
        {
            // guardar la tarea en la base de datos
            $arBanco = $form->getData();
            $em->persist($arBanco);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_base_banco_listar'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Banco:nuevo.html.twig', array(
            'formBanco' => $form->createView(),
        ));
    }
    
}
