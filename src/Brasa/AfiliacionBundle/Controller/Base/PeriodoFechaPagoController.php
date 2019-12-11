<?php

namespace Brasa\AfiliacionBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiPeriodoFechaPagoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PeriodoFechaPagoController extends Controller {

    var $strDqlLista = "";

    /**
     * @Route("/afi/base/periodo/fecha/pago", name="brs_afi_base_periodo_fecha_pago")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoFechaPago')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_periodo_fecha_pago'));
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->generarExcel();
            }
        }

        $arPeriodosFechasPagos = $paginator->paginate($em->getRepository('BrasaAfiliacionBundle:AfiPeriodoFechaPago')->findAll(), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/PeriodoFechaPago:lista.html.twig', array(
                    'arPeriodosFechasPagos' => $arPeriodosFechasPagos,
                    'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/periodo/fecha/pago/nuevo/{codigoPeriodoFechaPago}", name="brs_afi_base_periodo_fecha_pago_nuevo")
     */
    public function nuevoAction(Request $request, $codigoPeriodoFechaPago = '') {
        $em = $this->getDoctrine()->getManager();
        $arPeriodoFechaPago = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
        if ($codigoPeriodoFechaPago != '' && $codigoPeriodoFechaPago != '0') {
            $arPeriodoFechaPago = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoFechaPago')->find($codigoPeriodoFechaPago);
        }
        $form = $this->createForm(new AfiPeriodoFechaPagoType, $arPeriodoFechaPago);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arPeriodoFechaPago = $form->getData();
            $em->persist($arPeriodoFechaPago);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_afi_base_periodo_fecha_pago'));
        }
        return $this->render('BrasaAfiliacionBundle:Base/PeriodoFechaPago:nuevo.html.twig', array(
                    'arPeriodoFechaPago' => $arPeriodoFechaPago,
                    'form' => $form->createView()));
    }

    private function formularioFiltro() {
        $session = new Session();
        $form = $this->createFormBuilder()
                ->add('BtnEliminar', SubmitType::class, array('label' => 'Eliminar',))
                ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
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
        for ($col = 'A'; $col !== 'D'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'CÓDIG0')
                ->setCellValue('B1', 'NOMBRE')
                ->setCellValue('C1', 'DIA HABIL')
                ->setCellValue('D1', 'DIGITO INICIO')
                ->setCellValue('E1', 'DIGITO FIN')
                ->setCellValue('F1', 'AÑO');

        $i = 2;

        $arPeriodosFechasPagos = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
        $arPeriodosFechasPagos = $em->getRepository('BrasaAfiliacionBundle:AfiPeriodoFechaPago')->findAll();

        foreach ($arPeriodosFechasPagos as $arPeriodoFechaPago) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPeriodoFechaPago->getCodigoPeriodoFechaPagoPk())
                    ->setCellValue('B' . $i, $arPeriodoFechaPago->getNombre())
                    ->setCellValue('C' . $i, $arPeriodoFechaPago->getDiaHabil())
                    ->setCellValue('D' . $i, $arPeriodoFechaPago->getDosUltimosDigitosInicioNit())
                    ->setCellValue('E' . $i, $arPeriodoFechaPago->getDosUltimosDigitosInicioNit())
                    ->setCellValue('F' . $i, $arPeriodoFechaPago->getAnio());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('PeriodoFechaPago');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="PeriodoFechaPago.xlsx"');
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
