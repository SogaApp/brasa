<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Brasa\RecursoHumanoBundle\Form\Type\RhuEmpleadoType;
use Doctrine\ORM\EntityRepository;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;

class EmpleadoController extends Controller
{
    var $strSqlLista = "";
    
    /**
     * @Route("/rhu/base/empleados/lista", name="brs_rhu_base_empleados_lista")
     */
    public function listaAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegPermisoDocumento')->permiso($this->getUser(), 32, 1)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $paginator  = $this->get('knp_paginator');
        $session = new session;
        $form = $this->formularioLista();
        $form->handleRequest($request);
        $this->listar();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
            }

            if($form->get('BtnPdf')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $objFormatoEmpleado = new \Brasa\RecursoHumanoBundle\Formatos\FormatoEmpleado();
                $objFormatoEmpleado->Generar($this, $this->strSqlLista);

            }
            if($form->get('BtnExcel')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcel();
            }
            if($form->get('BtnInterfaz')->isClicked()) {
                $this->filtrarLista($form);
                $this->listar();
                $this->generarExcelInterfaz();
            }
            if($form->get('BtnInactivar')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionar');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleado) {
                        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
                        if($arEmpleado->getEstadoActivo() == 1) {
                            $arEmpleado->setEstadoActivo(0);
                        } else {
                            $arEmpleado->setEstadoActivo(1);
                        }
                        $em->persist($arEmpleado);
                    }
                    $em->flush();
                }
            }
        }
        $arEmpleados = $paginator->paginate($em->createQuery($this->strSqlLista), $request->query->get('page', 1), 20);
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:lista.html.twig', array(
            'arEmpleados' => $arEmpleados,
            'form' => $form->createView()
            ));
    }
    
    /**
     * @Route("/rhu/base/empleados/detalles/{codigoEmpleado}", name="brs_rhu_base_empleados_detalles")
     */
    public function detalleAction(Request $request, $codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $paginator  = $this->get('knp_paginator');
        $form = $this->createFormBuilder()            
            ->add('BtnEliminarEmpleadoEstudio', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnEliminarEmpleadoFamilia', SubmitType::class, array('label'  => 'Eliminar',))
            ->add('BtnImprimir', SubmitType::class, array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        //inicio - permiso para ver el salario del empleado        
        $permisoVerSalario = $em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(),2);
        //fin - permiso para ver el salario del empleado
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        $arIncapacidades = new \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad();
        $arIncapacidades = $em->getRepository('BrasaRecursoHumanoBundle:RhuIncapacidad')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arVacaciones = new \Brasa\RecursoHumanoBundle\Entity\RhuVacacion();
        $arVacaciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuVacacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arLicencias = new \Brasa\RecursoHumanoBundle\Entity\RhuLicencia();
        $arLicencias = $em->getRepository('BrasaRecursoHumanoBundle:RhuLicencia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arDisciplinarios = new \Brasa\RecursoHumanoBundle\Entity\RhuDisciplinario();
        $arDisciplinarios = $em->getRepository('BrasaRecursoHumanoBundle:RhuDisciplinario')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arContratos = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContratos = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arCreditos = new \Brasa\RecursoHumanoBundle\Entity\RhuCredito();
        $arCreditos = $em->getRepository('BrasaRecursoHumanoBundle:RhuCredito')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arEmpleadoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
        $arEmpleadoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arExamenes = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamenes = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'control' => 1 ));
        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arDotacion = new \Brasa\RecursoHumanoBundle\Entity\RhuDotacion();
        $arDotacion = $em->getRepository('BrasaRecursoHumanoBundle:RhuDotacion')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado));
        $arAdicionalesPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoAdicional();
        $arAdicionalesPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoAdicional')->findBy(array('codigoEmpleadoFk' => $codigoEmpleado, 'permanente' => 1));
        if($form->isValid()) {            
            if($form->get('BtnEliminarEmpleadoEstudio')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarEmpleadoEstudio');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleadoEstudio) {
                        $arEmpleadoEstudios = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoEstudio();
                        $arEmpleadoEstudios = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoEstudio')->find($codigoEmpleadoEstudio);
                        $em->remove($arEmpleadoEstudios);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }

            if($form->get('BtnEliminarEmpleadoFamilia')->isClicked()) {
                $arrSeleccionados = $request->request->get('ChkSeleccionarEmpleadoFamilia');
                if(count($arrSeleccionados) > 0) {
                    foreach ($arrSeleccionados AS $codigoEmpleadoFamilia) {
                        $arEmpleadoFamilia = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia();
                        $arEmpleadoFamilia = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleadoFamilia')->find($codigoEmpleadoFamilia);
                        $em->remove($arEmpleadoFamilia);
                    }
                    $em->flush();
                    return $this->redirect($this->generateUrl('brs_rhu_base_empleados_detalles', array('codigoEmpleado' => $codigoEmpleado)));
                }
            }

            if($form->get('BtnImprimir')->isClicked()) {
                $objFormatoHojaVida = new \Brasa\RecursoHumanoBundle\Formatos\FormatoHojaVida();
                $objFormatoHojaVida->Generar($this, $codigoEmpleado);
            }
        }
        $strRutaImagen = "";
        if($arEmpleado->getRutaFoto() != "") {
            $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
            $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
            $strRutaImagen = $arConfiguracion->getRutaImagenes()."empleados/" . $arEmpleado->getRutaFoto();
        }
        //$verRuta = $arConfiguracion->getRutaImagenes()."empleados/" . $arEmpleado->getRutaFoto();
        $arIncapacidades = $paginator->paginate($arIncapacidades, $this->get('Request')->query->get('page', 1),5);
        $arVacaciones = $paginator->paginate($arVacaciones, $this->get('Request')->query->get('page', 1),5);
        $arLicencias = $paginator->paginate($arLicencias, $this->get('Request')->query->get('page', 1),5);
        $arContratos = $paginator->paginate($arContratos, $this->get('Request')->query->get('page', 1),5);
        $arCreditos = $paginator->paginate($arCreditos, $this->get('Request')->query->get('page', 1),5);
        $arDisciplinarios = $paginator->paginate($arDisciplinarios, $this->get('Request')->query->get('page', 1),5);
        $arEmpleadoEstudios = $paginator->paginate($arEmpleadoEstudios, $this->get('Request')->query->get('page', 1),6);
        $arExamenes = $paginator->paginate($arExamenes, $this->get('Request')->query->get('page', 1),6);
        $arEmpleadoFamilia = $paginator->paginate($arEmpleadoFamilia, $this->get('Request')->query->get('page', 1),8);
        $arDotacion = $paginator->paginate($arDotacion, $this->get('Request')->query->get('page', 1),8);
        $arAdicionalesPago = $paginator->paginate($arAdicionalesPago, $this->get('Request')->query->get('page', 1),8);
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:detalle.html.twig', array(
                    'arEmpleado' => $arEmpleado,
                    'arIncapacidades' => $arIncapacidades,
                    'arVacaciones' => $arVacaciones,
                    'arLicencias' => $arLicencias,
                    'arContratos' => $arContratos,
                    'arCreditos' => $arCreditos,
                    'arDisciplinarios' => $arDisciplinarios,
                    'arEmpleadoEstudios' => $arEmpleadoEstudios,
                    'arExamenes' => $arExamenes,
                    'arEmpleadoFamilia' => $arEmpleadoFamilia,
                    'arDotacion' => $arDotacion,
                    'arAdicionalesPago' => $arAdicionalesPago,
                    'strRutaImagen' => $strRutaImagen,
                    //'strRutas' => $srtRutas,
                    'permisoVerSalario' => $permisoVerSalario,
                    'form' => $form->createView()
                    ));
    }

    /**
     * @Route("/rhu/base/empleados/nuevo/{codigoEmpleado}/{codigoSeleccion}", name="brs_rhu_base_empleados_nuevo")
     */
    public function nuevoAction(Request $request, $codigoEmpleado, $codigoSeleccion = 0) {
        $em = $this->getDoctrine()->getManager();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion;
        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
        $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        if($codigoEmpleado != 0) {
            $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
        } else {
            if($codigoSeleccion != 0) {
                $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                $arEmpleado->setTipoIdentificacionRel($arSeleccion->getTipoIdentificacionRel());
                $arEmpleado->setNumeroIdentificacion($arSeleccion->getNumeroIdentificacion());
                $arEmpleado->setNombre1($arSeleccion->getNombre1());
                $arEmpleado->setNombre2($arSeleccion->getNombre2());
                $arEmpleado->setApellido1($arSeleccion->getApellido1());
                $arEmpleado->setApellido2($arSeleccion->getApellido2());
                $arEmpleado->setEstadoCivilRel($arSeleccion->getEstadoCivilRel());
                $arEmpleado->setFechaNacimiento($arSeleccion->getFechaNacimiento());
                $arEmpleado->setTelefono($arSeleccion->getTelefono());
                $arEmpleado->setCelular($arSeleccion->getCelular());
                $arEmpleado->setCorreo($arSeleccion->getCorreo());
                $arEmpleado->setDireccion($arSeleccion->getDireccion());
                $arEmpleado->setBarrio($arSeleccion->getBarrio());
                $arEmpleado->setCiudadRel($arSeleccion->getCiudadRel());
                $arEmpleado->setCiudadExpedicionRel($arSeleccion->getCiudadExpedicionRel());
                $arEmpleado->setCiudadNacimientoRel($arSeleccion->getCiudadNacimientoRel());
                $arEmpleado->setCodigoSexoFk($arSeleccion->getCodigoSexoFk());
            }
            $arEmpleado->setVrSalario(0); //Parametrizar con configuracion salario minimo
            if($request->request->get('ChkCabezaHogar')){
               $arEmpleado->setCabezaHogar(1);
            }
        }
        $form = $this->createForm(new RhuEmpleadoType(), $arEmpleado);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $boolErrores = 0;
            $arrControles = $request->request->All();
            $arEmpleado = $form->getData();
            if($codigoEmpleado == 0) {
                $arEmpleado->setCodigoUsuario($arUsuario->getUserName());
            }
            $arEmpleado->setNombreCorto($arEmpleado->getApellido1() . " " . $arEmpleado->getApellido2() . " " .$arEmpleado->getNombre1() . " " . $arEmpleado->getNombre2());
            if ($arEmpleado->getCodigoTipoLibreta() != 0){
                $arEmpleado->setLibretaMilitar($arEmpleado->getNumeroIdentificacion());
            }
            else {
                $arEmpleado->setLibretaMilitar('');
            }
            $arEmpleado->setCodigoTipoLibreta($arEmpleado->getCodigoTipoLibreta());
            if($boolErrores == 0) {
                //Calculo edad
                    $varFechaNacimientoAnio = $arEmpleado->getFechaNacimiento()->format('Y');
                    $varFechaNacimientoMes = $arEmpleado->getFechaNacimiento()->format('m');
                    $varMesActual = date('m');
                    if ($varMesActual >= $varFechaNacimientoMes){
                        $varEdad = date('Y') - $varFechaNacimientoAnio;
                    } else {
                        $varEdad = date('Y') - $varFechaNacimientoAnio -1;
                    }
                //Fin calculo edad
                $intEdadEmpleado = $arConfiguracion->getEdadMinimaEmpleado();
                if ($varEdad < $intEdadEmpleado){
                    $objMensaje->Mensaje("error", "El empleado debe ser mayor de " .$intEdadEmpleado. " años!", $this);
                }else{
                    $digito = $objFunciones->devuelveDigitoVerificacion($arEmpleado->getNumeroIdentificacion());
                    $arEmpleado->setDigitoVerificacion($digito);
                    $em->persist($arEmpleado);                    
                    $em->flush();
                    if($form->get('guardarnuevo')->isClicked()) {
                        return $this->redirect($this->generateUrl('brs_rhu_base_empleados_nuevo', array('codigoEmpleado' => 0, 'codigoSeleccion' => 0)));
                    } else {
                        return $this->redirect($this->generateUrl('brs_rhu_base_empleados_lista'));
                    }
                }
            }
        }

        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:nuevo.html.twig', array(
            'arEmpleado' => $arEmpleado,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/base/empleados/nuevo/enlazar/", name="brs_rhu_base_empleados_nuevo_enlazar")
     */
    public function enlazarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioEnlazar();
        $form->handleRequest($request);
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($form->isValid()) {
            if($form->get('BtnFiltrar')->isClicked()) {
                if($form->get('TxtIdentificacion')->getData() != "") {
                    $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('numeroIdentificacion' => $form->get('TxtIdentificacion')->getData()));
                }
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:enlazar.html.twig', array(
            'arSelecciones' => $arSelecciones,
            'form' => $form->createView()));
    }

    /**
     * @Route("/rhu/base/empleados/cargar/foto/{codigoEmpleado}", name="brs_rhu_base_empleados_cargar_foto")
     */
    public function cargarFotoAction(Request $request, $codigoEmpleado) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->formularioCargarFoto();
        $form->handleRequest($request);
        $arSelecciones = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
        if($form->isValid()) {
            if($form->get('BtnCargar')->isClicked()) {
                $objArchivo = $form['attachment']->getData();
                $arEmpleado = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
                $arEmpleado = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->find($codigoEmpleado);
                $arConfiguracion = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                $arConfiguracion = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                $strNombreArchivo = $arEmpleado->getCodigoEmpleadoPk() . "_" . $objArchivo->getClientOriginalName();
                $strRuta = $arConfiguracion->getRutaAlmacenamiento() . "imagenes/empleados/" . $strNombreArchivo;
                if(!file_exists($strRuta)) {
                    $form['attachment']->getData()->move($arConfiguracion->getRutaAlmacenamiento() . "imagenes/empleados", $strNombreArchivo);
                    $arEmpleado->setRutaFoto($strNombreArchivo);
                    $em->persist($arEmpleado);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                } else {
                    $arEmpleado->setRutaFoto($strNombreArchivo);
                    $em->persist($arEmpleado);
                    $em->flush();
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                }

            }
        }
        return $this->render('BrasaRecursoHumanoBundle:Base/Empleado:cargarFoto.html.twig', array('form' => $form->createView()));
    }

    private function formularioEnlazar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacionSeleccion')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->getForm();
        return $form;
    }

    private function formularioCargarFoto() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $form = $this->createFormBuilder()
            ->add('attachment', FileType::class)
            ->add('BtnCargar', SubmitType::class, array('label'  => 'Cargar'))
            ->getForm();
        return $form;
    }

    private function formularioLista() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
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
            ->add('estadoActivo', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'ACTIVOS', '0' => 'INACTIVOS')))
            ->add('estadoContratado', ChoiceType::class, array('choices'   => array('2' => 'TODOS', '1' => 'SI', '0' => 'NO')))    
            ->add('TxtNombre', TextType::class, array('label'  => 'Nombre','data' => $session->get('filtroNombre')))
            ->add('TxtIdentificacion', TextType::class, array('label'  => 'Identificacion','data' => $session->get('filtroIdentificacion')))
            ->add('TxtCodigo', TextType::class, array('data' => $session->get('filtroCodigoEmpleado')))
            ->add('BtnFiltrar', SubmitType::class, array('label'  => 'Filtrar'))
            ->add('BtnInterfaz', SubmitType::class, array('label'  => 'Interfaz',))
            ->add('BtnPdf', SubmitType::class, array('label'  => 'PDF',))
            ->add('BtnExcel', SubmitType::class, array('label'  => 'Excel',))
            ->add('BtnInactivar', SubmitType::class, array('label'  => 'Activar / Inactivar',))
            ->getForm();
        return $form;
    }

    private function filtrarLista($form) {
        $session = new session;
        $request = $this->getRequest();
        $controles = $request->request->get('form');
        $session->set('filtroCodigoCentroCosto', $controles['centroCostoRel']);
        $session->set('filtroEmpleadoNombre', $form->get('TxtNombre')->getData());
        $session->set('filtroIdentificacion', $form->get('TxtIdentificacion')->getData());
        $session->set('filtroEmpleadoActivo', $form->get('estadoActivo')->getData());
        $session->set('filtroEmpleadoContratado', $form->get('estadoContratado')->getData());
        $session->set('filtroCodigoEmpleado', $form->get('TxtCodigo')->getData());
    }

    private function listar() {
        $em = $this->getDoctrine()->getManager();
        $session = new session;
        $this->strSqlLista = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->listaDQL(
                $session->get('filtroEmpleadoNombre'),
                $session->get('filtroCodigoCentroCosto'),
                $session->get('filtroEmpleadoActivo'),
                $session->get('filtroIdentificacion'),
                "",
                $session->get('filtroEmpleadoContratado'),
                $session->get('filtroCodigoEmpleado')
                );
    }

    private function generarExcel() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);
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
        for($col = 'A'; $col !== 'AZ'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }        

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CÓDIGO')
                    ->setCellValue('B1', 'TIPO')
                    ->setCellValue('C1', 'IDENTIFICACIÓN')
                    ->setCellValue('D1', 'DV')
                    ->setCellValue('E1', 'CIUDAD EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('F1', 'FECHA EXPEDICIÓN IDENTIFICACIÓN')
                    ->setCellValue('G1', 'LIBRETA MILITAR')
                    ->setCellValue('H1', 'CENTRO COSTO')
                    ->setCellValue('I1', 'NOMBRE')
                    ->setCellValue('J1', 'TELÉFONO')
                    ->setCellValue('K1', 'CELULAR')
                    ->setCellValue('L1', 'DIRECCIÓN')
                    ->setCellValue('M1', 'BARRIO')
                    ->setCellValue('N1', 'CIUDAD RESIDENCIA')
                    ->setCellValue('O1', 'RH')
                    ->setCellValue('P1', 'SEXO')
                    ->setCellValue('Q1', 'CORREO')
                    ->setCellValue('R1', 'FECHA NACIMIENTO')
                    ->setCellValue('S1', 'CIUDAD DE NACIMIENTO')
                    ->setCellValue('T1', 'ESTADO CIVIL')
                    ->setCellValue('U1', 'PADRE DE FAMILIA')
                    ->setCellValue('V1', 'CABEZA DE HOGAR')
                    ->setCellValue('W1', 'NIVEL DE ESTUDIO')
                    ->setCellValue('X1', 'ENTIDAD SALUD')
                    ->setCellValue('Y1', 'ENTIDAD PENSION')
                    ->setCellValue('Z1', 'ENTIDAD CAJA DE COMPESACIÓN')
                    ->setCellValue('AA1', 'ENTIDAD CESANTIAS')
                    ->setCellValue('AB1', 'CLASIFICACIÓN DE RIESGO')
                    ->setCellValue('AC1', 'CUENTA BANCARIA')
                    ->setCellValue('AD1', 'BANCO')
                    ->setCellValue('AE1', 'FECHA CONTRATO')
                    ->setCellValue('AF1', 'FECHA FINALIZA CONTRATO')
                    ->setCellValue('AG1', 'CARGO')
                    ->setCellValue('AH1', 'DESCRIPCIÓN CARGO')
                    ->setCellValue('AI1', 'TIPO PENSIÓN')
                    ->setCellValue('AJ1', 'TIPO COTIZANTE')
                    ->setCellValue('AK1', 'SUBTIPO COTIZANTE')
                    ->setCellValue('AL1', 'ESTADO ACTIVO')
                    ->setCellValue('AM1', 'ESTADO CONTRATO')
                    ->setCellValue('AN1', 'CODIGO CONTRATO')
                    ->setCellValue('AO1', 'TALLA CAMISA')
                    ->setCellValue('AP1', 'TALLA JEANS')
                    ->setCellValue('AQ1', 'TALLA CALZADO')
                    ->setCellValue('AR1', 'DEPARTAMENTO')
                    ->setCellValue('AS1', 'HORARIO')
                    ->setCellValue('AT1', 'DISCAPACIDAD')
                    ->setCellValue('AU1', 'ZONA')
                    ->setCellValue('AV1', 'SUBZONA')
                    ->setCellValue('AW1', 'TIPO')
                    ->setCellValue('AX1', 'C.CONTABILIDAD')
                    ->setCellValue('AY1', 'PUESTO');

        $i = 2;
        $query = $em->createQuery($this->strSqlLista);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();
        $arEmpleados = $query->getResult();
        foreach ($arEmpleados as $arEmpleado) {
            if ($arEmpleado->getCodigoCentroCostoFk() == null){
                $centroCosto = "";
            }else{
                $centroCosto = $arEmpleado->getCentroCostoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoClasificacionRiesgoFk() == null){
                $clasificacionRiesgo = "";
            }else{
                $clasificacionRiesgo = $arEmpleado->getClasificacionRiesgoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoCargoFk() == null){
                $cargo = "";
            }else{
                $cargo = $arEmpleado->getCargoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoPensionFk() == null){
                $tipoPension = "";
            }else{
                $tipoPension = $arEmpleado->getTipoPensionRel()->getNombre();
            }
            if ($arEmpleado->getCodigoTipoCotizanteFk() == null){
                $tipoCotizante = "";
            }else{
                $tipoCotizante = $arEmpleado->getSsoTipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoSubtipoCotizanteFk() == null){
                $subtipoCotizante = "";
            }else{
                $subtipoCotizante = $arEmpleado->getSsoSubtipoCotizanteRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadSaludFk() == null){
                $entidadSalud = "";
            }else{
                $entidadSalud = $arEmpleado->getEntidadSaludRel()->getNombre();
            }
            
            if ($arEmpleado->getCodigoEntidadPensionFk() == null){
                $entidadPension = "";
            }else{
                $entidadPension = $arEmpleado->getEntidadPensionRel()->getNombre();
            }
            
            if ($arEmpleado->getCodigoEntidadCajaFk() == null){
                $entidadCaja = "";
            }else{
                $entidadCaja = $arEmpleado->getEntidadCajaRel()->getNombre();
            }        
            if ($arEmpleado->getCodigoSexoFk() == "M"){
                $sexo = "MASCULINO";
            }else{
                $sexo = "FEMENINO";
            }
            if ($arEmpleado->getPadreFamilia() == 0){
                $padreFamilia = "NO";
            }else{
                $padreFamilia = "SI";
            }
            if ($arEmpleado->getCabezaHogar() == 0){
                $cabezaHogar = "NO";
            }else{
                $cabezaHogar = "SI";
            }
            if ($arEmpleado->getEstadoActivo() == 0){
                $estadoActivo = "NO";
            }else{
                $estadoActivo = "SI";
            }
            if ($arEmpleado->getDiscapacidad() == 0){
                $discapacidad = "NO";
            }else{
                $discapacidad = "SI";
            }
            if ($arEmpleado->getEstadoContratoActivo() == 0){
                $estadoContratoActivo = "NO VIGENTE";
            }else{
                $estadoContratoActivo = "VIGENTE";
            }
            if ($arEmpleado->getCodigoDepartamentoEmpresaFk() == null){
                $departamentoEmpresa = "";
            }else{
                $departamentoEmpresa = $arEmpleado->getDepartamentoEmpresaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoHorarioFk() == null){
                $horario = "";
            }else{
                $horario = $arEmpleado->getHorarioRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEmpleadoEstudioTipoFk() == null){
                $empleadoEstudioTipo = "";
            }else{
                $empleadoEstudioTipo = $arEmpleado->getEmpleadoEstudioTipoRel()->getNombre();
            }
            if ($arEmpleado->getCodigoEntidadCesantiaFk() == null){
                $entidadCesantia = "";
            }else{
                $entidadCesantia = $arEmpleado->getEntidadCesantiaRel()->getNombre();
            }
            if ($arEmpleado->getCodigoCiudadExpedicionFk() != null){
                $ciudadExpedicion = $arEmpleado->getciudadExpedicionRel()->getNombre();
            } else {
                $ciudadExpedicion = "";
            }
            if ($arEmpleado->getCodigoCiudadNacimientoFk() != null){
                $ciudadNacimiento = $arEmpleado->getCiudadNacimientoRel()->getNombre();
            } else {
                $ciudadNacimiento = "";
            }
            if ($arEmpleado->getCodigoRhPk() != null){
                $rh = $arEmpleado->getRhRel()->getTipo();
            } else {
                $rh = "";
            }
            if ($arEmpleado->getCodigoBancoFk() != null){
                $banco = $arEmpleado->getBancoRel()->getNombre();
            } else {
                $banco = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getCodigoEmpleadoPk())
                    ->setCellValue('B' . $i, $arEmpleado->getTipoIdentificacionRel()->getNombre())
                    ->setCellValue('C' . $i, $arEmpleado->getNumeroIdentificacion())
                    ->setCellValue('D' . $i, $arEmpleado->getDigitoVerificacion())
                    ->setCellValue('E' . $i, $ciudadExpedicion)
                    ->setCellValue('F' . $i, $arEmpleado->getFechaExpedicionIdentificacion())
                    ->setCellValue('G' . $i, $arEmpleado->getLibretaMilitar())
                    ->setCellValue('H' . $i, $centroCosto)
                    ->setCellValue('I' . $i, $arEmpleado->getNombreCorto())
                    ->setCellValue('J' . $i, $arEmpleado->getTelefono())
                    ->setCellValue('K' . $i, $arEmpleado->getCelular())
                    ->setCellValue('L' . $i, $arEmpleado->getDireccion())
                    ->setCellValue('M' . $i, $arEmpleado->getBarrio())
                    ->setCellValue('N' . $i, $arEmpleado->getciudadRel()->getNombre())
                    ->setCellValue('O' . $i, $rh)
                    ->setCellValue('P' . $i, $sexo)
                    ->setCellValue('Q' . $i, $arEmpleado->getCorreo())
                    ->setCellValue('R' . $i, $arEmpleado->getFechaNacimiento())
                    ->setCellValue('S' . $i, $ciudadNacimiento)
                    ->setCellValue('T' . $i, $arEmpleado->getEstadoCivilRel()->getNombre())
                    ->setCellValue('U' . $i, $padreFamilia)
                    ->setCellValue('V' . $i, $cabezaHogar)
                    ->setCellValue('W' . $i, $empleadoEstudioTipo)
                    ->setCellValue('X' . $i, $entidadSalud)
                    ->setCellValue('Y' . $i, $entidadPension)
                    ->setCellValue('Z' . $i, $entidadCaja)
                    ->setCellValue('AA' . $i, $entidadCesantia)
                    ->setCellValue('AB' . $i, $clasificacionRiesgo)
                    ->setCellValue('AC' . $i, $arEmpleado->getCuenta())
                    ->setCellValue('AD' . $i, $banco)
                    ->setCellValue('AE' . $i, $arEmpleado->getFechaContrato())
                    ->setCellValue('AF' . $i, $arEmpleado->getFechaFinalizaContrato())
                    ->setCellValue('AG' . $i, $cargo)
                    ->setCellValue('AH' . $i, $arEmpleado->getCargoDescripcion())
                    ->setCellValue('AI' . $i, $tipoPension)
                    ->setCellValue('AJ' . $i, $tipoCotizante)
                    ->setCellValue('AK' . $i, $subtipoCotizante)
                    ->setCellValue('AL' . $i, $estadoActivo)
                    ->setCellValue('AM' . $i, $estadoContratoActivo)
                    ->setCellValue('AN' . $i, $arEmpleado->getCodigoContratoActivoFk())
                    ->setCellValue('AO' . $i, $arEmpleado->getCamisa())
                    ->setCellValue('AP' . $i, $arEmpleado->getJeans())
                    ->setCellValue('AQ' . $i, $arEmpleado->getCalzado())
                    ->setCellValue('AR' . $i, $departamentoEmpresa)
                    ->setCellValue('AS' . $i, $horario)
                    ->setCellValue('AT' . $i, $discapacidad)
                    ->setCellValue('AX' . $i, $arEmpleado->getCodigoCentroCostoContabilidadFk());
            if($arEmpleado->getCodigoZonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AU' . $i, $arEmpleado->getZonaRel()->getNombre()); 
            }
            if($arEmpleado->getCodigoSubzonaFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AV' . $i, $arEmpleado->getSubzonaRel()->getNombre()); 
            }
            if($arEmpleado->getCodigoEmpleadoTipoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AW' . $i, $arEmpleado->getEmpleadoTipoRel()->getNombre()); 
            }            
            if($arEmpleado->getCodigoPuestoFk()) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AY' . $i, $arEmpleado->getPuestoRel()->getNombre()); 
            }            
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Empleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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
        ini_set('memory_limit', '512m');
        set_time_limit(60);
    }
    
    private function generarExcelInterfaz() {
        ob_clean();
        set_time_limit(0);
        ini_set("memory_limit", -1);        
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
        for($col = 'A'; $col !== 'AR'; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal('left');                
        }    
        for($col = 'L'; $col !== 'M'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        }         
        for($col = 'T'; $col !== 'U'; $col++) {
            $objPHPExcel->getActiveSheet()->getStyle($col)->getNumberFormat()->setFormatCode('yyyy/mm/dd');
        } 
        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A1', 'NIT')
                    ->setCellValue('B1', 'clase')
                    ->setCellValue('C1', 'NOMBRE')
                    ->setCellValue('D1', 'nombre1')
                    ->setCellValue('E1', 'nombre2')
                    ->setCellValue('F1', 'apellido1')
                    ->setCellValue('G1', 'apellido2')
                    ->setCellValue('H1', 'direccion')
                    ->setCellValue('I1', 'email')
                    ->setCellValue('J1', 'tel1')
                    ->setCellValue('K1', 'tel2')
                    ->setCellValue('L1', 'fechaing')
                    ->setCellValue('M1', 'CIIU')
                    ->setCellValue('N1', 'CDCIIU')
                    ->setCellValue('O1', 'SUCURSAL')
                    ->setCellValue('P1', 'CODALTERNO')
                    ->setCellValue('Q1', 'ESCLIENTE')
                    ->setCellValue('R1', 'HABILITADO')
                    ->setCellValue('S1', 'INTCAR')
                    ->setCellValue('T1', 'fecnac');

        $i = 2;
        $fecha = new \DateTime('now');
        $query = $em->createQuery($this->strSqlLista);
        $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();                         
        $arEmpleados = $query->getResult();
        $fecha = new \DateTime('now');
        foreach ($arEmpleados as $arEmpleado) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $arEmpleado->getNumeroIdentificacion()."-".$arEmpleado->getDigitoVerificacion())
                    ->setCellValue('B' . $i, $arEmpleado->getCodigoTipoIdentificacionFk())
                    ->setCellValue('C' . $i, $arEmpleado->getNombreCorto())
                    ->setCellValue('D' . $i, $arEmpleado->getNombre1())
                    ->setCellValue('E' . $i, $arEmpleado->getNombre2())
                    ->setCellValue('F' . $i, $arEmpleado->getApellido1())
                    ->setCellValue('G' . $i, $arEmpleado->getApellido2())
                    ->setCellValue('H' . $i, $arEmpleado->getDireccion())
                    ->setCellValue('I' . $i, $arEmpleado->getCorreo())
                    ->setCellValue('J' . $i, $arEmpleado->getTelefono())
                    ->setCellValue('K' . $i, $arEmpleado->getCelular())                    
                    ->setCellValue('L' . $i, PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,$fecha->format('m'),$fecha->format('d'),$fecha->format('Y'))))
                    ->setCellValue('M' . $i, $arEmpleado->getCiudadRel()->getCodigoInterface())
                    ->setCellValue('N' . $i, $arEmpleado->getCiudadRel()->getCodigoInterface())
                    ->setCellValue('O' . $i, '0')
                    ->setCellValue('P' . $i, '')
                    ->setCellValue('Q' . $i, 'S')
                    ->setCellValue('R' . $i, 'S')
                    ->setCellValue('S' . $i, 'S')
                    ->setCellValue('T' . $i, PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,$arEmpleado->getFechaNacimiento()->format('m'),$arEmpleado->getFechaNacimiento()->format('d'),$arEmpleado->getFechaNacimiento()->format('Y'))));                                                        
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Empleados');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Empleados.xlsx"');
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
