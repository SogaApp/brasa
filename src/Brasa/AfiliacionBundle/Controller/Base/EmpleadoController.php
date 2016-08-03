<?php
namespace Brasa\AfiliacionBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\AfiliacionBundle\Form\Type\AfiEmpleadoType;
use Brasa\AfiliacionBundle\Form\Type\AfiContratoType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class EmpleadoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/empleado", name="brs_afi_base_empleado")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                try{
                    $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->eliminar($arrSeleccionados);
                    return $this->redirect($this->generateUrl('brs_afi_base_empleado'));
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el empleado, tiene registros asociados', $this);
                  }
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();                
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }
        
        $arEmpleados = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:lista.html.twig', array(
            'arEmpleados' => $arEmpleados, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/empleado/nuevo/{codigoEmpleado}", name="brs_afi_base_empleado_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoEmpleado = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        if($codigoEmpleado != '' && $codigoEmpleado != '0') {
            $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
        } else {
            $arEmpleado->setFechaNacimiento(new \DateTime('now'));
        }        
        $form = $this->createForm(new AfiEmpleadoType, $arEmpleado);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arEmpleado = $form->getData(); 
            $arEmpleado->setNombreCorto($arEmpleado->getNombre1() . " " . $arEmpleado->getNombre2() . " " .$arEmpleado->getApellido1() . " " . $arEmpleado->getApellido2());
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            if($codigoEmpleado == 0) {
                $arEmpleado->setCodigoUsuario($arUsuario->getUserName());
            }
            $em->persist($arEmpleado);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_empleado_nuevo', array('codigoEmpleado' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_empleado'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/base/empleado/detalle/{codigoEmpleado}", name="brs_afi_base_empleado_detalle")
     */    
    public function detalleAction(Request $request, $codigoEmpleado = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioDetalle();
        $form->handleRequest($request);        
        if ($form->isValid()) {                            
            if ($form->get('BtnEliminarContrato')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarContrato');
                try{
                    $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->eliminar($arrSeleccionados,$codigoEmpleado);
                } catch (ForeignKeyConstraintViolationException $e) { 
                    $objMensaje->Mensaje('error', 'No se puede eliminar el contrato, tiene registros asociados', $this);
                  }
                //return $this->redirect($this->generateUrl('brs_tur_base_empleado_concepto'));
            }
            if ($form->get('BtnImprimir')->isClicked()) {
               $objFormatoEmpleado = new \Brasa\AfiliacionBundle\Formatos\Empleado();
               $objFormatoEmpleado->Generar($this, $codigoEmpleado);
            }

        }
        $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaDetalleDql($codigoEmpleado);        
        $arContratos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:detalle.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'arContratos' => $arContratos, 
            'form' => $form->createView()));
    }        
    
    /**
     * @Route("/afi/base/empleado/contrato/nuevo/{codigoEmpleado}/{codigoContrato}", name="brs_afi_base_empleado_contrato_nuevo")
     */    
    public function contratoNuevoAction(Request $request, $codigoEmpleado = '', $codigoContrato = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        if($codigoContrato != '' && $codigoContrato != '0') {
            $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigoContrato);
        } else {
            $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
            $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);            
            $arContrato->setEmpleadoRel($arEmpleado);
            $arContrato->setClienteRel($arEmpleado->getClienteRel());
            $arContrato->setFechaDesde(new \DateTime('now'));
            $arContrato->setFechaHasta(new \DateTime('now'));
            $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->configuracionDatoCodigo(1);//SALARIO MINIMO
            $douSalarioMinimo = $arConfiguracion->getVrSalario();
            $arContrato->setVrSalario($douSalarioMinimo);
            $arContrato->setIndefinido(true);
            if($arEmpleado->getClienteRel()) {
                $arContrato->setGeneraPension($arEmpleado->getClienteRel()->getGeneraPension());
                $arContrato->setGeneraSalud($arEmpleado->getClienteRel()->getGeneraSalud());
                $arContrato->setGeneraRiesgos($arEmpleado->getClienteRel()->getGeneraRiesgos());
                $arContrato->setGeneraCaja($arEmpleado->getClienteRel()->getGeneraCaja());
                $arContrato->setGeneraSena($arEmpleado->getClienteRel()->getGeneraSena());
                $arContrato->setGeneraIcbf($arEmpleado->getClienteRel()->getGeneraIcbf()); 
                $arContrato->setPorcentajePension($arEmpleado->getClienteRel()->getPorcentajePension());
                $arContrato->setPorcentajeSalud($arEmpleado->getClienteRel()->getPorcentajeSalud());
                $arContrato->setPorcentajeCaja($arEmpleado->getClienteRel()->getPorcentajeCaja());                
            }            
        }        
        $form = $this->createForm(new AfiContratoType, $arContrato);
        $form->handleRequest($request);
        if ($form->isValid()) {      
            $em->persist($arContrato);
            $em->flush();
            if($codigoContrato == 0 || $codigoContrato == '') {
                $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
                $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);                            
                $arEmpleado->setCodigoContratoActivo($arContrato->getCodigoContratoPk());
                $em->persist($arEmpleado);
                $em->flush();
            }                                    
            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                  
        }
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:contratoNuevo.html.twig', array(
            'form' => $form->createView()));
    }     
    
    private function lista() {    
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCliente'),
                $session->get('filtroEmpleadoIdentificacion')
                ); 
    }
    
    private function filtrar ($form) {        
        $session = $this->getRequest()->getSession();        
        $session->set('filtroNit', $form->get('TxtNit')->getData()); 
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEmpleadoIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        } 
        $form = $this->createFormBuilder()            
            ->add('TxtNit', 'text', array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', 'text', array('label'  => 'NombreCliente','data' => $strNombreCliente))                                
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoNombre')))
            ->add('TxtNumeroIdentificacion', 'text', array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoIdentificacion')))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function formularioDetalle() {
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()                        
            ->add('BtnEliminarContrato', 'submit', array('label'  => 'Eliminar contrato',))            
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))                        
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
        for($col = 'A'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'TIPO ID')
                    ->setCellValue('D1', 'EMPLEADO')
                    ->setCellValue('E1', 'CIUDAD')
                    ->setCellValue('F1', 'DIRECCION')
                    ->setCellValue('G1', 'BARRIO')
                    ->setCellValue('H1', 'TELEFONO')
                    ->setCellValue('I1', 'CELULAR')
                    ->setCellValue('J1', 'EMAIL')
                    ->setCellValue('K1', 'RH')
                    ->setCellValue('L1', 'ESTADO CIVIL')
                    ->setCellValue('M1', 'FECHA NAC')
                    ->setCellValue('N1', 'SEXO')
                    ->setCellValue('O1', 'CARGO')
                    ->setCellValue('P1', 'FECHA DESDE')
                    ->setCellValue('Q1', 'FECHA HASTA')
                    ->setCellValue('R1', 'TIPO CONTIZANTE')
                    ->setCellValue('S1', 'SUCURSAL')
                    ->setCellValue('T1', 'PENSION')
                    ->setCellValue('U1', 'SALUD')
                    ->setCellValue('V1', 'ARL')
                    ->setCellValue('W1', 'CAJA')
                    ->setCellValue('X1', 'CLIENTE')
                    ->setCellValue('Y1', 'INDEFINIDO')
                    ->setCellValue('Z1', 'ACTIVO');
        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arEmpleados = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arEmpleados = $query->getResult();
                
        foreach ($arEmpleados as $arEmpleado) {
        $ciudad = '';
        if ($arEmpleado->getCodigoCiudadFk() != null){
            $ciudad = $arEmpleado->getCiudadRel()->getNombre();
        }
        $rh = '';
        if ($arEmpleado->getCodigoRhPk() != null){
            $rh = $arEmpleado->getRhRel()->getTipo();
        }
        $estadoCivil = '';
        if ($arEmpleado->getCodigoEstadoCivilFk() != null){
            $estadoCivil = $arEmpleado->getEstadoCivilRel()->getNombre();
        }
        if ($arEmpleado->getCodigoSexoFk() == 'M'){
            $sexo = 'MASCULINO';
        } else {
            $sexo = 'FEMENINO';
        }
        if ($arEmpleado->getCodigoContratoActivo() == null){
            $codigoContratoActivo = 0;
        } else {
            $codigoContratoActivo = $arEmpleado->getCodigoContratoActivo();
        }
        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigoContratoActivo);
        
        $cargo = '';
        $fechaDesde = '';
        $fechaHasta = '';
        $tipoCotizante = '';
        $sucursal = '';
        $pension = '';
        $salud = '';
        $arl = '';
        $caja = '';
        if ($arContrato != null){
            
            if ($arContrato->getCodigoCargoFk() != null){
                $cargo = $arContrato->getCargoRel()->getNombre();
            }
            if ($arContrato->getFechaDesde() != null){
                $fechaDesde = $arContrato->getFechaDesde()->format('Y-m-d');
            }
            if ($arContrato->getFechaHasta() != null){
                $fechaHasta = $arContrato->getFechaHasta()->format('Y-m-d');
            }
            if ($arContrato->getCodigoTipoCotizanteFk() != null){
                $tipoCotizante = $arContrato->getSsoTipoCotizanteRel()->getNombre();
            }
            if ($arContrato->getCodigoSucursalFk() != null){
                $sucursal = $arContrato->getSucursalRel()->getNombre();
            }
            if ($arContrato->getCodigoEntidadPensionFk() != null){
                $pension = $arContrato->getEntidadPensionRel()->getNombre();
            }if ($arContrato->getCodigoEntidadSaludFk() != null){
                $salud = $arContrato->getEntidadSaludRel()->getNombre();
            }
            if ($arContrato->getCodigoClasificacionRiesgoFk() != null){
                $arl = $arContrato->getClasificacionRiesgoRel()->getNombre();
            }
            if ($arContrato->getCodigoEntidadCajaFk() != null){
                $caja = $arContrato->getEntidadCajaRel()->getNombre();
            }
        }
        $cliente = '';
        if ($arEmpleado->getCodigoClienteFk() != null){
            $cliente = $arEmpleado->getClienteRel()->getNombreCorto();
        }
        if ($arEmpleado->getClienteRel()->getIndependiente() == 1){
            $independiente = 'SI';
        } else {
            $independiente = 'NO';
        }
        if ($arEmpleado->getEstadoActivo() == 1){
            $activo = 'SI';
        } else {
            $activo = 'NO';
        }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                    ->setCellValue('B' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arEmpleado->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('D' . $i, $arEmpleado->getNombreCorto())
                    ->setCellValue('E' . $i, $ciudad)
                    ->setCellValue('F' . $i, $arEmpleado->getDireccion())
                    ->setCellValue('G' . $i, $arEmpleado->getBarrio())
                    ->setCellValue('H' . $i, $arEmpleado->getTelefono())
                    ->setCellValue('I' . $i, $arEmpleado->getCelular())
                    ->setCellValue('J' . $i, $arEmpleado->getCorreo())
                    ->setCellValue('K' . $i, $rh)
                    ->setCellValue('L' . $i, $estadoCivil)
                    ->setCellValue('M' . $i, $arEmpleado->getFechaNacimiento()->format('Y-m-d'))
                    ->setCellValue('N' . $i, $sexo)
                    ->setCellValue('O' . $i, $cargo)
                    ->setCellValue('P' . $i, $fechaDesde)
                    ->setCellValue('Q' . $i, $fechaHasta)
                    ->setCellValue('R' . $i, $tipoCotizante)
                    ->setCellValue('S' . $i, $sucursal)
                    ->setCellValue('T' . $i, $pension)
                    ->setCellValue('U' . $i, $salud)
                    ->setCellValue('V' . $i, $arl)
                    ->setCellValue('W' . $i, $caja)
                    ->setCellValue('X' . $i, $cliente)
                    ->setCellValue('Y' . $i, $independiente)
                    ->setCellValue('Z' . $i, $activo);                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Empleado');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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