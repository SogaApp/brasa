<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Programacion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DescargaMasivaController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/programacion/descarga/masiva", name="brs_tur_utilidad_programacion_descarga_masiva")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 88)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnGenerar')->isClicked()) {                
                $dateFechaDesde = $form->get('fechaDesde')->getData();
                $dateFechaHasta = $form->get('fechaHasta')->getData();
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strRutaGeneral = $arConfiguracion->getRutaTemporal();
                if(!file_exists($strRutaGeneral)) {
                    mkdir($strRutaGeneral, 0777);
                }           
                $arProgramaciones = new \Brasa\TurnoBundle\Entity\TurProgramacion();
                $strDql = $em->getRepository('BrasaTurnoBundle:TurProgramacion')->listaDql("", "", "", $dateFechaDesde->format('Y/m/d'), $dateFechaHasta->format('Y/m/d'));
                $query = $em->createQuery($strDql);
                $arProgramaciones = $query->getResult();
                $strRuta = $strRutaGeneral . "ProgramacionesTurno/";
                if(!file_exists($strRuta)) {
                    mkdir($strRuta, 0777);                    
                }
                $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
                $codigoFormato = $arConfiguracion->getCodigoFormatoProgramacion();                
                foreach ($arProgramaciones as $arProgramacion) {   
                    if($codigoFormato <= 1) {
                        $objProgramacion = new \Brasa\TurnoBundle\Formatos\Programacion1();
                        $objProgramacion->Generar($this, $arProgramacion->getCodigoProgramacionPk(), $strRuta);                    
                    }
                    if($codigoFormato == 2) {
                        $objProgramacion = new \Brasa\TurnoBundle\Formatos\Programacion2();
                        $objProgramacion->Generar($this, $arProgramacion->getCodigoProgramacionPk(), $strRuta);                    
                    }                     
                }            
                $strRutaZip = $strRutaGeneral . 'ProgramacionesTurno.zip';
                $this->comprimir($strRuta, $strRutaZip);                                                
                $dir = opendir($strRuta);                
                while ($current = readdir($dir)){
                    if( $current != "." && $current != "..") {
                        unlink($strRuta . $current);
                    }                    
                } 
                rmdir($strRuta);
                
                $strArchivo = $strRutaZip;
                header('Content-Description: File Transfer');
                header('Content-Type: text/csv; charset=ISO-8859-15');
                header('Content-Disposition: attachment; filename='.basename($strArchivo));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($strArchivo));
                readfile($strArchivo);                               
                unlink($strRutaZip);                                                                
            }            
        }                    
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:descargaMasiva.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                        
            ->add('fechaDesde', DateType::class, array('data' => new \DateTime('now'), 'format' => 'yyyyMMdd'))                
            ->add('fechaHasta', DateType::class, array('data' => new \DateTime('now'), 'format' => 'yyyyMMdd'))                
            ->add('BtnGenerar', SubmitType::class, array('label'  => 'Generar'))    
            ->getForm();        
        return $form;
    }                 
    
    function comprimir($ruta, $zip_salida, $handle = false, $recursivo = false, $archivo = "") {

        /* Declara el handle del objeto */
        if (!$handle) {
            $handle = new \ZipArchive();
            if ($handle->open($zip_salida, ZipArchive::CREATE) === false) {
                return false; /* Imposible crear el archivo ZIP */
            }
        }

        /* Procesa directorio */
        if (is_dir($ruta)) {
            /* Aseguramos que sea un directorio sin carácteres corruptos */
            $ruta = dirname($ruta . '/arch.ext');
            $handle->addEmptyDir($ruta); /* Agrega el directorio comprimido */            
            $dir = opendir($ruta);            
            while ($current = readdir($dir)){
                if( $current != "." && $current != "..") {
                    $this->comprimir($ruta . "/" . $current, $zip_salida, $handle, true, $current); /* Comprime el subdirectorio o archivo */
                }
            }            
            //foreach (glob($ruta . '/*') as $url) { /* Procesa cada directorio o archivo dentro de el */
                //$this->comprimir($url, $zip_salida, $handle, true); /* Comprime el subdirectorio o archivo */
            //}

            /* Procesa archivo */
        } else {
            $handle->addFile($ruta, $archivo);
        }

        /* Finaliza el ZIP si no se está ejecutando una acción recursiva en progreso */
        if (!$recursivo) {
            $handle->close();
        }

        return true; /* Retorno satisfactorio */
    }

}
