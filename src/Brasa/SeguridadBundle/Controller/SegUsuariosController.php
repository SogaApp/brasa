<?php

namespace Brasa\SeguridadBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\SecurityContext;
use Brasa\SeguridadBundle\Form\Type\UserType;
use Brasa\SeguridadBundle\Form\Type\SegPermisoDocumentoType;
class SegUsuariosController extends Controller
{
    var $strDqlLista = "";
    public function listaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        $arUsuarios = $paginator->paginate($em->createQuery($this->strDqlLista), $request->query->get('page', 1), 50);
        return $this->render('BrasaSeguridadBundle:Usuarios:lista.html.twig', array(
            'form' => $form->createView(),
            'arUsuarios' => $arUsuarios
            ));
    }

    public function nuevoAction($codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        //El error es que se debe colocar el eslas entes de Brasa solo con eso toma la clase
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        if($codigoUsuario != 0) {
            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
        }
        $form = $this->createForm(new UserType(), $arUsuario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $form->getData();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($arUsuario->getPassword(), $arUsuario->getSalt());
            if($codigoUsuario == 0) {
                $arUsuario->setPassword($password);
            }

            $em->persist($arUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_seg_admin_usuario_lista'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function detalleAction($codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->add('BtnEliminarEspecial', 'submit', array('label' => 'Eliminar'))
            ->add('BtnEliminarDocumento', 'submit', array('label' => 'Eliminar'))    
            ->add('BtnEliminarRol', 'submit', array('label' => 'Eliminar'))            
            ->getForm();
        $form->handleRequest($request);
        
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
        if($form->isValid()) {
            if($form->get('BtnEliminarEspecial')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoEspecial');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioPermisoEspecialPk) {
                        $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                        $arUsuarioPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->find($codigoUsuarioPermisoEspecialPk);
                        $em->remove($arUsuarioPermisoEspecial);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if($form->get('BtnEliminarDocumento')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoDocumento');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPermisoDocumentoPk) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($codigoPermisoDocumentoPk);
                        $em->remove($arPermisoDocumento);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if($form->get('BtnEliminarRol')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarUsuarioRol');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioRol) {
                        $arUsuarioRol = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                        $arUsuarioRol = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->find($codigoUsuarioRol);
                        $em->remove($arUsuarioRol);                        
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }            
        }
        $arPermisosDocumentos = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        $arPermisosDocumentos = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arPermisosEspeciales = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arUsuarioRoles = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
        $arUsuarioRoles = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->findBy(array('codigoUsuarioFk' => $codigoUsuario));        
        return $this->render('BrasaSeguridadBundle:Usuarios:detalle.html.twig', array(
                    'arPermisosDocumentos' => $arPermisosDocumentos,
                    'arPermisosEspeciales' => $arPermisosEspeciales,
                    'arUsuarioRoles' => $arUsuarioRoles,
                    'arUsuario' => $arUsuario,
                    'form' => $form->createView()
                    ));
    }
    
    
    /**
     * @Route("/seg/usuario/detalle/ver/{codigoUsuario}/", name="brs_seg_usuario_detalle_ver")
     */      
    public function detalleVerAction($codigoUsuario) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->createFormBuilder()
            ->getForm();
        $form->handleRequest($request);
        
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
        if($form->isValid()) {
            if($form->get('BtnEliminarEspecial')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoEspecial');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioPermisoEspecialPk) {
                        $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                        $arUsuarioPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->find($codigoUsuarioPermisoEspecialPk);
                        $em->remove($arUsuarioPermisoEspecial);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if($form->get('BtnEliminarDocumento')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarPermisoDocumento');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPermisoDocumentoPk) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($codigoPermisoDocumentoPk);
                        $em->remove($arPermisoDocumento);
                        $em->flush();
                    }
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }
            if($form->get('BtnEliminarRol')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarUsuarioRol');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoUsuarioRol) {
                        $arUsuarioRol = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                        $arUsuarioRol = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->find($codigoUsuarioRol);
                        $em->remove($arUsuarioRol);                        
                    }
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('brs_seg_admin_usuario_detalle', array('codigoUsuario' => $codigoUsuario)));
            }            
            if($form->get('BtnActualizar')->isClicked()) {
                $arrControles = $request->request->All();
                $intIndice = 0;
                if (isset($arrControles['LblCodigoGuiaDocumento'])){
                    foreach ($arrControles['LblCodigoGuiaDocumento'] as $intCodigo) {
                        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($intCodigo);
                        if(isset($arrControles['ChkSeleccionarIngreso'.$intCodigo])) {
                            $arPermisoDocumento->setIngreso(1);
                        } else {
                            $arPermisoDocumento->setIngreso(0);
                        }
                        if(isset($arrControles['ChkSeleccionarNuevo'.$intCodigo])) {
                            $arPermisoDocumento->setNuevo(1);
                        } else {
                            $arPermisoDocumento->setNuevo(0);
                        }
                        if(isset($arrControles['ChkSeleccionarEditar'.$intCodigo])) {
                            $arPermisoDocumento->setEditar(1);
                        } else {
                            $arPermisoDocumento->setEditar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarEliminar'.$intCodigo])) {
                            $arPermisoDocumento->setEliminar(1);
                        } else {
                            $arPermisoDocumento->setEliminar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarAutorizar'.$intCodigo])) {
                            $arPermisoDocumento->setAutorizar(1);
                        } else {
                            $arPermisoDocumento->setAutorizar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarDesautorizar'.$intCodigo])) {
                            $arPermisoDocumento->setDesautorizar(1);
                        } else {
                            $arPermisoDocumento->setDesautorizar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarAprobar'.$intCodigo])) {
                            $arPermisoDocumento->setAprobar(1);
                        } else {
                            $arPermisoDocumento->setAprobar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarDesaprobar'.$intCodigo])) {
                            $arPermisoDocumento->setDesaprobar(1);
                        } else {
                            $arPermisoDocumento->setDesaprobar(0);
                        }
                        if(isset($arrControles['ChkSeleccionarAnular'.$intCodigo])) {
                            $arPermisoDocumento->setAnular(1);
                        } else {
                            $arPermisoDocumento->setAnular(0);
                        }
                        if(isset($arrControles['ChkSeleccionarDesanular'.$intCodigo])) {
                            $arPermisoDocumento->setDesanular(1);
                        } else {
                            $arPermisoDocumento->setDesanular(0);
                        }
                        if(isset($arrControles['ChkSeleccionarImprimir'.$intCodigo])) {
                            $arPermisoDocumento->setImprimir(1);
                        } else {
                            $arPermisoDocumento->setImprimir(0);
                        }
                        $em->persist($arPermisoDocumento);     
                    }
                }
                $em->flush();
            }
        }
        $arPermisosDocumentos = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        $arPermisosDocumentos = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arPermisosEspeciales = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $codigoUsuario));
        $arUsuarioRoles = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
        $arUsuarioRoles = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->findBy(array('codigoUsuarioFk' => $codigoUsuario));        
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleVer.html.twig', array(
                    'arPermisosDocumentos' => $arPermisosDocumentos,
                    'arPermisosEspeciales' => $arPermisosEspeciales,
                    'arUsuarioRoles' => $arUsuarioRoles,
                    'arUsuario' => $arUsuario,
                    'form' => $form->createView()
                    ));
    }    
    
    public function detalleNuevoPermisoEspecialAction($codigoUsuario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('guardar', 'submit', array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
                $arrSeleccionados = $request->request->get('ChkSeleccionar');    
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoPermisoEspecial) {
                        $arUsuarioPermisoEspecialValidar = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                        $arUsuarioPermisoEspecialValidar = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->findBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoPermisoEspecialFk' => $codigoPermisoEspecial));                        
                        if(!$arUsuarioPermisoEspecialValidar) {
                            $arPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegPermisoEspecial();
                            $arPermisoEspecial = $em->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->find($codigoPermisoEspecial);                            
                            $arUsuarioPermisoEspecial = new \Brasa\SeguridadBundle\Entity\SegUsuarioPermisoEspecial();
                            $arUsuarioPermisoEspecial->setPermisoEspecialRel($arPermisoEspecial);
                            $arUsuarioPermisoEspecial->setUsuarioRel($arUsuario);
                            $arUsuarioPermisoEspecial->setPermitir(1);
                            $em->persist($arUsuarioPermisoEspecial);
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                            
                        }
                    }                                        
                }
            }                                                                                              
        }
        $arPermisosEspeciales = $em->getRepository('BrasaSeguridadBundle:SegPermisoEspecial')->findBy(array(), array('modulo' => 'ASC'));
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoPermisoEspecial.html.twig', array(
            'arPermisosEspeciales' => $arPermisosEspeciales,
            'form' => $form->createView()));
    }
    
    public function detalleNuevoPermisoDocumentoAction($codigoUsuario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->add('ingreso', 'checkbox', array('required'  => false))
            ->add('nuevo', 'checkbox', array('required'  => false))
            ->add('editar', 'checkbox', array('required'  => false))
            ->add('eliminar', 'checkbox', array('required'  => false))
            ->add('autorizar', 'checkbox', array('required'  => false))
            ->add('desautorizar', 'checkbox', array('required'  => false))
            ->add('aprobar', 'checkbox', array('required'  => false))
            ->add('desaprobar', 'checkbox', array('required'  => false))
            ->add('anular', 'checkbox', array('required'  => false))            
            ->add('imprimir', 'checkbox', array('required'  => false))
            ->add('desanular', 'checkbox', array('required'  => false))            
            ->add('BtnGuardar', 'submit', array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                $arDatos = $form->getData();
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoDocumento) {        
                        $arUsuarioPermisoDocumentoValidar = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                        $arUsuarioPermisoDocumentoValidar = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->findBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoDocumentoFk' => $codigoDocumento));
                        if(!$arUsuarioPermisoDocumentoValidar) {
                            $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);                                            
                            $arDocumento = $em->getRepository('BrasaSeguridadBundle:SegDocumento')->find($codigoDocumento);
                            $arUsuarioPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
                            $arUsuarioPermisoDocumento->setDocumentoRel($arDocumento);
                            $arUsuarioPermisoDocumento->setUsuarioRel($arUsuario);
                            $arUsuarioPermisoDocumento->setIngreso($arDatos['ingreso']);
                            $arUsuarioPermisoDocumento->setNuevo($arDatos['nuevo']);
                            $arUsuarioPermisoDocumento->setEditar($arDatos['editar']);
                            $arUsuarioPermisoDocumento->setEliminar($arDatos['eliminar']);
                            $arUsuarioPermisoDocumento->setAutorizar($arDatos['autorizar']);
                            $arUsuarioPermisoDocumento->setDesautorizar($arDatos['desautorizar']);
                            $arUsuarioPermisoDocumento->setAprobar($arDatos['aprobar']);
                            $arUsuarioPermisoDocumento->setDesaprobar($arDatos['desaprobar']);
                            $arUsuarioPermisoDocumento->setAnular($arDatos['anular']);
                            $arUsuarioPermisoDocumento->setDesanular($arDatos['desanular']);
                            $arUsuarioPermisoDocumento->setImprimir($arDatos['imprimir']);
                            
                            $em->persist($arUsuarioPermisoDocumento);
                        }                        
                    }
                    $em->flush();                    
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
                else {
                    $objMensaje->Mensaje("error", "No selecciono ningun dato para grabar", $this);
                }
            }                                    
        }
        $arDocumentos = $em->getRepository('BrasaSeguridadBundle:SegDocumento')->findBy(array(), array('tipo' => 'ASC','modulo' => 'ASC','nombre' => 'ASC'));
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoPermisoDocumento.html.twig', array(
            'arDocumentos' => $arDocumentos,
            'form' => $form->createView()
                ));
    }

    /**
     * @Route("/seg/usuario/detalle/permiso/documento/editar/{codigoPermisoDocumento}/", name="brs_seg_usuario_detalle_permiso_documento_editar")
     */     
    public function detallePermisoDocumentoEditarAction($codigoPermisoDocumento) {
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arPermisoDocumento = new \Brasa\SeguridadBundle\Entity\SegPermisoDocumento();
        if($codigoPermisoDocumento != 0) {
            $arPermisoDocumento = $em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->find($codigoPermisoDocumento);
        }
        $form = $this->createForm(new SegPermisoDocumentoType(), $arPermisoDocumento);
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('guardar')->isClicked()) {
                $arPermisoDocumento = $form->getData();   
                $em->persist($arPermisoDocumento);
                $em->flush();
                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                                            
            }
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleEditarPermisoDocumento.html.twig', array(
            'form' => $form->createView()));
    }     
    
    /**
     * @Route("/seg/usuario/detalle/rol/nuevo/{codigoUsuario}/", name="brs_seg_usuario_detalle_rol_nuevo")
     */     
    public function detalleNuevoRolAction($codigoUsuario) {
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $em = $this->getDoctrine()->getManager();
        $arRoles = $em->getRepository('BrasaSeguridadBundle:SegRoles')->findAll();
        $form = $this->createFormBuilder()
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar',))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            if ($form->get('BtnGuardar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoRol) {
                        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);
                        $arRol = $em->getRepository('BrasaSeguridadBundle:SegRoles')->find($codigoRol);
                        $arUsuarioRolValidar = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                        $arUsuarioRolValidar = $em->getRepository('BrasaSeguridadBundle:SegUsuarioRol')->findBy(array('codigoUsuarioFk' => $codigoUsuario, 'codigoRolFk' => $codigoRol));
                        if(!$arUsuarioRolValidar) {
                            $arUsuarioRol = new \Brasa\SeguridadBundle\Entity\SegUsuarioRol();
                            $arUsuarioRol->setRolRel($arRol);
                            $arUsuarioRol->setUsuarioRel($arUsuario);
                            $em->persist($arUsuarioRol);
                        }                        
                    }
                    $em->flush();                    
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }
                else {
                    $objMensaje->Mensaje("error", "No selecciono ningun dato para grabar", $this);
                }
            }
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:detalleNuevoRol.html.twig', array(
            'arRoles' => $arRoles,
            'form' => $form->createView()));
    }    
    
    public function recuperarAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $form = $this->formularioRecuperar();
        $form->handleRequest($request);
        if ($form->isValid()) {            
            return $this->redirect($this->generateUrl('login'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:recuperar.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function cambiarClaveAction($codigoUsuario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $formUsuario = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_seg_admin_usuario_cambiar_clave', array('codigoUsuario' => $codigoUsuario)))
            ->add('password', 'password')                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formUsuario->handleRequest($request);        
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);        
        
        if ($formUsuario->isValid()) {            
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($formUsuario->get('password')->getData(), $arUsuario->getSalt());
            $arUsuario->setPassword($password);   
            $em->persist($arUsuario);
            $em->flush();                                    
            return $this->redirect($this->generateUrl('brs_seg_admin_usuario_lista'));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:cambiarClave.html.twig', array(
            'arUsuario' => $arUsuario,
            'formUsuario' => $formUsuario->createView()
        ));
    }   
    /**
     * @Route("/user/usuario/cambiar/calve/usuario/{codigoUsuario}/", name="brs_seg_user_usuario_cambiar_clave")
     */     
    public function cambiarClaveUsuarioAction($codigoUsuario) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $formUsuario = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_seg_user_usuario_cambiar_clave', array('codigoUsuario' => $codigoUsuario)))
            ->add('password', 'password')                            
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $formUsuario->handleRequest($request);        
        $arUsuario = new \Brasa\SeguridadBundle\Entity\User();
        $arUsuario = $em->getRepository('BrasaSeguridadBundle:User')->find($codigoUsuario);        
        
        if ($formUsuario->isValid()) {            
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($arUsuario);
            $password = $encoder->encodePassword($formUsuario->get('password')->getData(), $arUsuario->getSalt());
            $arUsuario->setPassword($password);   
            $em->persist($arUsuario);
            $em->flush();                                    
            return $this->redirect($this->generateUrl('brs_seg_usuario_detalle_ver', array('codigoUsuario' => $codigoUsuario)));
        }
        return $this->render('BrasaSeguridadBundle:Usuarios:cambiarClave.html.twig', array(
            'arUsuario' => $arUsuario,
            'formUsuario' => $formUsuario->createView()
        ));
    }     
    
    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $this->strDqlLista = $em->getRepository('BrasaSeguridadBundle:User')->listaDql();
    }

    private function formularioRecuperar() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('username', 'text', array('label'  => 'Numero', 'data' => ""))            
            ->add('BtnRecuperar', 'submit', array('label'  => 'Recuperar'))
            ->getForm();
        return $form;
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->getRequest()->getSession();
        $form = $this->createFormBuilder()
            ->add('TxtNumero', 'text', array('label'  => 'Numero','data' => ""))            
            ->add('BtnFiltrar', 'submit', array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

}