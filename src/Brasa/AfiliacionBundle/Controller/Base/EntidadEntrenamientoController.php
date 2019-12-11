<?php
namespace Brasa\AfiliacionBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Brasa\AfiliacionBundle\Form\Type\AfiEntidadEntrenamientoType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EntidadEntrenamientoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/base/entidad/entrenamiento/", name="brs_afi_base_entidad_entrenamiento")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 123, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamiento')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_entidad_entrenamiento'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arEntidadEntrenamientos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/EntidadEntrenamiento:lista.html.twig', array(
            'arEntidadEntrenamientos' => $arEntidadEntrenamientos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/base/entidad/entrenamiento/nuevo/{codigoEntidadEntrenamiento}", name="brs_afi_base_entidad_entrenamiento_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoEntidadEntrenamiento = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arEntidadEntrenamiento = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento();
        if($codigoEntidadEntrenamiento != '' && $codigoEntidadEntrenamiento != '0') {
            $arEntidadEntrenamiento = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamiento')->find($codigoEntidadEntrenamiento);
        }        
        $form = $this->createForm(new AfiEntidadEntrenamientoType, $arEntidadEntrenamiento);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arEntidadEntrenamiento = $form->getData();                        
            $em->persist($arEntidadEntrenamiento);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_afi_base_entidad_entrenamiento_nuevo', array('codigoEntidadEntrenamiento' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_afi_base_entidad_entrenamiento'));
            }                                   
        }
        return $this->render('BrasaAfiliacionBundle:Base/EntidadEntrenamiento:nuevo.html.twig', array(
            'arEntidadEntrenamiento' => $arEntidadEntrenamiento,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/base/entidad/entrenamiento/detalle/{codigoEntidadEntrenamiento}", name="brs_afi_base_entidad_entrenamiento_detalle")
     */    
    public function detalleAction(Request $request, $codigoEntidadEntrenamiento = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->formularioDetalle();
        $form->handleRequest($request);        
        if ($form->isValid()) {                                                   
            if($form->get('BtnActualizarCosto')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles);                                 
                return $this->redirect($this->generateUrl('brs_afi_base_entidad_entrenamiento_detalle', array('codigoEntidadEntrenamiento' => $codigoEntidadEntrenamiento)));
            }            
            if ($form->get('BtnEliminarCosto')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarCosto');
                $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_base_entidad_entrenamiento_detalle', array('codigoEntidadEntrenamiento' => $codigoEntidadEntrenamiento)));
            }      
            if ($form->get('BtnImprimir')->isClicked()) {
               $objMensaje->Mensaje('error', "Opcion en desarrollo", $this);
            }            
        }
        $arEntidadEntrenamiento = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento();
        $arEntidadEntrenamiento = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamiento')->find($codigoEntidadEntrenamiento);
        $dql = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->listaDetalleDql($codigoEntidadEntrenamiento);        
        $arEntidadEntrenamientoCostos = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/EntidadEntrenamiento:detalle.html.twig', array(
            'arEntidadEntrenamiento' => $arEntidadEntrenamiento,
            'arEntidadEntrenamientoCostos' => $arEntidadEntrenamientoCostos, 
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/afi/base/entidad/entrenamiento/detalle/costo/nuevo/{codigoEntidadEntrenamiento}", name="brs_afi_base_entidad_entrenamiento_detalle_costo_nuevo")
     */    
    public function detalleCostoNuevoAction(Request $request, $codigoEntidadEntrenamiento = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $arEntidadEntrenamiento = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento();
        $arEntidadEntrenamiento = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamiento')->find($codigoEntidadEntrenamiento);
        $form = $this->formularioDetalleCostoNuevo();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                      
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCursoTipo) { 
                    $arEntidadEntrenamientoCostoValidar = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto();
                    $arEntidadEntrenamientoCostoValidar = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->findBy(array('codigoEntidadEntrenamientoFk' => $codigoEntidadEntrenamiento, 'codigoCursoTipoFk' => $codigoCursoTipo));
                    if(!$arEntidadEntrenamientoCostoValidar) {
                        $arCursoTipo = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
                        $arCursoTipo = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->find($codigoCursoTipo);
                        $arEntidadEntrenamientoCosto = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto();
                        $arEntidadEntrenamientoCosto->setEntidadEntrenamientoRel($arEntidadEntrenamiento);          
                        $arEntidadEntrenamientoCosto->setCursoTipoRel($arCursoTipo);
                        $arEntidadEntrenamientoCosto->setCosto(0);
                        $em->persist($arEntidadEntrenamientoCosto);                         
                    }                   
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlCursosTipos = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->listaDql();
        $arCursoTipos = $paginator->paginate($em->createQuery($dqlCursosTipos), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Base/EntidadEntrenamiento:detalleCostoNuevo.html.twig', array(
            'arEntidadEntrenamiento' => $arEntidadEntrenamiento, 
            'arCursoTipos' => $arCursoTipos, 
            'form' => $form->createView()));
    }    
    
    private function lista() {    
        $session = new Session();
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamiento')->listaDQL(
                $session->get('filtroEntidadEntrenamientoNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = new Session();        
        $session->set('filtroEntidadEntrenamientoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new Session();
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroEntidadEntrenamientoNombre')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalle() {        
        $form = $this->createFormBuilder()                                    
            ->add('BtnActualizarCosto', SubmitType::class, array('label'  => 'Actualizar',))                        
            ->add('BtnEliminarCosto', SubmitType::class, array('label'  => 'Eliminar',))                        
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir',))                        
            ->getForm();
        return $form;
    }         
    
    private function formularioDetalleCostoNuevo() {
        $session = new Session();
        $form = $this->createFormBuilder()     
            ->add('TxtNombre', 'text', array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoNombre')))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar',))            
            ->getForm();
        return $form;
    }             
    
    private function generarExcel() {
        ob_clean();
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
        for($col = 'A'; $col !== 'J'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }            
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')                    
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'DIRECCION')
                    ->setCellValue('D1', 'TELEFONO')
                    ->setCellValue('E1', 'CELULAR')
                    ->setCellValue('F1', 'EMAIL')
                    ->setCellValue('G1', 'CONTACTO')
                    ->setCellValue('H1', 'CELCONTACTO')
                    ->setCellValue('I1', 'TELCONTACTO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
        $arEntidadEntrenamientos = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento();
        $arEntidadEntrenamientos = $query->getResult();
                
        foreach ($arEntidadEntrenamientos as $arEntidadEntrenamiento) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEntidadEntrenamiento->getCodigoEntidadEntrenamientoPk())                    
                    ->setCellValue('B' . $i, $arEntidadEntrenamiento->getNombreCorto())
                    ->setCellValue('C' . $i, $arEntidadEntrenamiento->getDireccion())
                    ->setCellValue('D' . $i, $arEntidadEntrenamiento->getTelefono())
                    ->setCellValue('E' . $i, $arEntidadEntrenamiento->getCelular())
                    ->setCellValue('F' . $i, $arEntidadEntrenamiento->getEmail())
                    ->setCellValue('G' . $i, $arEntidadEntrenamiento->getContacto())
                    ->setCellValue('H' . $i, $arEntidadEntrenamiento->getCelularContacto())
                    ->setCellValue('I' . $i, $arEntidadEntrenamiento->getTelefonoContacto());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('EntidadEntrenamiento');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="EntidadEntrenamientos.xlsx"');
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
    
    private function actualizarDetalle($arrControles) {
        $em = $this->getDoctrine()->getManager();        
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arEntidadEntrenamientoCosto = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamientoCosto();
                $arEntidadEntrenamientoCosto = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->find($intCodigo);
                $arEntidadEntrenamientoCosto->setCosto($arrControles['TxtCosto'.$intCodigo]);                             
                $em->persist($arEntidadEntrenamientoCosto);
            }
            $em->flush();                            
        }        
    }        
    
}