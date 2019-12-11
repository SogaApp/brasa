<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuSedeType;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class SedeController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/base/sede/listar", name="brs_rhu_base_sede_listar")
     */ 
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 97, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $session = $this->getRequest()->getSession();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if($form->get('BtnFiltrar')->isClicked()) {                
                $this->filtrarLista($form);
                $this->listar();
            }
            
            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoSede = new \Brasa\RecursoHumanoBundle\Formatos\FormatoSede();
                $objFormatoSede->Generar($this, $this->strSqlLista);
                
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
                            ->setCellValue('B1', 'Centro_costo')
                            ->setCellValue('C1', 'Nombre');

                $i = 2;
                $query = $em->createQuery($this->strSqlLista);
                $arSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuSede();
                $arSedes = $query->getResult();
                foreach ($arSedes as $arSede) {
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arSede->getCodigoSedePk())
                            ->setCellValue('B' . $i, $arSede->getCentroCostoRel()->getNombre())
                            ->setCellValue('C' . $i, $arSede->getNombre());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Sedes');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Sedes.xlsx"');
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

            if ($form->get('BtnEliminar')->isClicked()) {    
                if(count($arrSeleccionados) > 0) {
                    try{
                        foreach ($arrSeleccionados AS $codigoSedePk) {
                            $arSedes = new \Brasa\RecursoHumanoBundle\Entity\RhuSede();
                            $arSedes = $em->getRepository('BrasaRecursoHumanoBundle:RhuSede')->find($codigoSedePk);
                            $em->remove($arSedes);
                        }
                        $em->flush();
                    } catch (ForeignKeyConstraintViolationException $e) { 
                        $objMensaje->Mensaje('error', 'No se puede eliminar la sede porque esta siendo utilizado', $this);
                      }    
                }
                $this->filtrarLista($form);
                $this->listar();
            }
        }         
        $arSedes = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/Sede:lista.html.twig', array(
            'arSedes' => $arSedes,
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/rhu/base/sede/nuevo/{codigoSedePk}", name="brs_rhu_base_sede_nuevo")
     */ 
    public function nuevoAction($codigoSedePk) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arSede = new \Brasa\RecursoHumanoBundle\Entity\RhuSede();
        if ($codigoSedePk != 0)
        {
            $arSede = $em->getRepository('BrasaRecursoHumanoBundle:RhuSede')->find($codigoSedePk);
        }
        $form = $this->createForm(new RhuSedeType(), $arSede);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arSede = $form->getData();
            $em->persist($arSede);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_sede_nuevo', array('codigoSedePk' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_base_sede_listar'));
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Sede:nuevo.html.twig', array(
            'arSede' => $arSede,
            'form' => $form->createView()));
    }   
    
    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();        
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')                                        
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,  
                'empty_data' => "",
                'empty_value' => "TODOS",    
                'data' => ""
            );  
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));                                    
        }
        $form = $this->createFormBuilder()                        
            ->add('centroCostoRel', 'entity', $arrayPropiedades)                                           
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))
            ->getForm();        
        return $form;
    }  
    
    private function filtrarLista($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);        
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
    }   
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuSede')->listaDQL(
                $session->get('filtroEmpleadoNombre'), 
                $session->get('filtroCodigoCentroCosto')                
                );         
    }       
    
}
