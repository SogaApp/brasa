<?php
namespace Brasa\TurnoBundle\Controller\Base;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Brasa\TurnoBundle\Form\Type\TurTurnoType;
use Brasa\TurnoBundle\Form\Type\TurTurnoDetalleType;
class TurnoController extends Controller
{
    var $strListaDql = "";
    
    /**
     * @Route("/tur/base/turno/", name="brs_tur_base_turno")
     */     
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurTurno')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_turno'));                                  
            }
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
        
        $arTurnos = $paginator->paginate($em->createQuery($this->strListaDql), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Turno:lista.html.twig', array(
            'arTurnos' => $arTurnos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/turno/nuevo/{codigoTurno}", name="brs_tur_base_turno_nuevo")
     */    
    public function nuevoAction($codigoTurno = '') {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arTurno = new \Brasa\TurnoBundle\Entity\TurTurno();
        if($codigoTurno != '' && $codigoTurno != '0') {
            $arTurno = $em->getRepository('BrasaTurnoBundle:TurTurno')->find($codigoTurno);
        }        
        $form = $this->createForm(new TurTurnoType, $arTurno);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arTurno = $form->getData(); 
            $arUsuario = $this->getUser();
            $arTurno->setUsuario($arUsuario->getUserName());
            $em->persist($arTurno);
            $em->flush();            
            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_turno_nuevo', array('codigoTurno' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_turno'));
            }
        }
        return $this->render('BrasaTurnoBundle:Base/Turno:nuevo.html.twig', array(
            'arTurno' => $arTurno,
            'form' => $form->createView()));
    }         
    
    private function lista() {
        $em = $this->getDoctrine()->getManager();
        $this->strListaDql =  $em->getRepository('BrasaTurnoBundle:TurTurno')->listaDQL();
    }

    private function filtrar ($form) {
        $session = $this->getRequest()->getSession();
        $request = $this->getRequest(); 
    }
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()                        
            ->add('BtnEliminar', 'submit', array('label'  => 'Eliminar',))            
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NOMBRE')
                    ->setCellValue('C1', 'H.DESDE')
                    ->setCellValue('D1', 'H.HASTA')
                    ->setCellValue('E1', 'NOV')
                    ->setCellValue('F1', 'DES')
                    ->setCellValue('G1', 'INC')
                    ->setCellValue('H1', 'LIC')
                    ->setCellValue('I1', 'VAC')
                    ->setCellValue('J1', 'HORAS')
                    ->setCellValue('K1', 'H.NOMINA')
                    ->setCellValue('L1', 'H.DIURNAS')
                    ->setCellValue('M1', 'H.NOCTURNAS');
        $i = 2;
        
        $query = $em->createQuery($this->strListaDql);
        $arTurnos = new \Brasa\TurnoBundle\Entity\TurTurno();
        $arTurnos = $query->getResult();
                
        foreach ($arTurnos as $arTurno) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arTurno->getCodigoTurnoPk())
                    ->setCellValue('B' . $i, $arTurno->getNombre())
                    ->setCellValue('C' . $i, $arTurno->getHoraDesde()->format('H:i'))
                    ->setCellValue('D' . $i, $arTurno->getHoraHasta()->format('H:i'))
                    ->setCellValue('E' . $i, $objFunciones->devuelveBoolean($arTurno->getNovedad()))
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arTurno->getDescanso()))
                    ->setCellValue('G' . $i, $objFunciones->devuelveBoolean($arTurno->getIncapacidad()))
                    ->setCellValue('H' . $i, $objFunciones->devuelveBoolean($arTurno->getLicencia()))
                    ->setCellValue('I' . $i, $objFunciones->devuelveBoolean($arTurno->getVacacion()))
                    ->setCellValue('J' . $i, $arTurno->getHoras())
                    ->setCellValue('K' . $i, $arTurno->getHorasNomina())
                    ->setCellValue('L' . $i, $arTurno->getHorasDiurnas())
                    ->setCellValue('M' . $i, $arTurno->getHorasNocturnas());
                        
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Turnos');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Turnos.xlsx"');
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