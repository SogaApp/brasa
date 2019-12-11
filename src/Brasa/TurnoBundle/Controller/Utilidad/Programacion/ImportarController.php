<?php

namespace Brasa\TurnoBundle\Controller\Utilidad\Programacion;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ImportarController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/utilidad/programacion/importar", name="brs_tur_utilidad_programacion_importar")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 87)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');        
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $arUsuario = $this->get('security.context')->getToken()->getUser();
                set_time_limit(0);
                ini_set("memory_limit", -1);                
                $error = "";
                
                $form['attachment']->getData()->move($arConfiguracion->getRutaTemporal(), "archivo.xls");                
                $ruta = $arConfiguracion->getRutaTemporal(). "archivo.xls";                
                $arrCargas = array();
                
                $objPHPExcel = \PHPExcel_IOFactory::load($ruta);                
                //Cargar informacion
                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle     = $worksheet->getTitle();
                    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
                    $nrColumns = ord($highestColumn) - 64;
                    for ($row = 2; $row <= $highestRow; ++ $row) {                        
                        $cell = $worksheet->getCellByColumnAndRow(0, $row);
                        $codigoRecurso = $cell->getValue();                        
                        $arrTemporal = array('codigoRecurso' => $codigoRecurso);                                                
                        for($i=1;$i<=31;$i++) {
                            $cell = $worksheet->getCellByColumnAndRow($i, $row);
                            $turno = $cell->getValue(); 
                            $arrTemporal[$i] = $turno;                            
                        }   
                        $arrCargas[] = $arrTemporal;
                    }
                }
                
                //Validar
                foreach ($arrCargas as $arrCarga) {
                    for($i=1; $i<=31; $i++) {
                        if(!$this->validarTurno($arrCarga[$i])) {
                            $error . "El turno " . $arrCarga[$i] . " no existe";
                        }
                    }                    
                }
                
                if($error != "") {
                    $objMensaje->Mensaje('error', "Error al cargar:" . $error, $this);
                } else {
                    foreach ($arrCargas as $arrCarga) {
                        $arProgramacionImportar = new \Brasa\TurnoBundle\Entity\TurProgramacionImportar();
                        $arProgramacionImportar->setDia1($arrCarga[1]);
                        $arProgramacionImportar->setDia2($arrCarga[2]);
                        $arProgramacionImportar->setDia3($arrCarga[3]);
                        $arProgramacionImportar->setDia4($arrCarga[4]);
                        $arProgramacionImportar->setDia5($arrCarga[5]);
                        $arProgramacionImportar->setDia6($arrCarga[6]);
                        $arProgramacionImportar->setDia7($arrCarga[7]);
                        $arProgramacionImportar->setDia8($arrCarga[8]);
                        $arProgramacionImportar->setDia9($arrCarga[9]);
                        $arProgramacionImportar->setDia10($arrCarga[10]);
                        $arProgramacionImportar->setDia11($arrCarga[11]);
                        $arProgramacionImportar->setDia12($arrCarga[12]);
                        $arProgramacionImportar->setDia13($arrCarga[13]);
                        $arProgramacionImportar->setDia14($arrCarga[14]);
                        $arProgramacionImportar->setDia15($arrCarga[15]);
                        $arProgramacionImportar->setDia16($arrCarga[16]);
                        $arProgramacionImportar->setDia17($arrCarga[17]);
                        $arProgramacionImportar->setDia18($arrCarga[18]);
                        $arProgramacionImportar->setDia19($arrCarga[19]);
                        $arProgramacionImportar->setDia20($arrCarga[20]);
                        $arProgramacionImportar->setDia21($arrCarga[21]);
                        $arProgramacionImportar->setDia22($arrCarga[22]);
                        $arProgramacionImportar->setDia23($arrCarga[23]);
                        $arProgramacionImportar->setDia24($arrCarga[24]);
                        $arProgramacionImportar->setDia25($arrCarga[25]);
                        $arProgramacionImportar->setDia26($arrCarga[26]);
                        $arProgramacionImportar->setDia27($arrCarga[27]);
                        $arProgramacionImportar->setDia28($arrCarga[28]);
                        $arProgramacionImportar->setDia29($arrCarga[29]);
                        $arProgramacionImportar->setDia30($arrCarga[30]);
                        $arProgramacionImportar->setDia31($arrCarga[31]);
                        $em->persist($arProgramacionImportar);
                    }                    
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                
                }                                
            } 
        }                 
        $dql = $em->getRepository('BrasaTurnoBundle:TurProgramacionImportar')->listaDql();
        $arProgramacionesImportar = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 200);
        return $this->render('BrasaTurnoBundle:Utilidades/Programaciones:importar.html.twig', array(            
            'arProgramacionesImportar' => $arProgramacionesImportar,
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {                

        $form = $this->createFormBuilder()                        
            ->add('attachment', FileType::class)
            ->add('BtnCargar', SubmitType::class, array('label'  => 'Cargar'))   
            ->getForm();        
        return $form;
    }        

    private function validarTurno() {
        
        return true;
    }
}
