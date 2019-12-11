<?php
namespace Brasa\AfiliacionBundle\Controller\Movimiento;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Brasa\AfiliacionBundle\Form\Type\AfiCursoType;
class CursoController extends Controller
{
    var $strDqlLista = "";
    /**
     * @Route("/afi/movimiento/curso", name="brs_afi_movimiento_curso")
     */    
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $form = $this->formularioFiltro();
        $form->handleRequest($request);
        $this->lista();
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            
            if($request->request->get('OpGenerar')) {            
                $codigoCurso = $request->request->get('OpGenerar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->generar($codigoCurso);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }
            
            if($request->request->get('OpDeshacer')) {            
                $codigoCurso = $request->request->get('OpDeshacer');
                $strSql = "DELETE FROM afi_curso_detalle WHERE codigo_curso_fk = " . $codigoCurso;           
                $em->getConnection()->executeQuery($strSql);                 
                $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);                
                $arCurso->setEstadoGenerado(0);
                $em->persist($arCurso);
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }            
            if ($form->get('BtnEliminar')->isClicked()) {                
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 4)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->eliminar($arrSeleccionados);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }
            if ($form->get('BtnAsistencia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCurso) {
                    $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                    $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
                    if($arCurso->getAsistencia() == 1) {
                        $arCurso->setAsistencia(0);
                    }  else {
                        $arCurso->setAsistencia(1);
                    }
                    $em->persist($arCurso);                    
                }                
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            } 
            if ($form->get('BtnCertificado')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCurso) {
                    $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
                    $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
                    if($arCurso->getCertificado() == 1) {
                        $arCurso->setCertificado(0);
                    }  else {
                        $arCurso->setCertificado(1);
                    }
                    $em->persist($arCurso);                    
                }                
                $em->flush();
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
            }            
            if ($form->get('BtnFiltrar')->isClicked()) {                
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();                
            }
            if ($form->get('BtnExcel')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();
                $this->lista();
                $this->generarExcel();
            }
            if ($form->get('BtnExcelDetalle')->isClicked()) {
                $this->filtrar($form);
                $form = $this->formularioFiltro();                
                $this->generarExcelDetallado();
            }            
        }
        
        $arCursos = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:lista.html.twig', array(
            'arCursos' => $arCursos, 
            'form' => $form->createView()));
    }

    /**
     * @Route("/afi/movimiento/curso/nuevo/{codigoCurso}", name="brs_afi_movimiento_curso_nuevo")
     */    
    public function nuevoAction(Request $request, $codigoCurso = '') {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        if($codigoCurso != '' && $codigoCurso != '0') {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 3)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
        } else {
            if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 2)) {
                return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
            }
            $fecha = date('Y-m-d');
            $nuevafecha = strtotime ( '+360 day' , strtotime ( $fecha ) ) ;
            $nuevafecha = date ('Y-m-d', $nuevafecha ); 
            $nuevafecha = date_create($nuevafecha);
            $arCurso->setFechaVence($nuevafecha);
            $arCurso->setFechaProgramacion(new \DateTime('now'));
        }        
        $form = $this->createForm(new AfiCursoType, $arCurso);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arCurso = $form->getData();  
            $arrControles = $request->request->All();
            if($arrControles['txtNit'] != '') {
                $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $arrControles['txtNit']));                
                if(count($arCliente) > 0) {
                    $arCurso->setClienteRel($arCliente);                    
                    if($arrControles['txtNumeroIdentificacion'] != '') {
                        $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
                        $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));                                        
                        if(count($arEmpleado) > 0) {                                                    
                            $arCurso->setEmpleadoRel($arEmpleado);
                            $arCurso->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                            $arCurso->setNombreCorto($arEmpleado->getNombreCorto());                            
                        }
                    }
                    if($arCurso->getNumeroIdentificacion() == "") {
                        $arCurso->setNumeroIdentificacion($arCliente->getNit());
                        $arCurso->setNombreCorto($arCliente->getNombreCorto());                          
                    }
                    $arCurso->setFecha(new \DateTime('now'));  
                    $em->persist($arCurso);
                    $em->flush();            
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_nuevo', array('codigoCurso' => 0 )));
                    } else {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $arCurso->getCodigoCursoPk())));
                    }                    
                } else {
                    $objMensaje->Mensaje("error", "El cliente no existe", $this);
                }                                                 
            }                                              
        }
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:nuevo.html.twig', array(
            'arCurso' => $arCurso,
            'form' => $form->createView()));
    }        

    /**
     * @Route("/afi/movimiento/curso/nuevo/cliente/", name="brs_afi_movimiento_curso_nuevo_cliente")
     */    
    public function nuevoClienteAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();    
        $form = $this->formularioNuevoCliente();                
        $form->handleRequest($request);
        $this->listaNuevoCliente();
        if ($form->isValid()) {
            if ($form->get('BtnFiltrar')->isClicked()) {                
                $this->filtrarNuevoCliente($form);
                $form = $this->formularioNuevoCliente();
                $this->listaNuevoCliente();                
            }            
            if ($form->get('BtnGuardar')->isClicked()) { 
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if($arrSeleccionados) {
                    $nit = $form->get('TxtNit')->getData();
                    $arrControles = $request->request->All();
                    if($nit != '') {
                        $arCliente = new \Brasa\AfiliacionBundle\Entity\AfiCliente();
                        $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $nit));                
                        if(count($arCliente) > 0) {
                            $arEntidadEntrenamiento = new \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento();
                            $arEntidadEntrenamiento = $form->get('entidadEntrenamientoRel')->getData();
                            $arCursoTipo = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
                            $arCursoTipo = $form->get('cursoTipoRel')->getData();
                            $fechaVence = $form->get('fechaVence')->getData();
                            $fechaProgramacion = $form->get('fechaProgramacion')->getData();
                            foreach ($arrSeleccionados as $codigoEmpleado) {
                                $arEmpleado = new \Brasa\AfiliacionBundle\Entity\AfiEmpleado();
                                $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->find($codigoEmpleado);
                                
                                $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();                        
                                $arCurso->setClienteRel($arCliente);                    
                                $arCurso->setEmpleadoRel($arEmpleado);
                                $arCurso->setEntidadEntrenamientoRel($arEntidadEntrenamiento);                                
                                $arCurso->setNumeroIdentificacion($arEmpleado->getNumeroIdentificacion());
                                $arCurso->setNombreCorto($arEmpleado->getNombreCorto());                          
                                $arCurso->setFecha(new \DateTime('now'));  
                                $arCurso->setFechaVence($fechaVence);
                                $arCurso->setFechaProgramacion($fechaProgramacion);
                                $em->persist($arCurso);   
                                
                                $arCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
                                $arCursoDetalle->setCursoRel($arCurso);
                                $arCursoDetalle->setCursoTipoRel($arCursoTipo);
                                $costo = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->costoCursoEntidadEntrenamiento($arEntidadEntrenamiento->getCodigoEntidadEntrenamientoPk(), $arCursoTipo->getCodigoCursoTipoPk());
                                $arCursoDetalle->setCosto($costo);
                                $arCursoDetalle->setPrecio($arCursoTipo->getPrecio());
                                $em->persist($arCursoDetalle);
                            }                 
                            $em->flush();            
                            return $this->redirect($this->generateUrl('brs_afi_movimiento_curso'));
                        } else {
                            $objMensaje->Mensaje("error", "El cliente no existe", $this);
                        }                                                 
                    }                     
                }
                 
            }
                                            
        }
        
        $arEmpleados = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);        
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:nuevoCliente.html.twig', array( 
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/afi/movimiento/curso/detalle/{codigoCurso}", name="brs_afi_movimiento_curso_detalle")
     */    
    public function detalleAction(Request $request, $codigoCurso = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $objMensaje = $this->get('mensajes_brasa');
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
        $form = $this->formularioDetalle($arCurso);
        $form->handleRequest($request);
        $this->listaDetalle($codigoCurso);
        if ($form->isValid()) {  
            if($form->get('BtnAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 5)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoCurso);
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->autorizar($codigoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }            
            if($form->get('BtnAnular')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 9)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->anular($codigoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }            
            if($form->get('BtnDesAutorizar')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 6)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->desAutorizar($codigoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                }
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }    
            if($form->get('BtnImprimir')->isClicked()) {
                if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 126, 10)) {
                    return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
                }
                $strResultado = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->imprimir($codigoCurso);
                if($strResultado != "") {
                    $objMensaje->Mensaje("error", $strResultado, $this);
                } else {
                    $objCurso = new \Brasa\AfiliacionBundle\Formatos\Curso();
                    $objCurso->Generar($this, $codigoCurso);                    
                }
                //return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }      
            if($form->get('BtnFacturar')->isClicked()) {            
                if($arCurso->getEstadoFacturado() == 0 && $arCurso->getEstadoAutorizado() == 1) {                    
                    $codigoFactura = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->facturar($codigoCurso,  $this->getUser()->getUsername(), 1);
                    if($codigoFactura != 0) {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));                                        
                    }                    
                }
            }            
            if($form->get('BtnCuentaCobro')->isClicked()) {            
                if($arCurso->getEstadoFacturado() == 0 && $arCurso->getEstadoAutorizado() == 1) {                    
                    $codigoFactura = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->facturar($codigoCurso,  $this->getUser()->getUsername(), 2);
                    if($codigoFactura != 0) {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_factura_detalle', array('codigoFactura' => $codigoFactura)));                                        
                    } else {
                        return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));                                        
                    }                    
                }
            }            
            if($form->get('BtnDetalleActualizar')->isClicked()) {   
                $arrControles = $request->request->All();
                $this->actualizarDetalle($arrControles, $codigoCurso);                                 
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }            
            if ($form->get('BtnDetalleEliminar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->eliminar($arrSeleccionados);
                $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->liquidar($codigoCurso);
                return $this->redirect($this->generateUrl('brs_afi_movimiento_curso_detalle', array('codigoCurso' => $codigoCurso)));
            }
        }
        
        $arCursoDetalles = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:detalle.html.twig', array(
            'arCurso' => $arCurso, 
            'arCursoDetalles' => $arCursoDetalles, 
            'form' => $form->createView()));
    }    

    /**
     * @Route("/afi/movimiento/curso/detalle/nuevo/{codigoCurso}", name="brs_afi_movimiento_curso_detalle_nuevo")
     */    
    public function detalleNuevoAction(Request $request, $codigoCurso = '') {
        $em = $this->getDoctrine()->getManager();        
        $paginator  = $this->get('knp_paginator');
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);
        $form = $this->formularioDetalleNuevo();
        $form->handleRequest($request);        
        if ($form->isValid()) {
            $arrSeleccionados = $request->request->get('ChkSeleccionar');                                      
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                foreach ($arrSeleccionados as $codigoCursoTipo) {
                    $arCursoTipo = new \Brasa\AfiliacionBundle\Entity\AfiCursoTipo();
                    $arCursoTipo = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->find($codigoCursoTipo);
                    $arCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
                    $arCursoDetalle->setCursoRel($arCurso);          
                    $arCursoDetalle->setCursoTipoRel($arCursoTipo);
                    $arCursoDetalle->setProveedorRel($arCursoTipo->getProveedorRel());
                    $costo = $em->getRepository('BrasaAfiliacionBundle:AfiEntidadEntrenamientoCosto')->costoCursoEntidadEntrenamiento($arCurso->getCodigoEntidadEntrenamientoFk(), $codigoCursoTipo);
                    $arCursoDetalle->setCosto($costo);
                    $arCursoDetalle->setPrecio($arCursoTipo->getPrecio());
                    $em->persist($arCursoDetalle);                    
                }
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
            }
        }
        $dqlCursosTipos = $em->getRepository('BrasaAfiliacionBundle:AfiCursoTipo')->listaDql();
        $arCursoTipos = $paginator->paginate($em->createQuery($dqlCursosTipos), $request->query->get('page', 1), 20);
        return $this->render('BrasaAfiliacionBundle:Movimiento/Curso:detalleNuevo.html.twig', array(
            'arCurso' => $arCurso, 
            'arCursoTipos' => $arCursoTipos, 
            'form' => $form->createView()));
    }    
    
    private function lista() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $strFechaDesde = "";
        $strFechaHasta = "";        
        $filtrarFecha = $session->get('filtroCursoFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroCursoFechaDesde');
            $strFechaHasta = $session->get('filtroCursoFechaHasta');                    
        }
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->listaDQL(
                $session->get('filtroCursoNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroCursoEstadoAutorizado'), 
                $session->get('filtroCursoAsistencia'),
                $session->get('filtroCursoEstadoFacturado'),
                $session->get('filtroCursoEstadoPagado'),
                $session->get('filtroCursoEstadoAnulado'),
                $strFechaDesde,
                $strFechaHasta,
                $session->get('filtroCodigoEmpleado')
                ); 
    }
    
    private function listaNuevoCliente() {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->listaDQL('',
                $session->get('filtroCodigoCliente')
                ); 
    }    
    
    private function listaDetalle($codigoCurso) {    
        $session = new session;
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->listaDQL(
                $codigoCurso   
                ); 
    }    

    private function filtrar ($form) {        
        $session = new session;       
        $session->set('filtroCursoNumero', $form->get('TxtNumero')->getData());
        $session->set('filtroCursoEstadoAutorizado', $form->get('estadoAutorizado')->getData());          
        $session->set('filtroCursoAsistencia', $form->get('asistencia')->getData());          
        $session->set('filtroCursoEstadoFacturado', $form->get('estadoFacturado')->getData());          
        $session->set('filtroCursoEstadoPagado', $form->get('estadoPagado')->getData());          
        $session->set('filtroCursoEstadoAnulado', $form->get('estadoAnulado')->getData());          
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
        $session->set('filtroNumeroIdentificacion', $form->get('TxtNumeroIdentificacion')->getData());                         
        $dateFechaDesde = $form->get('fechaDesde')->getData();
        $dateFechaHasta = $form->get('fechaHasta')->getData();
        $session->set('filtroCursoFechaDesde', $dateFechaDesde->format('Y/m/d'));
        $session->set('filtroCursoFechaHasta', $dateFechaHasta->format('Y/m/d'));                 
        $session->set('filtroCursoFiltrarFecha', $form->get('filtrarFecha')->getData());
    }
    
    private function filtrarNuevoCliente ($form) {        
        $session = new session;              
        $session->set('filtroNit', $form->get('TxtNit')->getData());                         
    }    
    
    private function formularioFiltro() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }  
        $strNombreEmpleado = "";
        if($session->get('filtroNumeroIdentificacion')) {
            $arEmpleado = $em->getRepository('BrasaAfiliacionBundle:AfiEmpleado')->findOneBy(array('numeroIdentificacion' => $session->get('filtroNumeroIdentificacion')));
            if($arEmpleado) {
                $session->set('filtroCodigoEmpleado', $arEmpleado->getCodigoEmpleadoPk());
                $strNombreEmpleado = $arEmpleado->getNombreCorto();
            }  else {
                $session->set('filtroCodigoEmpleado', null);
                $session->set('filtroNumeroIdentificacion', null);
            }          
        } else {
            $session->set('filtroCodigoEmpleado', null);
        }        
        
        $dateFecha = new \DateTime('now');
        $strFechaDesde = $dateFecha->format('Y/m/')."01";
        $intUltimoDia = $strUltimoDiaMes = date("d",(mktime(0,0,0,$dateFecha->format('m')+1,1,$dateFecha->format('Y'))-1));
        $strFechaHasta = $dateFecha->format('Y/m/').$intUltimoDia;
        if($session->get('filtroCursoFechaDesde') != "") {
            $strFechaDesde = $session->get('filtroCursoFechaDesde');
        }
        if($session->get('filtroCursoFechaHasta') != "") {
            $strFechaHasta = $session->get('filtroCursoFechaHasta');
        }    
        $dateFechaDesde = date_create($strFechaDesde);
        $dateFechaHasta = date_create($strFechaHasta);
        $form = $this->createFormBuilder()
            ->add('TxtNit', textType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', textType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                
            ->add('TxtNumeroIdentificacion', textType::class, array('label'  => 'Nit','data' => $session->get('filtroNumeroIdentificacion')))
            ->add('TxtNombreEmpleado', textType::class, array('label'  => 'NombreCliente','data' => $strNombreEmpleado))                                
            ->add('TxtNumero', textType::class, array('label'  => 'Codigo','data' => $session->get('filtroCursoNumero')))
            ->add('estadoAutorizado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'AUTORIZADO', '0' => 'SIN AUTORIZAR'), 'data' => $session->get('filtroCursoEstadoAutorizado')))                
            ->add('asistencia', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'ASISTIO', '0' => 'NO ASISTIO'), 'data' => $session->get('filtroCursoAsistencia')))                                
            ->add('estadoFacturado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'FACTURADO', '0' => 'SIN FACTURAR'), 'data' => $session->get('filtroCursoEstadoFacturado')))                                
            ->add('estadoPagado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'PAGADO', '0' => 'SIN PAGAR'), 'data' => $session->get('filtroCursoEstadoPagado')))                                                
            ->add('estadoAnulado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'ANULADO', '0' => 'SIN ANULAR'), 'data' => $session->get('filtroCursoEstadoAnulado')))                                
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaDesde))                            
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFechaHasta))                
            ->add('filtrarFecha', CheckboxType::class, array('required'  => false, 'data' => $session->get('filtroCursoFiltrarFecha')))                             
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar',))            
            ->add('BtnAsistencia', SubmitType::class, array('label'  => 'Asistencia',))            
            ->add('BtnCertificado', SubmitType::class, array('label'  => 'Certificado',))                            
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnExcelDetalle', SubmitType::class, array('label'  => 'Excel detalle',))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }     
    
    private function formularioDetalle($ar) {        
        $arrBotonAutorizar = array('label' => 'Autorizar', 'disabled' => false);      
        $arrBotonAnular = array('label' => 'Anular', 'disabled' => true);    
        $arrBotonFacturar = array('label' => 'Facturar', 'disabled' => true);        
        $arrBotonCuentaCobro = array('label' => 'Cuenta cobro', 'disabled' => true);        
        $arrBotonDesAutorizar = array('label' => 'Des-autorizar', 'disabled' => false);
        $arrBotonImprimir = array('label' => 'Imprimir', 'disabled' => false);
        $arrBotonDetalleEliminar = array('label' => 'Eliminar', 'disabled' => false);
        $arrBotonDetalleActualizar = array('label' => 'Actualizar', 'disabled' => false);
        
        if($ar->getEstadoAutorizado() == 1) {            
            $arrBotonAutorizar['disabled'] = true;                        
            $arrBotonDetalleEliminar['disabled'] = true;            
            $arrBotonDetalleActualizar['disabled'] = true;

            $arrBotonAnular['disabled'] = false; 
            if($ar->getEstadoAnulado() == 1) {
                $arrBotonDesAutorizar['disabled'] = true;
                $arrBotonAnular['disabled'] = true;
            } else {
                if($ar->getEstadoFacturado() == 0) {
                    if($ar->getNumero() > 0) {
                        $arrBotonFacturar['disabled'] = false;
                        $arrBotonCuentaCobro['disabled'] = false;
                    }                    
                }                
            }            
        } else {
            $arrBotonDesAutorizar['disabled'] = true;            
            $arrBotonImprimir['disabled'] = true;
        }
 
        $form = $this->createFormBuilder()
                    ->add('BtnFacturar', SubmitType::class, $arrBotonFacturar)                
                    ->add('BtnCuentaCobro', SubmitType::class, $arrBotonCuentaCobro)                
                    ->add('BtnDesAutorizar', SubmitType::class, $arrBotonDesAutorizar)            
                    ->add('BtnAutorizar', SubmitType::class, $arrBotonAutorizar)                                     
                    ->add('BtnImprimir', SubmitType::class, $arrBotonImprimir)
                    ->add('BtnAnular', SubmitType::class, $arrBotonAnular)                
                    ->add('BtnDetalleActualizar', SubmitType::class, $arrBotonDetalleActualizar)
                    ->add('BtnDetalleEliminar', SubmitType::class, $arrBotonDetalleEliminar)
                    ->getForm();
        return $form;
    }    
    
    private function formularioNuevoCliente() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $strNombreCliente = "";
        if($session->get('filtroNit')) {
            $arCliente = $em->getRepository('BrasaAfiliacionBundle:AfiCliente')->findOneBy(array('nit' => $session->get('filtroNit')));
            if($arCliente) {
                $session->set('filtroCodigoCliente', $arCliente->getCodigoClientePk());
                $strNombreCliente = $arCliente->getNombreCorto();
            }  else {
                $session->set('filtroCodigoCliente', null);
                $session->set('filtroNit', null);
            }          
        } else {
            $session->set('filtroCodigoCliente', null);
        }       
        $dateFecha = new \DateTime('now');
        
        $form = $this->createFormBuilder()
            ->add('entidadEntrenamientoRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiEntidadEntrenamiento',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ee')
                    ->orderBy('ee.nombreCorto', 'ASC');},
                'choice_label' => 'nombreCorto',
                'required' => true))  
            ->add('cursoTipoRel', EntityType::class, array(
                'class' => 'BrasaAfiliacionBundle:AfiCursoTipo',
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ct')
                    ->orderBy('ct.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))                             
            ->add('TxtNit', textType::class, array('label'  => 'Nit','data' => $session->get('filtroNit')))
            ->add('TxtNombreCliente', textType::class, array('label'  => 'NombreCliente','data' => $strNombreCliente))                                
            ->add('fechaProgramacion', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFecha))                                                            
            ->add('fechaVence', DateType::class, array('format' => 'yyyyMMdd', 'data' => $dateFecha))                                                                                        
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))                                    
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }    
    
    private function formularioDetalleNuevo() {
        $session = new session;
        $form = $this->createFormBuilder()     
            ->add('TxtNombre', textType::class, array('label'  => 'Nombre','data' => $session->get('filtroEmpleadoNombre')))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar',))            
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar',))            
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'P'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'P'; $col !== 'S'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'VENCE')
                    ->setCellValue('E1', 'PROGRAMADO')
                    ->setCellValue('F1', 'NIT')
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'IDENTIFICACION')
                    ->setCellValue('I1', 'EMPLEADO')
                    ->setCellValue('J1', 'FAC')
                    ->setCellValue('K1', 'PAG')
                    ->setCellValue('L1', 'AUT')
                    ->setCellValue('M1', 'ASI')
                    ->setCellValue('N1', 'CER')
                    ->setCellValue('O1', 'ANU')
                    ->setCellValue('P1', 'COSTO')
                    ->setCellValue('Q1', 'TOTAL')
                    ->setCellValue('R1', 'UTILIDAD');

        $i = 2;        
        $query = $em->createQuery($this->strDqlLista);
        $arCursos = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCursos = $query->getResult();
                
        foreach ($arCursos as $arCurso) {            
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCurso->getCodigoCursoPk())
                    ->setCellValue('B' . $i, $arCurso->getNumero())
                    ->setCellValue('C' . $i, $arCurso->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arCurso->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arCurso->getFechaProgramacion()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arCurso->getClienteRel()->getNit())
                    ->setCellValue('G' . $i, $arCurso->getClienteRel()->getNombreCorto())
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arCurso->getEstadoFacturado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arCurso->getEstadoPagado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arCurso->getEstadoAutorizado()))
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arCurso->getAsistencia()))
                    ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arCurso->getCertificado()))
                    ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arCurso->getEstadoAnulado()))
                    ->setCellValue('P' . $i, $arCurso->getCosto())
                    ->setCellValue('Q' . $i, $arCurso->getTotal())
                    ->setCellValue('R' . $i, $arCurso->getTotal()-$arCurso->getCosto());
            
            if($arCurso->getCodigoEmpleadoFk() != null) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arCurso->getEmpleadoRel()->getNumeroIdentificacion());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $arCurso->getEmpleadoRel()->getNombreCorto());
            }
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Curso');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Cursos.xlsx"');
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

    private function generarExcelDetallado() {
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
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10); 
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        for($col = 'A'; $col !== 'V'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);         
        }      
        for($col = 'S'; $col !== 'V'; $col++) {            
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('#,##0');
        }         
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIG0')
                    ->setCellValue('B1', 'NUMERO')
                    ->setCellValue('C1', 'FECHA')
                    ->setCellValue('D1', 'VENCE')
                    ->setCellValue('E1', 'PROGRAMADO')
                    ->setCellValue('F1', 'NIT')
                    ->setCellValue('G1', 'CLIENTE')
                    ->setCellValue('H1', 'IDENTIFICACION')
                    ->setCellValue('I1', 'EMPLEADO')
                    ->setCellValue('J1', 'FAC')
                    ->setCellValue('K1', 'PAG')
                    ->setCellValue('L1', 'AUT')
                    ->setCellValue('M1', 'ASI')
                    ->setCellValue('N1', 'CER')
                    ->setCellValue('O1', 'ANU')
                    ->setCellValue('P1', 'TIPO')
                    ->setCellValue('Q1', 'PROVEEDOR')
                    ->setCellValue('R1', 'ASESOR')
                    ->setCellValue('S1', 'COSTO')
                    ->setCellValue('T1', 'PRECIO')
                    ->setCellValue('U1', 'UTILIDAD');

        $i = 2;    
        $strFechaDesde = "";
        $strFechaHasta = "";        
        $filtrarFecha = $session->get('filtroCursoFiltrarFecha');
        if($filtrarFecha) {
            $strFechaDesde = $session->get('filtroCursoFechaDesde');
            $strFechaHasta = $session->get('filtroCursoFechaHasta');                    
        }        
        $dql= $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->listaDqlConsulta(
                $session->get('filtroCursoNumero'), 
                $session->get('filtroCodigoCliente'), 
                $session->get('filtroCursoEstadoAutorizado'), 
                $session->get('filtroCursoAsistencia'),
                $session->get('filtroCursoEstadoFacturado'),
                $session->get('filtroCursoEstadoPagado'),
                $session->get('filtroCursoEstadoAnulado'),
                $strFechaDesde,
                $strFechaHasta  
                );         
        
        $query = $em->createQuery($dql);        
        $arCursosDetalles = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();
        $arCursosDetalles = $query->getResult();
                
        foreach ($arCursosDetalles as $arCursoDetalle) {  
            $proveedor = '';
            if ($arCursoDetalle->getCodigoProveedorFk() != null){
                $proveedor = $arCursoDetalle->getProveedorRel()->getNombreCorto();
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arCursoDetalle->getCursoRel()->getCodigoCursoPk())
                    ->setCellValue('B' . $i, $arCursoDetalle->getCursoRel()->getNumero())
                    ->setCellValue('C' . $i, $arCursoDetalle->getCursoRel()->getFecha()->format('Y/m/d'))
                    ->setCellValue('D' . $i, $arCursoDetalle->getCursoRel()->getFechaVence()->format('Y/m/d'))
                    ->setCellValue('E' . $i, $arCursoDetalle->getCursoRel()->getFechaProgramacion()->format('Y/m/d'))
                    ->setCellValue('F' . $i, $arCursoDetalle->getCursoRel()->getClienteRel()->getNit())
                    ->setCellValue('G' . $i, $arCursoDetalle->getCursoRel()->getClienteRel()->getNombreCorto())
                    ->setCellValue('J' . $i, $objFunciones->devuelveBoolean($arCursoDetalle->getCursoRel()->getEstadoFacturado()))
                    ->setCellValue('K' . $i, $objFunciones->devuelveBoolean($arCursoDetalle->getCursoRel()->getEstadoPagado()))
                    ->setCellValue('L' . $i, $objFunciones->devuelveBoolean($arCursoDetalle->getCursoRel()->getEstadoAutorizado()))
                    ->setCellValue('M' . $i, $objFunciones->devuelveBoolean($arCursoDetalle->getCursoRel()->getAsistencia()))
                    ->setCellValue('N' . $i, $objFunciones->devuelveBoolean($arCursoDetalle->getCursoRel()->getCertificado()))
                    ->setCellValue('O' . $i, $objFunciones->devuelveBoolean($arCursoDetalle->getCursoRel()->getEstadoAnulado()))
                    ->setCellValue('P' . $i, $arCursoDetalle->getCursoTipoRel()->getNombre())
                    ->setCellValue('Q' . $i, $proveedor)
                    ->setCellValue('R' . $i, $arCursoDetalle->getCursoRel()->getClienteRel()->getAsesorRel()->getNombre())
                    ->setCellValue('S' . $i, $arCursoDetalle->getCosto())
                    ->setCellValue('T' . $i, $arCursoDetalle->getPrecio())
                    ->setCellValue('U' . $i, $arCursoDetalle->getPrecio() - $arCursoDetalle->getCosto());
            
            if($arCursoDetalle->getCursoRel()->getCodigoEmpleadoFk() != null) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $arCursoDetalle->getCursoRel()->getEmpleadoRel()->getNumeroIdentificacion());
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $arCursoDetalle->getCursoRel()->getEmpleadoRel()->getNombreCorto());
            }
            $i++;
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('CursoDetalle');
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="CursosDetalles.xlsx"');
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
    
    private function actualizarDetalle($arrControles, $codigoCurso) {
        $em = $this->getDoctrine()->getManager();        
        if(isset($arrControles['LblCodigo'])) {
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arCursoDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle;
                $arCursoDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->find($intCodigo);
                $arCursoDetalle->setPrecio($arrControles['TxtPrecio'.$intCodigo]);                             
                $arCursoDetalle->setCosto($arrControles['TxtCosto'.$intCodigo]);                             
                $em->persist($arCursoDetalle);
            }
            $em->flush();                
            $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->liquidar($codigoCurso);            
        }        
    }        
    
}