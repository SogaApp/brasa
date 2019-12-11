<?php

namespace Brasa\GeneralBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\GeneralBundle\MisClases\EntityListener;
use Brasa\GeneralBundle\Form\Type\GenDirectorioType;
use Symfony\Component\HttpFoundation\Response;

class LogController extends Controller
{
    private $strDql = "";

    /**
     * @Route("/general/utilidad/log/lista", name="brs_gen_utilidad_log")
     */
    public function listaAction(Request $request){
        $paginator = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $this->generarExcel();
            }
        }


        $arLogs = $paginator->paginate($em->createQuery($this->strDql), $request->query->get('page', 1), 20);

        return $this->render('BrasaGeneralBundle:Utilidades/Log:lista.html.twig', array(
            'arLogs' => $arLogs,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/general/utilidad/log/{codigoRegistro}", name="brs_gen_consulta_log_extendido_")
     */
    public function logDetalle($codigoRegistro)
    {
        $em = $this->getDoctrine()->getManager();
        $detalles = $em->getRepository('BrasaGeneralBundle:GenLogExtendido')->find($codigoRegistro)->getCamposSeguimientoMostrar();
        $detalles = json_decode($detalles, true);
        if (!is_array($detalles)) {
            $detalles = array();
            $detalles['SIN REGISTRAR'] = 'N/A';
        }
        return $this->render('BrasaGeneralBundle:Utilidades/Log:detalles.html.twig', array(
            'detalles' => $detalles
        ));
    }


    private function lista()
    {
        $sesion = new Session();
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";
        $filtrarFecha = $sesion->get('filtroFiltrarFecha');
        if ($filtrarFecha) {
            $strFechaDesde = $sesion->get('FiltroFechaLog');
            $strFechaHasta = $sesion->get('filtroFechaHasta');
        }
        $codigo = $sesion->get("filtroCodigoLog");
        $usuario = $sesion->get("filtroUsuarioRel");
        $accion = $sesion->get("filtroAccionLog");
        $modulo = $sesion->get("filtroModuloLog");
        $filtroHoy = $sesion->get('filtroIngresaronHoy');
        $entidad = $sesion->get('filtroEntidad');
        $this->strDql = $em->getRepository('BrasaGeneralBundle:GenLogExtendido')->listaDql($codigo, $usuario, $accion, $modulo, $strFechaDesde, $filtroHoy,$strFechaHasta,$entidad);
    }

    /**
     * @param $form \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function filtrar($form)
    {
        $session = new Session();
        $usuario = $form->get('UsuarioRel')->getData() ? $form->get('UsuarioRel')->getData()->getId() : null;
        $session->set('filtroCodigoLog', $form->get('TxtCodigo')->getData());
        $session->set('filtroUsuarioRel', $usuario);
        $session->set('filtroAccionLog', $form->get('SelAccion')->getData());
        $session->set('filtroModuloLog', $form->get('TxtModulo')->getData());
        $session->set('filtroIngresaronHoy', $form->get('IngresaronHoy')->getData());
        $dateFechaDesde = $form->get('DtmFecha')->getData();
        $dateFechaHasta = $form->get('DtmFechaHasta')->getData();
        $session->set('FiltroFechaLog', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroFechaHasta', $dateFechaHasta->format('Y/m/d'));
        $session->set('filtroFiltrarFecha', $form->get('filtrarFecha')->getData());
        $session->set('filtroEntidad',$form->get('TxtEntidad')->getData());

    }

    /**
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function formularioFiltro()
    {
        $session = new Session();
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/') . "01";
        $intUltimoDia = $strUltimoDiaMes = date("d", (mktime(0, 0, 0, $dateFecha->format('m') + 1, 1, $dateFecha->format('Y')) - 1));
        $strFechaHasta = $dateFecha->format('Y/m/') . $intUltimoDia;
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder();
        $form
            ->add("UsuarioRel", 'entity', array(
            'class' => "BrasaSeguridadBundle:User",
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder("u")
                    ->orderBy("u.nombreCorto");
            },
            'choice_label' => 'nombreCorto',
            'placeholder' => 'Seleccione un usuario',
        ))
            ->add('SelAccion', 'choice', array(
                'label' => 'Accion', 'data' => $session->get('TxtAccion'),
                'placeholder' => 'Seleccione una accion',
                'choices' => array(
                    EntityListener::ACCION_NUEVO => "CREACION",
                    EntityListener::ACCION_ACTUALIZAR => "ACTUALIZACION",
                    EntityListener::ACCION_ELIMINAR => "ELIMINACION",
                ),
            ))
            ->add('IngresaronHoy', 'checkbox', array('label' => 'Ingresados hoy', 'required' => false))
            ->add("TxtCodigo", 'text', array('label' => 'Código', 'data' => $session->get('TxtCodigo')))
            ->add("TxtModulo", 'text', array('label' => 'Modulo', 'data' => $session->get('TxtModulo')))
            ->add("DtmFecha", 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))
            ->add('DtmFechaHasta', 'date', array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))
            ->add('BtnFiltrar', 'submit', array('label' => 'Filtrar'))
            ->add('BtnExcel', 'submit', array('label' => 'Excel'))
            ->add('filtrarFecha', 'checkbox')
            ->add("TxtEntidad", 'text', array('label' => 'Modulo', 'data' => $session->get('filtroEntidad')));
        return $form->getForm();
    }

    private function generarExcel()
    {
        /**
         * @var $arRegistro GenLogExtendido
         */
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $objPHPExcel = new \PHPExcel();
        $objRichText = new \PHPExcel_RichText();
        $objBold = $objRichText->createTextRun('bold');
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for ($col = 'A'; $col !== 'I'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'USUARIO')
            ->setCellValue('C1', 'ACCIÓN')
            ->setCellValue('D1', 'ENTIDAD')
            ->setCellValue('E1', 'CÓDIGO')
            ->setCellValue('F1', 'MODULO')
            ->setCellValue('G1', 'LOG')
            ->setCellValue('H1', 'FECHA');

        $i = 2;
        $query = $em->createQuery($this->strDql);
        $arRegistros = $query->getResult();

        foreach ($arRegistros as $arRegistro) {
            $logDetalles = '';
            $json = json_decode($arRegistro->getCamposSeguimientoMostrar(), true);
            if (is_array($json)) {
                foreach ($json as $key => $value) {
                    if (is_array($value)) {
                        if (isset($value['date'])) {
                            $value = $value['date'];
                        }
                    }
                    $logDetalles .= strtoupper($key) . ' : ' . $value . "\n";
                }
                $logDetalles .= "\nHAGA CLIC PARA VER LOS DETALLES";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $arRegistro->getCodigoLogExtendidoPk())
                ->setCellValue('B' . $i, $arRegistro->getUsuarioRel()->getNombreCorto())
                ->setCellValue('C' . $i, $arRegistro->getAccion())
                ->setCellValue('D' . $i, $arRegistro->getNombreEntidad())
                ->setCellValue('E' . $i, $arRegistro->getCodigoRegistroPk())
                ->setCellValue('F' . $i, $arRegistro->getModulo())
                ->setCellValue('G' . $i, $logDetalles)
                ->setCellValue('H' . $i, $arRegistro->getFecha());
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('LogExtendido');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="LogExtendido.xlsx"');
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