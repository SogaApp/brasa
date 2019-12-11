<?php
namespace Brasa\RecursoHumanoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class PermisosController extends Controller
{
    var $strListaDql = "";
    var $nombre = "";
    var $identificacion = "";
    var $centroCosto = "";
    var $cargo = "";
    var $departamentoEmpresa = "";
    var $afectaHorario = "";
    var $fechaDesde = "";
    var $fechaHasta = "";
    
    /**
     * @Route("/rhu/consultas/permisos", name="brs_rhu_consultas_permisos")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 34)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }         
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
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

        $arPermisos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 40);
        return $this->render('BrasaRecursoHumanoBundle:Consultas/Permisos:lista.html.twig', array(
            'arPermisos' => $arPermisos,
            'form' => $form->createView()));
    }

    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaRecursoHumanoBundle:RhuPermiso')->permisosAutorizados(
            $this->nombre,
            $this->identificacion,
            $this->centroCosto,
            $this->cargo,
            $this->departamentoEmpresa,
            $this->afectaHorario,
            $this->fechaDesde,    
            $this->fechaDesde);    
    }

    private function filtrar ($form) {
        $arDepartamentoEmpresa = $form->get('departamentoEmpresaRel')->getData();
        if ($arDepartamentoEmpresa == null){
            $intDepartamentoEmpresa = "";
        }else {
            $intDepartamentoEmpresa = $arDepartamentoEmpresa->getCodigoDepartamentoEmpresaPk();
        }
        $arCentroCosto = $form->get('centroCostoRel')->getData();
        if ($arCentroCosto == null){
            $intCentroCosto = "";
        }else {
            $intCentroCosto = $arCentroCosto->getCodigoCentroCostoPk();
        }
        $arCargo = $form->get('cargoRel')->getData();
        if ($arCargo == null){
            $intCargo = "";
        }else {
            $intCargo = $arCargo->getCodigoCargoPk();
        }
        $this->nombre = $form->get('TxtNombre')->getData();
        $this->identificacion = $form->get('TxtIdentificacion')->getData();
        $this->centroCosto = $intCentroCosto;
        $this->cargo = $intCargo;
        $this->departamentoEmpresa = $intDepartamentoEmpresa;
        $this->afectaHorario = $form->get('afectaHorario')->getData();
        $this->fechaDesde = $form->get('fechaDesde')->getData();
        $this->fechaHasta = $form->get('fechaHasta')->getData();
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();            
        $form = $this->createFormBuilder()
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $this->nombre))
            ->add('TxtIdentificacion', 'text', array('label'  => 'Identificaciín','data' => $this->identificacion))
            ->add('centroCostoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('cargoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => ""))
            ->add('departamentoEmpresaRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuDepartamentoEmpresa',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('de')
                    ->orderBy('de.nombre', 'ASC');},
                'property' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'empty_value' => "TODOS",
                'data' => "")) 
             ->add('afectaHorario', 'choice', array('choices' => array('2' => 'TODOS', '0' => 'NO', '1' => 'SI')))
            ->add('fechaDesde','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta','date',array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))                
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
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
                $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
                $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
                for($col = 'A'; $col !== 'AR'; $col++) {
                    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
                    $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
                }
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A1', 'CÓDIGO')
                            ->setCellValue('B1', 'FECHA')
                            ->setCellValue('C1', 'CENTRO COSTOS')
                            ->setCellValue('D1', 'IDENTIFICACIÓN')
                            ->setCellValue('E1', 'EMPLEADO')
                            ->setCellValue('F1', 'CARGO')
                            ->setCellValue('G1', 'DEPARTAMENTO EMPRESA')
                            ->setCellValue('H1', 'JEFE PERMISO')
                            ->setCellValue('I1', 'TIPO PERMISO')
                            ->setCellValue('J1', 'MOTIVO')
                            ->setCellValue('K1', 'HORA SALIDA')
                            ->setCellValue('L1', 'HORA LLEGADA')
                            ->setCellValue('M1', 'HORAS')
                            ->setCellValue('N1', 'AFECTA HORARIO')
                            ->setCellValue('O1', 'AUTORIZADO')
                            ->setCellValue('P1', 'OBSERVACIONES');

                $i = 2;
                $query = $em->createQuery($this->strListaDql);
                $arPermisos = new \Brasa\RecursoHumanoBundle\Entity\RhuPermiso();
                $arPermisos = $query->getResult();

                foreach ($arPermisos as $arPermisos) {
                    $centroCosto = "";
                    if ($arPermisos->getCodigoCentroCostoFk() != null){
                        $centroCosto = $arPermisos->getCentroCostoRel()->getNombre();
                    }
                    $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $arPermisos->getCodigoPermisoPk())
                            ->setCellValue('B' . $i, $arPermisos->getFechaPermiso()->format('Y/m/d'))
                            ->setCellValue('C' . $i, $centroCosto)
                            ->setCellValue('D' . $i, $arPermisos->getEmpleadoRel()->getNumeroIdentificacion())
                            ->setCellValue('E' . $i, $arPermisos->getEmpleadoRel()->getNombreCorto())
                            ->setCellValue('F' . $i, $arPermisos->getCargoRel()->getNombre())
                            ->setCellValue('G' . $i, $arPermisos->getDepartamentoEmpresaRel()->getNombre())
                            ->setCellValue('H' . $i, $arPermisos->getJefeAutoriza())
                            ->setCellValue('I' . $i, $arPermisos->getPermisoTipoRel()->getNombre())
                            ->setCellValue('J' . $i, $arPermisos->getMotivo())
                            ->setCellValue('K' . $i, $arPermisos->getHoraSalida()->format('H:i'))
                            ->setCellValue('L' . $i, $arPermisos->getHoraLlegada()->format('H:i'))
                            ->setCellValue('M' . $i, $arPermisos->getHorasPermiso())
                            ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arPermisos->getAfectaHorario()))
                            ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arPermisos->getEstadoAutorizado()))
                            ->setCellValue('P' . $i, $arPermisos->getObservaciones());
                    $i++;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Permisos');
                $objPHPExcel->setActiveSheetIndex(0);

                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Permisos.xlsx"');
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