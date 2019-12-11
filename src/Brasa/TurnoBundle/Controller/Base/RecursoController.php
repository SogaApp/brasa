<?php
namespace Brasa\TurnoBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Brasa\TurnoBundle\Form\Type\TurRecursoType;

class RecursoController extends Controller
{
    var $strDqlLista = "";
    var $strDqlListaEmpleados = "";
    
    /**
     * @Route("/tur/base/recurso/lista", name="brs_tur_base_recurso_lista")
     */     
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 77, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }        
        $paginator  = $this->get('knp_paginator');
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            if ($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrar($form);
            }
            if ($form->get('BtnActivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigo) {
                        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
                        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigo);
                        if($arRecurso->getEstadoActivo() == 0) {
                            $arRecurso->setEstadoActivo(1);
                            $arRecurso->setEstadoRetiro(0);
                            $em->persist($arRecurso);                            
                        }
                    }
                    $em->flush();
                }    
                return $this->redirect($this->generateUrl('brs_tur_base_recurso_lista'));                
            }            
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $this->generarExcel();
            }
        }
        
        $arRecursos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Recurso:lista.html.twig', array(
            'arRecursos' => $arRecursos, 
            'form' => $form->createView()));
    }
    
    /**
     * @Route("/tur/base/recurso/nuevo/{codigoRecurso}/{codigoEmpleado}", name="brs_tur_base_recurso_nuevo")
     */ 
    public function nuevoAction(Request $request, $codigoRecurso = '', $codigoEmpleado = '') {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        if($codigoRecurso != '' && $codigoRecurso != '0') {
            $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        } else {
            $arRecurso->setEstadoActivo(1);
            if($codigoEmpleado != '' && $codigoEmpleado != '0') {
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);                
                if($arEmpleado) {
                    $arRecursoValidar = $em->getRepository('BrasaTurnoBundle:TurRecurso')->findBy(array('codigoEmpleadoFk' => $arEmpleado->getCodigoEmpleadoPk()));                 
                    if(!$arRecursoValidar) {                        
                        $arRecurso->setEmpleadoRel($arEmpleado);
                        $arRecurso->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                        $arRecurso->setNombreCorto($arEmpleado->getNombreCorto());
                        $arRecurso->setTelefono($arEmpleado->getTelefono());
                        $arRecurso->setCelular($arEmpleado->getCelular());
                        $arRecurso->setDireccion($arEmpleado->getDireccion());
                        $arRecurso->setCorreo($arEmpleado->getCorreo());
                        $arRecurso->setFechaNacimiento($arEmpleado->getFechaNacimiento());
                    }else {
                        $objMensaje->Mensaje("error", "El recurso " . $arEmpleado->getNombreCorto() . " ya existe", $this);
                    }                    
                } 
            }
        }        
        $form = $this->createForm(TurRecursoType::class, $arRecurso);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if($form->get('guardar')->isClicked() || $form->get('guardarnuevo')->isClicked()) {
                $arrControles = $request->request->All();
                $arRecurso = $form->getData(); 
                if($arrControles['txtNumeroIdentificacion'] != '') {
                    $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                    $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                    if(count($arEmpleado) > 0) {  
                        $arRecurso->setCodigoRecursoPk($arEmpleado->getCodigoEmpleadoPk());
                        $arRecurso->setEmpleadoRel($arEmpleado);
                        $arUsuario = $this->getUser();
                        $arRecurso->setUsuario($arUsuario->getUserName());                        
                        $em->persist($arRecurso);
                        $em->flush();            

                        if($form->get('guardarnuevo')->isClicked()) {
                            return $this->redirect($this->generateUrl('brs_tur_base_recurso_nuevo', array('codigoRecurso' => 0, 'codigoEmpleado' => 0 )));
                        } else {
                            return $this->redirect($this->generateUrl('brs_tur_base_recurso_lista'));
                        }                         
                    } else {
                        $objMensaje->Mensaje("error", "El empleado no existe", $this);
                    }
                }                
            } 
            if($form->get('BtnActualizar')->isClicked()) {
                if($codigoRecurso != '' && $codigoRecurso != '0') {
                    $arEmpleado = $arRecurso->getEmpleadoRel();
                    $arRecurso->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                    $arRecurso->setNombreCorto($arEmpleado->getNombreCorto());
                    $arRecurso->setTelefono($arEmpleado->getTelefono());
                    $arRecurso->setCelular($arEmpleado->getCelular());
                    $arRecurso->setDireccion($arEmpleado->getDireccion());
                    $arRecurso->setCorreo($arEmpleado->getCorreo());
                    $arRecurso->setFechaNacimiento($arEmpleado->getFechaNacimiento());                    
                    $em->persist($arRecurso);
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_tur_base_recurso_nuevo', array('codigoRecurso' => $codigoRecurso, 'codigoEmpleado' => 0 )));
                }
            }            
        }
        return $this->render('BrasaTurnoBundle:Base/Recurso:nuevo.html.twig', array(
            'arRecurso' => $arRecurso,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/tur/base/recurso/detalle/{codigoRecurso}", name="brs_tur_base_recurso_detalle")
     */     
    public function detalleAction(Request $request, $codigoRecurso) {
        $em = $this->getDoctrine()->getManager();         
        $objMensaje = $this->get('mensajes_brasa');
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        $form = $this->formularioDetalle($arRecurso);
        $form->handleRequest($request);
        if($form->isValid()) {                              
            if($form->get('BtnImprimir')->isClicked()) {
            }            
        }

        //$arRecursoDetalle = new \Brasa\TurnoBundle\Entity\TurRecursoDetalle();
        //$arRecursoDetalle = $em->getRepository('BrasaTurnoBundle:TurRecursoDetalle')->findBy(array ('codigoProgramacionFk' => $codigoRecurso));
        return $this->render('BrasaTurnoBundle:Base/Recurso:detalle.html.twig', array(
                    'arRecurso' => $arRecurso,                    
                    'form' => $form->createView()
                    ));
    }    
    
    /**
     * @Route("/tur/base/recurso/nuevo/enlazar/", name="brs_tur_base_recurso_nuevo_enlazar")
     */     
    public function enlazarAction(Request $request) {
        $session = new session;      
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioEnlazar();
        $form->handleRequest($request);        
        $this->listaEnlazar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {     
                $this->filtrarEnlazar($form);
                $this->listaEnlazar();                                
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strDqlListaEmpleados), $request->query->get('page', 1), 20);
        return $this->render('BrasaTurnoBundle:Base/Recurso:enlazar.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()));
    }    
    
    /**
     * @Route("/tur/base/recurso/retiro/{codigoRecurso}", name="brs_tur_base_recurso_retiro")
     */    
    public function retiroAction(Request $request, $codigoRecurso) {        
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arRecurso = new \Brasa\TurnoBundle\Entity\TurRecurso();
        $arRecurso = $em->getRepository('BrasaTurnoBundle:TurRecurso')->find($codigoRecurso);
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_tur_base_recurso_retiro', array('codigoRecurso' => $codigoRecurso)))
            ->add('fechaTerminacion', DateType::class, array('label'  => 'Terminacion', 'data' => new \DateTime('now')))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);    
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $dateFechaRetiro = $form->get('fechaTerminacion')->getData();
            $arRecurso->setFechaRetiro($dateFechaRetiro);
            $arRecurso->setEstadoRetiro(1);
            $arRecurso->setEstadoActivo(0);
            $em->persist($arRecurso);                            
            $em->flush();           
            return $this->redirect($this->generateUrl('brs_tur_base_recurso_lista'));
        }
        return $this->render('BrasaTurnoBundle:Base/Recurso:retiro.html.twig', array(
            'arRecurso' => $arRecurso,
            'form' => $form->createView()
        ));
    }
    
    private function lista() {        
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strDqlLista = $em->getRepository('BrasaTurnoBundle:TurRecurso')->listaDQL(
                $session->get('filtroNombreRecurso'),                
                $session->get('filtroCodigoRecurso'),
                "",
                $session->get('filtroIdentificacionRecurso'),
                $session->get('filtroCodigoRecursoGrupo'),
                $session->get('filtroRecursoEstadoRetirado')                
                ); 
    }
    
    private function listaEnlazar () {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strDqlListaEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->ListaRecursoDql(
                        $session->get('filtroEmpleadoNombre'), 
                        $session->get('filtroCodigoCentroCosto'), 
                        "",
                        $session->get('filtroIdentificacion'), "", "");
    }
    
    private function filtrar ($form) {    
        $session = new session;
        $session->set('filtroCodigoRecurso', $form->get('TxtCodigo')->getData());
        $session->set('filtroNombreRecurso', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacionRecurso', $form->get('TxtNumeroIdentificacion')->getData());                
        $arRecursoGrupo = $form->get('recursoGrupoRel')->getData();
        if($arRecursoGrupo) {
            $session->set('filtroCodigoRecursoGrupo', $arRecursoGrupo->getCodigoRecursoGrupoPk());
        }        
        $session->set('filtroRecursoEstadoRetirado', $form->get('estadoRetirado')->getData());
        $this->lista();
    }
    
    private function filtrarEnlazar ($form) {    
        $session = new session;     
        $arCentroCosto = $form->get('centroCostoRel')->getData();
        if($arCentroCosto) {            
            $session->set('filtroCodigoCentroCosto', $arCentroCosto->getCodigoCentroCostoPk());
        }
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombreCorto')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());        
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
    $session = new Session;  
        $arrayPropiedadesRecursoGrupo = array(
                'class' => 'BrasaTurnoBundle:TurRecursoGrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rg')
                    ->orderBy('rg.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );
        if($session->get('filtroCodigoRecursoGrupo')) {
            $arrayPropiedadesRecursoGrupo['data'] = $em->getReference("BrasaTurnoBundle:TurRecursoGrupo", $session->get('filtroCodigoRecursoGrupo'));
        }        
        $form = $this->createFormBuilder()            
            ->add('recursoGrupoRel', EntityType::class, $arrayPropiedadesRecursoGrupo)
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombreRecurso')))
            ->add('TxtCodigo', TextType::class, array('label'  => 'Codigo','data' => $session->get('filtroCodigoRecurso')))                  
            ->add('TxtNumeroIdentificacion', TextType::class, array('label'  => 'NumeroIdentificacion','data' => $session->get('filtroIdentificacionRecurso')))                                        
            ->add('estadoRetirado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'RETIRADO', '0' => 'SIN RETIRAR'), 'data' => $session->get('filtroRecursoEstadoRetirado')))                
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnActivar', SubmitType::class, array('label'  => 'Activar'))
            ->getForm();
        return $form;
    }
    
    private function formularioDetalle($ar) {
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);        
        $form = $this->createFormBuilder()    
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)                       
                    ->getForm();  
        return $form;
    }

    private function formularioEnlazar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $arrayPropiedades = array(
                'class' => 'BrasaRecursoHumanoBundle:RhuCentroCosto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                    ->orderBy('cc.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => false,
                'empty_data' => "",
                'placeholder' => "TODOS",
                'data' => ""
            );        
        if($session->get('filtroCodigoCentroCosto')) {
            $arrayPropiedades['data'] = $em->getReference("BrasaRecursoHumanoBundle:RhuCentroCosto", $session->get('filtroCodigoCentroCosto'));
        }        
        $form = $this->createFormBuilder()
            ->add('centroCostoRel', EntityType::class, $arrayPropiedades)   
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionSeleccion')))
            ->add('TxtNombreCorto', TextType::class, array('label'  => 'Nombre'))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }
    
    private function generarExcel() {
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
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
        for($col = 'A'; $col !== 'H'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }
        
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'IDENTIFICACION')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'TIPO')                    
                    ->setCellValue('E1', 'GRUPO')
                    ->setCellValue('F1', 'ACTIVO')
                    ->setCellValue('G1', 'RETIRO')
                    ->setCellValue('H1', 'F.RETIRO');

        $i = 2;
        
        $query = $em->createQuery($this->strDqlLista);
                $arRecursos = new \Brasa\TurnoBundle\Entity\TurRecurso();
                $arRecursos = $query->getResult();
                
        foreach ($arRecursos as $arRecurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arRecurso->getCodigoRecursoPk())
                    ->setCellValue('B' . $i, $arRecurso->getNumeroIdentificacion())
                    ->setCellValue('C' . $i, $arRecurso->getNombreCorto())                                                            
                    ->setCellValue('F' . $i, $objFunciones->devuelveBoolean($arRecurso->getEstadoActivo()))
                    ->setCellValue('G' . $i, $objFunciones->devuelveBoolean($arRecurso->getEstadoRetiro()));

            if($arRecurso->getCodigoRecursoTipoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $arRecurso->getRecursoTipoRel()->getNombre());
            }
            if($arRecurso->getCodigoRecursoGrupoFk()) {
               $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $arRecurso->getRecursoGrupoRel()->getNombre()); 
            }            
            if($arRecurso->getFechaRetiro()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arRecurso->getFechaRetiro()->format('Y/m/d'));
            }            
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Recurso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Recursos.xlsx"');
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