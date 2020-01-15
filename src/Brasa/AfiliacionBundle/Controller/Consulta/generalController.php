<?php

namespace Brasa\AfiliacionBundle\Controller\Consulta;

use Brasa\AfiliacionBundle\Entity\AfiContrato;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

//use Brasa\AfiliacionBundle\Form\Type\AfiIngresoType;

class generalController extends Controller
{
    var $strDqlLista = "";

    /**
     * @Route("/afi/consulta/contrato/general", name="brs_afi_consulta_contrato_general")
     */
    public function listaAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        if (!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 109)) {
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
        }

        $arContratos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 300);
        return $this->render('BrasaAfiliacionBundle:Consulta/Contrato:general.html.twig', array(
            'arContratos' => $arContratos,
            'form' => $form->createView()));
    }

    private function lista()
    {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiContrato')->listaConsultaGeneralDql(
            $session->get('filtroEmpleadoNombre'),
            $session->get('filtroCodigoCliente'),
            $session->get('filtroAsesor'),
            $session->get('filtroEmpleadoIdentificacion'),
            $session->get('filtroDesde'),
            $session->get('filtroHasta'),
            $session->get('filtroActivo')
        );
    }

    private function filtrar($form)
    {
        $session = new session;
        //$controles = $request->request->get('form');
        $session->set('filtroNit', $form->get('TxtNit')->getData());
        $codigoAsesor = "";

        if ($form->get('asesorRel')->getData()) {
            $codigoAsesor = $form->get('asesorRel')->getData()->getCodigoAsesorPk();
        }
        $session->set('filtroAsesor', $codigoAsesor);

        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroEmpleadoIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();

        if ($form->get('fechaDesde')->getData() != null) {
            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
        } else {
            $session->set('filtroDesde', null);
        }

        if ($form->get('fechaHasta')->getData() != null) {
            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
        } else {
            $session->set('filtroHasta', null);
        }


//        if ($form->get('fechaDesde')->getData() == null || $form->get('fechaHasta')->getData() == null) {
//            $session->set('filtroDesde', $form->get('fechaDesde')->getData());
//            $session->set('filtroHasta', $form->get('fechaHasta')->getData());
//        } else {
//            $session->set('filtroDesde', $dateFechaDesde->format('Y-m-d'));
//            $session->set('filtroHasta', $dateFechaHasta->format('Y-m-d'));
//        }
        $session->set('filtroActivo', $form->get('estadoActivo')->getData());
        $this->lista();
    }

    private function formularioFiltro()
    {
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
        $arrayPropiedades = array(
            'class' => 'BrasaGeneralBundle:GenAsesor',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('cc')
                    ->where('cc.estado = 1')
                    ->orderBy('cc.nombre', 'ASC');
            },
            'choice_label' => 'nombre',
            'required' => false,
            'empty_data' => "",
            'placeholder' => "TODOS",
            'data' => ""
        );
        if ($session->get('filtroAsesor')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaGeneralBundle:GenAsesor", $session->get('filtroAsesor'));
        }
        $form = $this->createFormBuilder()
            ->add('TxtNit', textType::class, array('label' => 'Nit', 'data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', textType::class, array('label' => 'NombreCliente', 'data' => $strNombreCliente))
            ->add('asesorRel', EntityType::class, $arrayPropiedades)
            ->add('TxtNombre', textType::class, array('label' => 'Nombre', 'data' => $session->get('filtroEmpleadoNombre')))
            ->add('TxtNumeroIdentificacion', textType::class, array('label' => 'Nombre', 'data' => $session->get('filtroEmpleadoIdentificacion')))
            ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('estadoActivo', ChoiceType::class, array('choices' => array('2' => 'TODOS', '1' => 'ACTIVO', '0' => 'RETIRADO'), 'data' => $session->get('filtroActivo')))
            ->add('BtnExcel', SubmitType::class, array('label' => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label' => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function generarExcel()
    {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        for ($col = 'A'; $col !== 'R'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'CONTRATO')
            ->setCellValue('B1', 'CLIENTE')
            ->setCellValue('C1', 'ASESOR')
            ->setCellValue('D1', 'IDENTIFICACION')
            ->setCellValue('E1', 'EMPLEADO')
            ->setCellValue('F1', 'DESDE')
            ->setCellValue('G1', 'HASTA')
            ->setCellValue('H1', 'ACTIVO')
            ->setCellValue('I1', 'SALARIO');
        $i = 2;

<<<<<<< HEAD
=======


>>>>>>> 30fa032eaa121a2a644bd8fdcc615fe16e877a7d
        //$query = $em->createQuery($this->strDqlLista);
        //$arIngresos = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
        //$arGeneral = $query->getResult();
        /** @var AfiContrato $arContratos */
        $arContratos = ($em->createQuery($this->strDqlLista))->execute();

<<<<<<< HEAD
        foreach ($arContratos as $arContratos) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arContratos->getCodigoContratoPk())
                ->setCellValue('B' . $i, $arContratos->getClienteRel()->getNombreCorto())
                ->setCellValue('C' . $i, $arContratos->getClienteRel()->getAsesorRel()->getNombre())
                ->setCellValue('D' . $i, $arContratos->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('E' . $i, $arContratos->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('F' . $i, $arContratos->getFechaDesde()->format('Y-m-d'))
                ->setCellValue('G' . $i, $arContratos->getFechaHasta()->format('Y-m-d'))
                ->setCellValue('H' . $i, $arContratos->getEstadoActivo() == 1 ? "SI" : "NO")
                ->setCellValue('I' . $i, $arContratos->getVrSalario());
=======
        foreach ($arContratos as $arContrato) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arContrato->getCodigoContratoPk())
                ->setCellValue('B' . $i, $arContrato->getClienteRel()->getNombreCorto())
                ->setCellValue('C' . $i, $arContrato->getClienteRel()->getAsesorRel()->getNombre())
                ->setCellValue('D' . $i, $arContrato->getEmpleadoRel()->getNumeroIdentificacion())
                ->setCellValue('E' . $i, $arContrato->getEmpleadoRel()->getNombreCorto())
                ->setCellValue('F' . $i, $arContrato->getFechaDesde()->format('Y-m-d'))
                ->setCellValue('G' . $i, $arContrato->getFechaHasta()->format('Y-m-d'))
                ->setCellValue('H' . $i, $arContrato->getIndefinido() == 1 ? "SI" : "NO")
                ->setCellValue('I' . $i, $arContrato->getVrSalario());
>>>>>>> 30fa032eaa121a2a644bd8fdcc615fe16e877a7d
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