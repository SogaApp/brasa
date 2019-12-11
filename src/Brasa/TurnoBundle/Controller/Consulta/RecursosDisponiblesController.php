<?php
namespace Brasa\TurnoBundle\Controller\Consulta;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecursosDisponiblesController extends Controller
{
    var $strListaDql = "";
    var $codigoPedido = "";
    
    /**
     * @Route("/tur/consulta/recursos/disponibles", name="brs_tur_consulta_recursos_disponibles")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 42)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arrDisponibles = array();
        $anio = "";
        $mes = "";
        if ($form->isValid()) {            
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
                $this->lista();
                $dateFecha = $form->get('fecha')->getData();    
                $anio = $dateFecha->format('Y');
                $mes = $dateFecha->format('m');
                $strDia = $dateFecha->format('j');
                $connection = $em->getConnection();                
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strRutaImagen = $arConfiguracion->getRutaImagenes() . "empleados/";                
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('estadoActivo' => 1));
                foreach ($arRecursos as $arRecurso) {
                    $strSql2 = "SELECT  
                                        codigo_programacion_detalle_pk
                                FROM
                                        tur_programacion_detalle   
                                LEFT JOIN tur_turno ON tur_programacion_detalle.dia_$strDia = tur_turno.codigo_turno_pk 
                                WHERE codigo_recurso_fk = " . $arRecurso->getCodigoRecursoPk() . " AND tur_programacion_detalle.dia_$strDia IS NOT NULL  AND anio = $anio AND mes = $mes";                    
                    $statement = $connection->prepare($strSql2);        
                    $statement->execute();
                    $resultados = $statement->fetchAll();
                    if(!$resultados) {
                        $strRutaFoto = "";
                        if($arRecurso->getEmpleadoRel()->getRutaFoto()) { 
                            $strRutaFoto = $strRutaImagen . $arRecurso->getEmpleadoRel()->getRutaFoto();
                        }    
                        $strRecursoTipo = "";
                        if($arRecurso->getCodigoRecursoTipoFk()) {
                            $strRecursoTipo = $arRecurso->getRecursoTipoRel()->getNombre();
                        }                        
                        $arrDisponibles[] = array(
                            'codigoRecursoPk' => $arRecurso->getCodigoRecursoPk(),
                            'numeroIdentificacion' => $arRecurso->getNumeroIdentificacion(),
                            'nombreCorto' => $arRecurso->getNombreCorto(),
                            'tipo' => $strRecursoTipo,
                            'telefono' => $arRecurso->getTelefono(),
                            'celular' => $arRecurso->getCelular(),
                            'rutaFoto' => $strRutaFoto,
                            'nombreTurno' => ''                  
                            );
                    } else {
                        $strSql2 = "SELECT  
                                            codigo_programacion_detalle_pk
                                    FROM
                                            tur_programacion_detalle   
                                    LEFT JOIN tur_turno ON tur_programacion_detalle.dia_$strDia = tur_turno.codigo_turno_pk 
                                    WHERE codigo_recurso_fk = " . $arRecurso->getCodigoRecursoPk() . " AND tur_turno.descanso = 1 AND anio = $anio AND mes = $mes";                    
                        $statement = $connection->prepare($strSql2);        
                        $statement->execute();
                        $resultados = $statement->fetchAll();                        
                        if($resultados) {
                            $strRutaFoto = "";
                            if($arRecurso->getEmpleadoRel()->getRutaFoto()) { 
                                $strRutaFoto = $strRutaImagen . $arRecurso->getEmpleadoRel()->getRutaFoto();
                            }
                            $strRecursoTipo = "";
                            if($arRecurso->getCodigoRecursoTipoFk()) {
                                $strRecursoTipo = $arRecurso->getRecursoTipoRel()->getNombre();
                            }
                            $arrDisponibles[] = array(
                                'codigoRecursoPk' => $arRecurso->getCodigoRecursoPk(),
                                'numeroIdentificacion' => $arRecurso->getNumeroIdentificacion(),
                                'nombreCorto' => $arRecurso->getNombreCorto(),
                                'tipo' => $strRecursoTipo,
                                'telefono' => $arRecurso->getTelefono(),
                                'celular' => $arRecurso->getCelular(),
                                'rutaFoto' => $strRutaFoto,
                                'nombreTurno' => 'DESCANSO'
                                );                            
                        }
                    }
                }
                //$arRecurso =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->disponibles($dateFecha->format('j'), $dateFecha->format('Y'), $dateFecha->format('m'));                
            }
        }
                
        return $this->render('BrasaTurnoBundle:Consultas/Recurso:disponible.html.twig', array(
            'arRecurso' => $arrDisponibles,
            'anio' => $anio,
            'mes' => $mes,
            'form' => $form->createView()));
    }        
        
    /**
     * @Route("/tur/consultas/recursos/disponibles/programacion/{anio}/{mes}/{codigoRecurso}", name="brs_tur_consultas_recursos_disponibles_programacion")
     */         
    public function programacionAction(Request $request, $anio, $mes, $codigoRecurso) {
        $em = $this->getDoctrine()->getManager();        
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso =  $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);                
        $arProgramacionDetalle = new \Brasa\TurnoBundle\Entity\TurProgramacionDetalle();
        $arProgramacionDetalle =  $em->getRepository('BrasaTurnoBundle:TurProgramacionDetalle')->findBy(array('anio' => $anio, 'mes' => $mes, 'codigoRecursoFk' => $codigoRecurso));                
        return $this->render('BrasaTurnoBundle:Consultas/Recurso:disponibleProgramacion.html.twig', array(
            'arProgramacionDetalle' => $arProgramacionDetalle,
            'arRecurso' => $arRecurso));
    }        
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurTurno')->listaDql();
    }

    private function filtrar ($form) {                
        
    }

    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('fecha', DateType::class, array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'CLIENTE');

        $i = 2;
        $query = $em->createQuery($this->strListaDql);
        $arProgramaciones = new \Brasa\TurnoBundle\Entity\TurPedido();
        $arProgramaciones = $query->getResult();

        foreach ($arProgramaciones as $arPedido) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arPedido->getCodigoPedidoPk())
                    ->setCellValue('B' . $i, $arPedido->getTerceroRel()->getNombreCorto());

            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Pedidos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Pedidos.xlsx"');
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