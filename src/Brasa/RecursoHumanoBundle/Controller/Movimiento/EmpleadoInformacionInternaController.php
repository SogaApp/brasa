<?php

namespace Brasa\RecursoHumanoBundle\Controller\Movimiento;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoInformacionInternaType;

/**
 * BaseEmpleadoInformacionInterna  Controller.
 *
 */
class EmpleadoInformacionInternaController extends Controller
{

    /**
     * @Route("/rhu/empleado/informacion/interna/lista", name="brs_rhu_empleado_informacion_interna_lista")
     */
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest(); // captura o recupera datos del formulario
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 19, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder() //
            ->add('BtnExcel', 'submit', array('label'  => 'Excel'))
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar'))
            ->getForm(); 
        $form->handleRequest($request);
        $arEmpleadoInformacionInterna = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna();
        if($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if(count($arrSeleccionados) > 0) {
                foreach ($arrSeleccionados AS $codigoEmpleadoInformacionInterna) {
                    $arEmpleadoInformacionInterna = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna();
                    $arEmpleadoInformacionInterna = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInterna')->find($codigoEmpleadoInformacionInterna);
                    $em->remove($arEmpleadoInformacionInterna);
                    $em->flush();
                }
            }
              
            if($form->get('BtnExcel')->isClicked()) { 
                $this->generarExcel();
            }
        }
        $arEmpleadoInformacionInterna = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna();
        $query = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInterna')->findAll();
        $arEmpleadoInformacionInterna = $paginator->paginate($query, $this->get('request')->query->get('page', 1),20);

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/EmpleadoInformacionInterna:listar.html.twig', array(
                    'arEmpleadoInformacionInterna' => $arEmpleadoInformacionInterna,
                    'form'=> $form->createView()
           
        ));
    }
    
    /**
     * @Route("/rhu/empleado/informacion/interna/nuevo/{codigoInformacionInterna}", name="brs_rhu_empleado_informacion_interna_nuevo")
     */
    public function nuevoAction($codigoInformacionInterna = 0) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('numeroIdentificacion', 'text', array('required' => true))
            ->add('informacionInternaTipoRel', 'entity', array(
                'class' => 'BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo',
                        'property' => 'nombre',
            ))    
            ->add('fecha', 'date', array('data' => new \DateTime('now')))
            ->add('comentarios', 'textarea', array('required' => true))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arInformacionInterna = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInterna();
            $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findBy(array('numeroIdentificacion' => $form->get('numeroIdentificacion')->getData()));
            if (count($arEmpleado) == 0){
                $objMensaje->Mensaje("error", "No existe el número de identificación", $this);
            }else {
                $arInformacionInterna->setEmpleadoRel($arEmpleado[0]);
                $arEmpleadoFinal = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleadoFinal = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($arEmpleado[0]);
                $intInformacionInternaTipo = $form->get('informacionInternaTipoRel')->getData();
                $arInformacionInternaTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoInformacionInternaTipo();
                $arInformacionInternaTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInternaTipo')->find($intInformacionInternaTipo);
                $arInformacionInterna->setFecha($form->get('fecha')->getData());
                $arInformacionInterna->setComentarios($form->get('comentarios')->getData());
                $arInformacionInterna->setEmpleadoInformacionInternaTipoRel($form->get('informacionInternaTipoRel')->getData());
                $em->persist($arInformacionInterna);
                if ($arInformacionInternaTipo->getAccion() == 1){
                    $arEmpleadoFinal->setEmpleadoInformacionInterna(1); 
                }else{
                    $arEmpleadoFinal->setEmpleadoInformacionInterna(0); 
                }
                $em->persist($arEmpleadoFinal);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_rhu_empleado_informacion_interna_lista'));
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Movimientos/EmpleadoInformacionInterna:nuevo.html.twig', array(

            'form' => $form->createView()));
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'IDENTIFICACIÓN')
                    ->setCellValue('C1', 'EMPLEADO')
                    ->setCellValue('D1', 'INFORMACIÓN INTERNA TIPO')
                    ->setCellValue('E1', 'FECHA')
                    ->setCellValue('F1', 'COMENTARIOS');
        $i = 2;
        $arEmpleadoInformacionInterna = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoInformacionInterna')->findAll();

        foreach ($arEmpleadoInformacionInterna as $arEmpleadoInformacionInterna) {

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleadoInformacionInterna->getCodigoEmpleadoInformacionInternaPk())
                    ->setCellValue('B' . $i, $arEmpleadoInformacionInterna->getEmpleadoRel()->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arEmpleadoInformacionInterna->getEmpleadoRel()->getNombreCorto())
                    ->setCellValue('D' . $i, $arEmpleadoInformacionInterna->getEmpleadoInformacionInternaTipoRel()->getNombre())
                    ->setCellValue('E' . $i, $arEmpleadoInformacionInterna->getFecha()->format('Y-m-d'))
                    ->setCellValue('F' . $i, $arEmpleadoInformacionInterna->getComentarios());
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('InformacionInterna');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="InformacionInterna.xlsx"');
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
