<?php
namespace Brasa\CarteraBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Brasa\CarteraBundle\Form\Type\CarClienteType;

class ClienteController extends Controller
{
    var $strDqlLista = "";
    var $strCodigo = "";
    var $strNombre = "";
    var $strIdentificacion = "";
    /**
     * @Route("/cartera/base/cliente/lista", name="brs_cartera_base_cliente_listar")
     */   
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 106, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 106, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaCarteraBundle:CarCliente')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_cartera_base_cliente_listar'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arClientes = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaCarteraBundle:Base/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes, 
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/cartera/base/cliente/nuevo/{codigoCliente}", name="brs_cartera_base_cliente_nuevo")
     */
    public function nuevoAction($codigoCliente = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCliente = new \Brasa\CarteraBundle\Entity\CarCliente();
        if($codigoCliente != '' && $codigoCliente != '0') {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 106, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arCliente = $em->getRepository('BrasaCarteraBundle:CarCliente')->find($codigoCliente);
        } else {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 106, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
        }        
        $form = $this->createForm(new CarClienteType, $arCliente);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCliente = $form->getData();
            $arClienteValidar = new \Brasa\CarteraBundle\Entity\CarCliente();
            $arClienteValidar = $em->getRepository('BrasaCarteraBundle:CarCliente')->findBy(array('nit' => $arCliente->getNit()));
            if(($codigoCliente == 0 || $codigoCliente == '') && count($arClienteValidar) > 0) {
                $objMensaje->Mensaje("error", "El cliente con ese nit ya existe", $this);
            } else {
                $arUsuario = $this->getUser();
                $arCliente->setUsuario($arUsuario->getUserName());
                $em->persist($arCliente);
                $em->flush();            
                if($form->get('guardarnuevo')->isClicked()) {
                    return $this->redirect($this->generateUrl('brs_cartera_base_cliente_nuevo', array('codigoCliente' => 0 )));
                } else {
                    return $this->redirect($this->generateUrl('brs_cartera_base_cliente_listar'));
                }                                   
            }                                                                            

        }
        return $this->render('BrasaCarteraBundle:Base/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }          
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaCarteraBundle:CarCliente')->listaDQL(
                $this->strNombre,                
                $this->strCodigo,
                $this->strIdentificacion
                ); 
    }

    private function filtrar ($form) {
        $this->strCodigo = $form->get('TxtCodigo')->getData();
        $this->strNombre = $form->get('TxtNombre')->getData();
        $this->strIdentificacion = $form->get('TxtIdentificacion')->getData();
        $this->lista();
    }
    
    private function formularioFiltro() {
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->strNombre))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificacion','data' => $this->strIdentificacion))
            ->add('TxtCodigo', 'text', array('label'  => 'Codigo','data' => $this->strCodigo))                            
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
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
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'DV')
                    ->setCellValue('D1', 'NOMBRE')
                    ->setCellValue('E1', 'CIUDAD')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'TELEFONO')
                    ->setCellValue('H1', 'CELULAR')
                    ->setCellValue('I1', 'EMAIL')
                    ->setCellValue('J1', 'FAX')
                    ->setCellValue('K1', 'FORMA PAGO')
                    ->setCellValue('L1', 'PLAZO PAGO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arClientes = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClientes = $query->getResult();
                
        foreach ($arClientes as $arCliente) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNit())
                    ->setCellValue('C' . $i, $arCliente->getDigitoVerificacion())
                    ->setCellValue('D' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('E' . $i, $arCliente->getCiudadRel()->getNombre())
                    ->setCellValue('F' . $i, $arCliente->getDireccion())
                    ->setCellValue('G' . $i, $arCliente->getTelefono())
                    ->setCellValue('H' . $i, $arCliente->getCelular())
                    ->setCellValue('I' . $i, $arCliente->getEmail())
                    ->setCellValue('J' . $i, $arCliente->getFax())
                    ->setCellValue('K' . $i, $arCliente->getFormaPagoRel()->getNombre())
                    ->setCellValue('L' . $i, $arCliente->getPlazoPago());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Cliente');
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