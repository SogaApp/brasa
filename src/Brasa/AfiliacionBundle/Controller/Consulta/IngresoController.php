<?php

namespace Brasa\AfiliacionBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

//use Brasa\AfiliacionBundle\Form\Type\AfiIngresoType;
class IngresoController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/afi/consulta/contrato/ingreso", name="brs_afi_consulta_contrato_ingreso")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 99)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->formularioFiltro();
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
            if ($form->get('BtnImprimir')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $objConsultaContratoIngreso = new \Brasa\AfiliacionBundle\Formatos\ConsultaContratoIngreso();
                $objConsultaContratoIngreso->Generar($this, $em->createQuery($this->strDqlLista));
            }
        }

        $arIngresos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 70);
        return $this->render('BrasaAfiliacionBundle:Consulta/Contrato:ingreso.html.twig', array(
                    'arIngresos' => $arIngresos,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaConsultaDql(
                $session->get('filtroEmpleadoNombre'), $session->get('filtroCodigoCliente'), $session->get('filtroEmpleadoIdentificacion'), $session->get('filtroDesde'), $session->get('filtroHasta'), $session->get('estadoPagado')
        );
    }

    private function filtrar($form) {
        $session = new session;
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEmpleadoIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('estadoPagado', $form->get('estadoPagado')->getData());
        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null) {
            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
        } else {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        }

        $this->lista();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if ($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if ($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            } else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }
        } else {
            $session->set('filtroCodigoCliente', null);
        }
        $form = $this->createFormBuilder()
                ->add('estadoPagado', ChoiceType::class, array(
                    'choices' => array(
                        '2' => 'TODOS',
                        '0' => 'NO',
                        '1' => 'SI',
                        '3' => 'CONVENIO'
                    ),
                    'data' => $session->get('estadoPagado'),
                ))
                ->add('TxtNit', textType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', textType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('TxtNombre', textType::class, array('label' => 'Nombre', 'data' => $session->get('filtroEmpleadoNombre')))
                ->add('TxtNumeroIdentificacion', textType::class, array('label' => 'Nombre', 'data' => $session->get('filtroEmpleadoIdentificacion')))
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnImprimir', SubmitType::class, array('label' => 'Imprimir',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        for ($col = 'A'; $col !== 'R'; $col++) {
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
                ->setCellValue('Y1', 'RETIRADO')
                ->setCellValue('Z1', 'RECIBO')
                ->setCellValue('AA1', 'VALOR')
                ->setCellValue('AB1', 'FORMA PAGO');
        $i = 2;

        $query = $em->createQuery($this->strDqlLista);
        //$arIngresos = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        $arIngresos = $query->getResult();

        foreach ($arIngresos as $arIngresos) {
            $ciudad = '';
            if ($arIngresos->getEmpleadoRel()->getCodigoCiudadFk() != null) {
                $ciudad = $arIngresos->getEmpleadoRel()->getCiudadRel()->getNombre();
            }
            $rh = '';
            if ($arIngresos->getEmpleadoRel()->getCodigoRhPk() != null) {
                $rh = $arIngresos->getEmpleadoRel()->getRhRel()->getTipo();
            }
            $estadoCivil = '';
            if ($arIngresos->getEmpleadoRel()->getCodigoEstadoCivilFk() != null) {
                $estadoCivil = $arIngresos->getEmpleadoRel()->getEstadoCivilRel()->getNombre();
            }
            if ($arIngresos->getEmpleadoRel()->getCodigoSexoFk() == 'M') {
                $sexo = 'MASCULINO';
            } else {
                $sexo = 'FEMENINO';
            }
            if ($arIngresos->getEmpleadoRel()->getCodigoContratoActivo() == null) {
                $codigoContratoActivo = 0;
            } else {
                $codigoContratoActivo = $arIngresos->getEmpleadoRel()->getCodigoContratoActivo();
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
            $indefinido = '';
            $numeroRecibo = '';
            $valor = '';
            $formaPago = '';

            if ($arContrato != null) {

                if ($arContrato->getCodigoCargoFk() != null) {
                    $cargo = $arContrato->getCargoRel()->getNombre();
                }
                if ($arContrato->getFechaDesde() != null) {
                    $fechaDesde = $arContrato->getFechaDesde()->format('Y-m-d');
                }
                if ($arContrato->getFechaHasta() != null) {
                    $fechaHasta = $arContrato->getFechaHasta()->format('Y-m-d');
                }
                if ($arContrato->getCodigoTipoCotizanteFk() != null) {
                    $tipoCotizante = $arContrato->getSsoTipoCotizanteRel()->getNombre();
                }
                if ($arContrato->getCodigoSucursalFk() != null) {
                    $sucursal = $arContrato->getSucursalRel()->getNombre();
                }
                if ($arContrato->getCodigoEntidadPensionFk() != null) {
                    $pension = $arContrato->getEntidadPensionRel()->getNombre();
                }if ($arContrato->getCodigoEntidadSaludFk() != null) {
                    $salud = $arContrato->getEntidadSaludRel()->getNombre();
                }
                if ($arContrato->getCodigoClasificacionRiesgoFk() != null) {
                    $arl = $arContrato->getClasificacionRiesgoRel()->getNombre();
                }
                if ($arContrato->getCodigoEntidadCajaFk() != null) {
                    $caja = $arContrato->getEntidadCajaRel()->getNombre();
                }
                if ($arContrato->getIndefinido() == 1) {
                    $indefinido = 'NO';
                } else {
                    $indefinido = 'SI';
                }
                if ($arContrato->getNumeroRecibo() != null) {
                    $numeroRecibo = $arContrato->getNumeroRecibo();
                }
                if ($arContrato->getValor() != null) {
                    $valor = $arContrato->getValor();
                }
                if ($arContrato->getFormaPago() != null) {
                    $formaPago = $arContrato->getFormaPago();
                }
            }
            $cliente = '';
            if ($arIngresos->getEmpleadoRel()->getCodigoClienteFk() != null) {
                $cliente = $arIngresos->getEmpleadoRel()->getClienteRel()->getNombreCorto();
            }
            if ($arIngresos->getEmpleadoRel()->getClienteRel()->getIndependiente() == 1) {
                $independiente = 'NO';
            } else {
                $independiente = 'SI';
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arIngresos->getCodigoEmpleadoFk())
                    ->setCellValue('B' . $i, $arIngresos->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arIngresos->getEmpleadoRel()->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('D' . $i, $arIngresos->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('E' . $i, $ciudad)
                    ->setCellValue('F' . $i, $arIngresos->getEmpleadoRel()->getDireccion())
                    ->setCellValue('G' . $i, $arIngresos->getEmpleadoRel()->getBarrio())
                    ->setCellValue('H' . $i, $arIngresos->getEmpleadoRel()->getTelefono())
                    ->setCellValue('I' . $i, $arIngresos->getEmpleadoRel()->getCelular())
                    ->setCellValue('J' . $i, $arIngresos->getEmpleadoRel()->getCorreo())
                    ->setCellValue('K' . $i, $rh)
                    ->setCellValue('L' . $i, $estadoCivil)
                    ->setCellValue('M' . $i, $arIngresos->getEmpleadoRel()->getFechaNacimiento()->format('Y-m-d'))
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
                    ->setCellValue('Y' . $i, $indefinido)
                    ->setCellValue('Z' . $i, $numeroRecibo)
                    ->setCellValue('AA' . $i, $valor)
                    ->setCellValue('AB' . $i, $formaPago);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('EmpleadoContratoIngreso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="EmpleadosContratoIngreso.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        exit;
    }

}
