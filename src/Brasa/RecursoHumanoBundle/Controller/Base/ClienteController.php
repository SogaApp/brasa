<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Brasa\RecursoHumanoBundle\Form\Type\RhuClienteType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class ClienteController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/base/cliente", name="brs_rhu_base_cliente")
     */       
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 94, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombreCliente')))
            ->add('BtnBuscar', SubmitType::class, array('label'  => 'Buscar'))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->getForm();
        $form->handleRequest($request);
        $this->lista();        
        if($form->isValid()) {
            if($form->get('BtnBuscar')->isClicked() || $form->get('BtnExcel')->isClicked()) {
                $session->set('dqlCliente', $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->ListaDQL(
                    $form->get('TxtNombre')->getData()
                    ));                
                $session->set('filtroNombreCliente', $form->get('TxtNombre')->getData());                
            }            
            if($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
            
        } else {
            $session->set('dqlCliente', $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->ListaDQL(
                    $session->get('filtroNombreCliente')
                    ));                          
        }             
        $arClientes = $paginator->paginate($em->createQuery($session->get('dqlCliente')), $this->get('Request')->query->get('page', 1), 50);
        return $this->render('BrasaRecursoHumanoBundle:Base/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes,
            'form' => $form->createView()));
    }
    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->listaDQL(
                $session->get('filtroClienteNombre')   
                ); 
    }    

    /**
     * @Route("/rhu/base/cliente/nuevo/{codigoCliente}", name="brs_rhu_base_cliente_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCliente) {        
        $em = $this->getDoctrine()->getManager();          
        $arCliente = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();
        if($codigoCliente != 0) {
            $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($codigoCliente);
        }
        
        $form = $this->createForm(RhuClienteType::class, $arCliente);        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $arCliente = $form->getData();
            if ($codigoCliente == 0){
                $arCliente->setUsuario($arUsuario->getUserName());
            }                
            $em->persist($arCliente);
            $em->flush();
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_rhu_base_cliente_nuevo', array('codigoCliente' => 0)));
            } else {
                return $this->redirect($this->generateUrl('brs_rhu_base_cliente'));
            }                         

        }

        return $this->render('BrasaRecursoHumanoBundle:Base/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,            
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/rhu/base/cliente/detalle/{codigoCliente}", name="brs_rhu_base_cliente_detalle")
     */
    public function detalleAction(Request $request, $codigoCliente) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()               
            ->getForm();
        $form->handleRequest($request);
        $arCliente = new \Brasa\RecursoHumanoBundle\Entity\RhuCliente();
        $arCliente = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->find($codigoCliente);
        return $this->render('BrasaRecursoHumanoBundle:Base/Cliente:detalle.html.twig', array(        
            'arCliente' => $arCliente,
            'form' => $form->createView()
                    ));
    }    
    
    private function generarExcel() {
         $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
                $objPHPExcel->getDefaultStyle('')->getFont()->setName('Arial')->setSize(10); 
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
                $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'NIT')
                            ->setCellValue('C1', 'DV')
                            ->setCellValue('D1', 'CLIENTE')
                            ->setCellValue('E1', 'FORMA PAGO')
                            ->setCellValue('F1', 'PLAZO')
                            ->setCellValue('G1', 'DIRECCION')
                            ->setCellValue('H1', 'BARRIO')
                            ->setCellValue('I1', 'CIUDAD')
                            ->setCellValue('J1', 'TELEFONO')
                            ->setCellValue('K1', 'CELULAR')
                            ->setCellValue('L1', 'FAX')
                            ->setCellValue('M1', 'EMAIL')
                            ->setCellValue('N1', 'GERENTE')
                            ->setCellValue('O1', 'CELULAR')
                            ->setCellValue('P1', 'FINANCIERO')
                            ->setCellValue('Q1', 'CELULAR')
                            ->setCellValue('R1', 'CONTACTO')
                            ->setCellValue('S1', 'CELULAR')
                            ->setCellValue('T1', 'TELEFONO')
                            ->setCellValue('U1', 'COMENTARIOS');
                $i = 2;
                $arClientes = $em->getRepository('BrasaRecursoHumanoBundle:RhuCliente')->findAll();
                foreach ($arClientes as $arCliente) {
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                            ->setCellValue('B' . $i, $arCliente->getNit())
                            ->setCellValue('C' . $i, $arCliente->getDigitoVerificacion())
                            ->setCellValue('D' . $i, $arCliente->getNombreCorto())
                            ->setCellValue('E' . $i, $arCliente->getFormaPagoRel()->getNombre())
                            ->setCellValue('F' . $i, $arCliente->getPlazoPago())
                            ->setCellValue('G' . $i, $arCliente->getDireccion())
                            ->setCellValue('H' . $i, $arCliente->getBarrio())
                            ->setCellValue('I' . $i, $arCliente->getCiudadRel()->getNombre())
                            ->setCellValue('J' . $i, $arCliente->getTelefono())
                            ->setCellValue('K' . $i, $arCliente->getCelular())
                            ->setCellValue('L' . $i, $arCliente->getFax())
                            ->setCellValue('M' . $i, $arCliente->getEmail())
                            ->setCellValue('N' . $i, $arCliente->getGerente())
                            ->setCellValue('O' . $i, $arCliente->getCelularGerente())
                            ->setCellValue('P' . $i, $arCliente->getFinanciero())
                            ->setCellValue('Q' . $i, $arCliente->getCelularFinanciero())
                            ->setCellValue('R' . $i, $arCliente->getContacto())
                            ->setCellValue('S' . $i, $arCliente->getCelularContacto())
                            ->setCellValue('T' . $i, $arCliente->getTelefonoContacto())
                            ->setCellValue('U' . $i, $arCliente->getComentarios())
                        ;
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Clientes');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Clientes.xlsx"');
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
