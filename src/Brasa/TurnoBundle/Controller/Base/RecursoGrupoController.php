<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurRecursoGrupoType;

class RecursoGrupoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/tur/base/recurso/grupo", name="brs_tur_base_recurso_grupo")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 79, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaTurnoBundle:TurRecursoGrupo')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_tur_base_recurso_grupo'));
            }
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arRecursoGrupos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/RecursoGrupo:lista.html.twig', array(
            'arRecursoGrupos' => $arRecursoGrupos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/tur/base/recurso/grupo/nuevo/{codigoRecursoGrupo}", name="brs_tur_base_recurso_grupo_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoRecursoGrupo = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRecursoGrupo = new \Brasa\TurnoBundle\Entity\TurRecursoGrupo();
        if($codigoRecursoGrupo != '' && $codigoRecursoGrupo != '0') {
            $arRecursoGrupo = $em->getRepository('BrasaTurnoBundle:TurRecursoGrupo')->find($codigoRecursoGrupo);
        }        
        $form = $this->createForm(TurRecursoGrupoType::class, $arRecursoGrupo);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arRecursoGrupo = $form->getData();                        
            $em->persist($arRecursoGrupo);
            $em->flush();            
            if($form->get('guardarnuevo')->isClicked()) {
                return $this->redirect($this->generateUrl('brs_tur_base_recurso_grupo_nuevo', array('codigoRecursoGrupo' => 0 )));
            } else {
                return $this->redirect($this->generateUrl('brs_tur_base_recurso_grupo'));
            }                                   
        }
        return $this->render('BrasaTurnoBundle:Base/RecursoGrupo:nuevo.html.twig', array(
            'arRecursoGrupo' => $arRecursoGrupo,
            'form' => $form->createView()));
    }        

    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurRecursoGrupo')->listaDQL(
                $session->get('filtroRecursoGrupoNombre')   
                ); 
    }

    private function filtrar ($form) {        
        $session = new session;     
        $session->set('filtroRecursoGrupoNombre', $form->get('TxtNombre')->getData());
        $this->lista();
    }
    
    private function formularioFiltro() {
        $session = new session;
        $form = $this->createFormBuilder()            
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroRecursoGrupoNombre')))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    

    private function generarExcel() {
        ob_clean();
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
                    ->setCellValue('B1', 'NIT')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'ESTRATO')
                    ->setCellValue('E1', 'CONTACTO')
                    ->setCellValue('F1', 'TELEFONO')
                    ->setCellValue('G1', 'CELULAR')
                    ->setCellValue('H1', 'DIRECCION')
                    ->setCellValue('I1', 'BARRIO')
                    ->setCellValue('J1', 'CIUDAD')
                    ->setCellValue('K1', 'FORMA PAGO')
                    ->setCellValue('L1', 'PLAZO PAGO')
                    ->setCellValue('M1', 'FINANCIERO')
                    ->setCellValue('N1', 'CELULAR FINANCIERO')
                    ->setCellValue('O1', 'GERENTE')
                    ->setCellValue('P1', 'CELULAR GERENTE');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arRecursoGrupos = new \Brasa\TurnoBundle\Entity\TurRecursoGrupo();
                $arRecursoGrupos = $query->getResult();
                
        foreach ($arRecursoGrupos as $arRecursoGrupo) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecursoGrupo->getCodigoRecursoGrupoPk())
                    ->setCellValue('B' . $i, $arRecursoGrupo->getNit())
                    ->setCellValue('C' . $i, $arRecursoGrupo->getNombreCorto())
                    ->setCellValue('D' . $i, $arRecursoGrupo->getEstrato())
                    ->setCellValue('E' . $i, $arRecursoGrupo->getContacto())
                    ->setCellValue('F' . $i, $arRecursoGrupo->getTelefonoContacto())
                    ->setCellValue('G' . $i, $arRecursoGrupo->getCelularContacto())
                    ->setCellValue('H' . $i, $arRecursoGrupo->getDireccion())
                    ->setCellValue('I' . $i, $arRecursoGrupo->getBarrio())
                    ->setCellValue('J' . $i, $arRecursoGrupo->getCiudadRel()->getNombre())
                    ->setCellValue('K' . $i, $arRecursoGrupo->getFormaPagoRel()->getNombre())
                    ->setCellValue('L' . $i, $arRecursoGrupo->getPlazoPago())
                    ->setCellValue('M' . $i, $arRecursoGrupo->getFinanciero())
                    ->setCellValue('N' . $i, $arRecursoGrupo->getCelularFinanciero())
                    ->setCellValue('O' . $i, $arRecursoGrupo->getGerente())
                    ->setCellValue('P' . $i, $arRecursoGrupo->getCelularGerente());                                    
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('RecursoGrupo');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="RecursoGrupos.xlsx"');
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