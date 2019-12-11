<?php

namespace Brasa\AfiliacionBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

//use Brasa\AfiliacionBundle\Form\Type\AfiIngresoType;

class terminacionesController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/afi/base/empleado/terminaciones", name="brs_afi_base_empleado_terminaciones")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

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
        }

        $arTerminaciones = $paginator->paginate($this->strDqlLista, $request->query->get('page', 1), 300);
        $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->findAll();
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:terminaciones.html.twig', array(
                    'arTerminaciones' => $arTerminaciones,
                    'arContratos' => $arContratos,
                    'form' => $form->createView()));
    }

    private function lista() {
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaTerminacionesDql(
                $session->get('filtroEmpleadoNombre'), $session->get('filtroCodigoCliente'), $session->get('filtroEmpleadoIdentificacion'), $session->get('filtroDesde'), $session->get('filtroHasta')
        );
    }

    /**
     * @Route("/afi/base/empleado/terminar/{codigoContrato}", name="brs_afi_base_empleado_terminar")
     */
    public function terminarAction(Request $request, $codigoContrato) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $respuesta = "";
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContrato = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
        $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($codigoContrato);
        $formContrato = $this->createFormBuilder()
                ->setAction($this->generateUrl('brs_afi_base_empleado_terminar', array('codigoContrato' => $codigoContrato)))
                ->add('fechaHasta', 'date', array('label' => 'Terminacion', 'data' => new \DateTime('now')))
                ->add('BtnGuardar', 'submit', array('label' => 'Guardar'))
                ->getForm();
        $formContrato->handleRequest($request);
        if ($formContrato->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $dateFechaHasta = $formContrato->get('fechaHasta')->getData();
            $respuesta = $em->getRepository('BrasaAfiliacionBundle:AfiNovedad')->dqlNovedades($arContrato->getCodigoEmpleadoFk(), $dateFechaHasta);
            if ($respuesta != "") {
                $objMensaje->Mensaje('error', $respuesta, $this);
            } else {
                $arContrato->setFechaHasta($dateFechaHasta);
                $arContrato->setIndefinido(0);
                $em->persist($arContrato);
                $em->flush();                
            }
            return $this->redirect($this->generateUrl('brs_afi_base_empleado_terminaciones'));
        }
        return $this->render('BrasaAfiliacionBundle:Base/Empleado:terminar.html.twig', array(
                    'arContrato' => $arContrato,
                    'formContrato' => $formContrato->createView()
        ));
    }

    private function filtrar($form) {
        $session = new Session();
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEmpleadoIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
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
        $session = new Session();
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
                ->add('TxtNit', textType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
                ->add('TxtNombreCliente', textType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
                ->add('TxtNombre', textType::class, array('label' => 'Nombre', 'data' => $session->get('filtroEmpleadoNombre')))
                ->add('TxtNumeroIdentificacion', textType::class, array('label' => 'Nombre', 'data' => $session->get('filtroEmpleadoIdentificacion')))
                ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
                ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
                ->getForm();
        return $form;
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $em = $this->getDoctrine()->getManager();
        $session = new Session();
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
                ->setCellValue('A1', 'CONTRATO')
                ->setCellValue('B1', 'CLIENTE')
                ->setCellValue('C1', 'IDENTIFICACION')
                ->setCellValue('D1', 'EMPLEADO')
                ->setCellValue('E1', 'DESDE')
                ->setCellValue('F1', 'HASTA')
                ->setCellValue('G1', 'RETIRADO');
        $i = 2;

        //$query = $em->createQuery($this->strDqlLista);
        //$arIngresos = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        //$arGeneral = $query->getResult();

        $arGeneral = $this->strDqlLista;

        foreach ($arGeneral as $arGeneral) {

            $arContratos = new \Brasa\AfiliacionBundle\Entity\AfiContrato();
            $arContratos = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->findAll();
            if ($arGeneral['cliente'] != null) {
                $cliente = $arGeneral['cliente'];
            } else {
                foreach ($arContratos as $arContratos) {
                    $arContrato = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->find($arGeneral['codigoContratoPk']);
                    $cliente = $arContrato->getClienteRel()->getNombreCorto();
                }
            }
            if ($arGeneral['indefinido'] == 1) {
                $retirado = "NO";
            } else {
                $retirado = "SI";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arGeneral['codigoContratoPk'])
                    ->setCellValue('B' . $i, $cliente)
                    ->setCellValue('C' . $i, $arGeneral['identificacion'])
                    ->setCellValue('D' . $i, $arGeneral['empleado'])
                    ->setCellValue('E' . $i, $arGeneral['desde'])
                    ->setCellValue('F' . $i, $arGeneral['hasta'])
                    ->setCellValue('G' . $i, $retirado);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('EmpleadoContrato');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="EmpleadosContratos.xlsx"');
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
