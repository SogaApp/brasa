<?php
namespace Brasa\AfiliacionBundle\Controller\Base;


use Brasa\AfiliacionBundle\BrasaAfiliacionBundle;
use Brasa\AfiliacionBundle\Entity\AfiCambioSalario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiClienteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class ClienteController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/cliente", name="brs_afi_base_cliente")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_cliente'));
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
        return $this->render('BrasaAfiliacionBundle:Base/Cliente:lista.html.twig', array(
            'arClientes' => $arClientes, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/cliente/nuevo/{codigoCliente}", name="brs_afi_base_cliente_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCliente = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
        if($codigoCliente != '' && $codigoCliente != '0') {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigoCliente);
        } else {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 121, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
        }       
        $form = $this->createForm(new AfiClienteType, $arCliente);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCliente = $form->getData();                        
            $em->persist($arCliente);            
            $em->flush();
            $nitClienteAfiliacion = $arCliente->getNit();
            $arClienteCartera = $em->getRepository('BrasaCarteraBundle:CarCliente')->findOneBy(array('nit' => $nitClienteAfiliacion));
            if ($arClienteCartera == null){
                $arClienteNuevo = new \Brasa\CarteraBundle\Entity\CarCliente();
                $arClienteNuevo->setNit($arCliente->getNit());
                $arClienteNuevo->setAsesorRel($arCliente->getAsesorRel());
                $arClienteNuevo->setCiudadRel($arCliente->getCiudadRel());
                $arClienteNuevo->setDigitoVerificacion($arCliente->getDigitoVerificacion());
                $arClienteNuevo->setNombreCorto($arCliente->getNombreCorto());
                $arClienteNuevo->setFormaPagoRel($arCliente->getFormaPagoRel());
                $arClienteNuevo->setPlazoPago($arCliente->getPlazoPago());
                $arClienteNuevo->setDireccion($arCliente->getDireccion());
                $arClienteNuevo->setCelular($arCliente->getCelular());
                $arClienteNuevo->setTelefono($arCliente->getTelefono());
                $arClienteNuevo->setFax($arCliente->getFax());
                $arClienteNuevo->setEmail($arCliente->getEmail());
                $em->persist($arClienteNuevo);            
                $em->flush();
            } else {
                $arClienteCartera->setNit($arCliente->getNit());
                $arClienteCartera->setAsesorRel($arCliente->getAsesorRel());
                $arClienteCartera->setCiudadRel($arCliente->getCiudadRel());
                $arClienteCartera->setDigitoVerificacion($arCliente->getDigitoVerificacion());
                $arClienteCartera->setNombreCorto($arCliente->getNombreCorto());
                $arClienteCartera->setFormaPagoRel($arCliente->getFormaPagoRel());
                $arClienteCartera->setPlazoPago($arCliente->getPlazoPago());
                $arClienteCartera->setDireccion($arCliente->getDireccion());
                $arClienteCartera->setCelular($arCliente->getCelular());
                $arClienteCartera->setTelefono($arCliente->getTelefono());
                $arClienteCartera->setFax($arCliente->getFax());
                $arClienteCartera->setEmail($arCliente->getEmail());
                $em->persist($arClienteCartera);            
                $em->flush();
            }
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_cliente_nuevo', array('codigoCliente' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_cliente'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/Cliente:nuevo.html.twig', array(
            'arCliente' => $arCliente,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/base/cliente/detalle/{codigoCliente}", name="brs_afi_base_cliente_detalle")
     */    
    public function detalleAction(Request $request, $codigoCliente = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioDetalle();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            
            if ($form->get('BtnImprimir')->isClicked()) {
               $objFormatoCliente = new \Brasa\AfiliacionBundle\Formatos\Cliente();
               $objFormatoCliente->Generar($this, $codigoCliente);
            }
        }
        $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
        $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->find($codigoCliente);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaDetalleDql($codigoCliente);        
        $arContratos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Cliente:detalle.html.twig', array(
            'arCliente' => $arCliente,
            'arContratos' => $arContratos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/cliente/cambioSalario", name="brs_afi_base_cliente_cambio_salario")
     */
    public function cambioSalario(Request $request){
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arrPropiedadesCliente = array(
            'class' => 'BrasaAfiliacionBundle:AfiCliente',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('ac')
                    ->orderBy('ac.nombreCorto', 'ASC');},
            'choice_label' => 'nombreCorto',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        $form = $this->createFormBuilder()
            ->add('clienteRel',EntityType::class , $arrPropiedadesCliente)
            ->add('salario',IntegerType::class,array('label' => 'Salario'))
            ->add('BtnActualizar',SubmitType::class,array('label' => 'Actualizar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('BtnActualizar')->isClicked()){
                $codigoCliente = $form->get("clienteRel")->getData()->getCodigoClientePk();
                $nuevoSalario = $form->get("salario")->getData();
                $arUsuario = $this->getUser()->getUserName();
                $arEmpleados = $em->getRepository("BrasaAfiliacionBundle:AfiEmpleado")->findBy(array('codigoClienteFk' => $codigoCliente));
                foreach ($arEmpleados as $arEmpleado){
                    if($arEmpleado->getCodigoContratoActivo()){
                        $arContrato = $em->getRepository("BrasaAfiliacionBundle:AfiContrato")->find($arEmpleado->getCodigoContratoActivo());
                    }else{
                        $arContrato = $em->getRepository("BrasaAfiliacionBundle:AfiContrato")->findOneBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk(),
                            'estadoActivo'=> 1),array('codigoContratoPk' => 'DESC'));
                    }
                    if($arContrato){
                        $arCambioSalario = new \Brasa\AfiliacionBundle\Entity\AfiCambioSalario();
                        $arCambioSalario->setEmpleadoRel($arEmpleado);
                        $arCambioSalario->setContratoRel($arContrato);
                        $arCambioSalario->setFecha( new \DateTime('now'));
                        $arCambioSalario->setVrSalarioAnterior($arContrato->getVrSalario());
                        $arCambioSalario->setVrSalarioNuevo($nuevoSalario);
                        $arCambioSalario->setCodigoUsuario($arUsuario);
                        $arContrato->setVrSalario($nuevoSalario);
                        $em->persist($arCambioSalario);
                        $em->persist($arContrato);
                    }
                }
                $em->flush();
                $objMensaje->Mensaje("informacion","Salarios Actualizados",$this);
                return $this->redirect($this->generateUrl('brs_afi_base_cliente_cambio_salario'));

            }
        }

            return $this->render('BrasaAfiliacionBundle:Base/Cliente:cambioSalario.html.twig', array(
            'form' => $form->createView()));
    }
    
    private function lista() {  
        $session = new Session();        
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->listaDQL(
                $session->get('filtroClienteNombre'),
                $session->get('filtroClienteCodigo'),
                $session->get('filtroClienteIndentificacion'),
                $session->get('filtroIndependiente')
                ); 
    }

    private function filtrar ($form) {        
        $session = new Session();         
        $session->set('filtroClienteNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroClienteCodigo', $form->get('TxtCodigo')->getData());
        $session->set('filtroClienteIndentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroIndependiente', $form->get('independiente')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new Session(); 
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroClienteNombre')))
            ->add('TxtIdentificacion', textType::class, array('label'  => 'Identificacion','data' => $session->get('filtroClienteIndentificacion')))
            ->add('TxtCodigo', textType::class, array('label'  => 'Codigo'))    
            ->add('independiente', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO')))                                            
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalle() {        
        $form = $this->createFormBuilder()                                    
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir',))                        
            ->getForm();
        return $form;
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
        for($col = 'A'; $col !== 'P'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'FORMAPAGO')
                    ->setCellValue('E1', 'PLAZO')
                    ->setCellValue('F1', 'ADMINISTRACION')
                    ->setCellValue('G1', 'AFILIACION')
                    ->setCellValue('H1', 'DIRECCION')
                    ->setCellValue('I1', 'BARRIO')
                    ->setCellValue('J1', 'CIUDAD')
                    ->setCellValue('K1', 'TELEFONO')
                    ->setCellValue('L1', 'CELULAR')
                    ->setCellValue('M1', 'FAX')
                    ->setCellValue('N1', 'EMAIL')
                    ->setCellValue('O1', 'CONTACTO')
                    ->setCellValue('P1', 'CELCONTACTO')
                    ->setCellValue('Q1', 'TELCONTACTO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arClientes = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
        $arClientes = $query->getResult();
                
        foreach ($arClientes as $arCliente) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCliente->getCodigoClientePk())
                    ->setCellValue('B' . $i, $arCliente->getNit())
                    ->setCellValue('C' . $i, $arCliente->getNombreCorto())
                    ->setCellValue('D' . $i, $arCliente->getFormaPagoRel()->getNombre())
                    ->setCellValue('E' . $i, $arCliente->getPlazoPago())
                    ->setCellValue('F' . $i, $arCliente->getAdministracion())
                    ->setCellValue('G' . $i, $arCliente->getAfiliacion())
                    ->setCellValue('H' . $i, $arCliente->getDireccion())
                    ->setCellValue('I' . $i, $arCliente->getBarrio())
                    ->setCellValue('J' . $i, $arCliente->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arCliente->getTelefono())
                    ->setCellValue('L' . $i, $arCliente->getCelular())
                    ->setCellValue('M' . $i, $arCliente->getFax())
                    ->setCellValue('N' . $i, $arCliente->getEmail())
                    ->setCellValue('O' . $i, $arCliente->getContacto())
                    ->setCellValue('P' . $i, $arCliente->getCelularContacto())
                    ->setCellValue('Q' . $i, $arCliente->getTelefonoContacto());                                    
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