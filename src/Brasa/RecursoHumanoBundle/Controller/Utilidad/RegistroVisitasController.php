<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use Brasa\RecursoHumanoBundle\Form\Type\RhuRegistroVisitaType;

class RegistroVisitasController extends Controller
{
    /**
     * @Route("/rhu/utilidades/control/acceso/visitante", name="brs_rhu_utilidades_control_acceso_visitante")
     */
    public function registroAction() {
        $request = $this->getRequest();
        $paginator  = $this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 86)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $nombreVisitante = "";
        $arVisitante = new \Brasa\RecursoHumanoBundle\Entity\RhuVisitante();
        $arRegistroVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita();
        $arRegistroVisitas = new \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita();
        $fechaHoy = new \DateTime('now');
        $dql = $em->getRepository('BrasaRecursoHumanoBundle:RhuRegistroVisita')->RegistroHoy($fechaHoy->format('Y/m/d'));
        $arRegistroVisitas = $paginator->paginate($em->createQuery($dql), $request->query->get('page', 1), 50);
        $form = $this->createForm(new RhuRegistroVisitaType, $arRegistroVisita);         
        $form->handleRequest($request);
        if ($form->isValid()) {
            $arrControles = $request->request->All();
            $arRegistroVisita = $form->getData();
            $arRegistroVisita->setNumeroIdentificacion($arrControles['txtNumeroIdentificacion']);
            $arVisitante = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisitante')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
            $registros = count($arVisitante);
            if ($registros == 0){
                $nombreVisitante = "";
            } else {
                $nombreVisitante = $arVisitante->getNombre();
            }
                
            if ($form->get('buscar')->isClicked()){
                $nombreVisitante;
            }else{
            if ($arrControles['txtNumeroIdentificacion'] == ''){
                $objMensaje->Mensaje("error", "Número de identificación es requerido", $this);
            }else {
                if ($arrControles['txtNombreCorto'] == ''){
                    $objMensaje->Mensaje("error", "Nombre del visitante es requerido", $this);
                } else {
                    $arVisitaEntrada = $em->getRepository('BrasaRecursoHumanoBundle:RhuRegistroVisita')->RegistroEntrada($fechaHoy->format('Y/m/d'),$arrControles['txtNumeroIdentificacion']);
                    if (count($arVisitaEntrada) != 0){
                            $objMensaje->Mensaje("error", "El visitante se encuentra registrado", $this);
                        }else {
                            $arRegistroVisita->setFechaEntrada(new \DateTime('now'));
                            //$arRegistroVisita->setNumeroIdentificacion($arrControles['txtNumeroIdentificacion']);
                            $arRegistroVisita->setNombre($arrControles['txtNombreCorto']);
                            $em->persist($arRegistroVisita);
                            //$arVisitante = $em->getRepository('BrasaRecursoHumanoBundle:RhuVisitante')->findOneBy(array('numeroIdentificacion' => $arrControles['txtNumeroIdentificacion']));
                            //$registros = count($arVisitante);
                            if ($registros == 0){
                                $arVisitante = new \Brasa\RecursoHumanoBundle\Entity\RhuVisitante();
                                $arVisitante->setNumeroIdentificacion($arRegistroVisita->getNumeroIdentificacion());
                                $arVisitante->setNombre($arrControles['txtNombreCorto']);
                                $em->persist($arVisitante);
                            }
                            $em->flush();
                            return $this->redirect($this->generateUrl('brs_rhu_utilidades_control_acceso_visitante'));
                        }
                }
            }
        }
        
                            }            
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/RegistroVisitas:registro.html.twig', array(
        'arRegistroVisita' => $arRegistroVisita,
        'arRegistroVisitas' => $arRegistroVisitas,
        'nombreVisitante' => $nombreVisitante,
        'form' => $form->createView()));
    } 
    
    /**
     * @Route("/rhu/utilidades/salida/control/acceso/visitantes/{codigoRegistroVisita}", name="brs_rhu_utilidades_salida_control_acceso_visitantes")
     */
    public function salidaAction($codigoRegistroVisita) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('brs_rhu_salida_control_acceso_visitantes', array('codigoRegistroVisita' => $codigoRegistroVisita)))
            ->add('comentarios', 'textarea', array('required' => false))
            ->add('BtnGuardar', 'submit', array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        $arRegistroVisita = new \Brasa\RecursoHumanoBundle\Entity\RhuRegistroVisita();
        $arRegistroVisita = $em->getRepository('BrasaRecursoHumanoBundle:RhuRegistroVisita')->find($codigoRegistroVisita);
        if ($form->isValid()) {
            //$arHorarioAcceso = $form->getData();
            $arRegistroVisita->setFechaSalida(new \DateTime('now'));
            $arRegistroVisita->setEstado(1);
            $arRegistroVisita->setComentarios($form->get('comentarios')->getData());
            $dateEntrada = $arRegistroVisita->getFechaEntrada();
            $dateSalida = $arRegistroVisita->getFechaSalida();
            $dateDiferencia = date_diff($dateSalida, $dateEntrada);
            $horas = $dateDiferencia->format('%H');
            $minutos = $dateDiferencia->format('%i');
            $segundos = $dateDiferencia->format('%s');
            $diferencia = $horas.":".$minutos.":".$segundos;
            $arRegistroVisita->setDuracionRegistro($diferencia);
            $em->persist($arRegistroVisita);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_rhu_utilidades_control_acceso_visitante'));
        }
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/RegistroVisitas:salida.html.twig', array(
            'arRegistroVisita' => $arRegistroVisita,
            'form' => $form->createView()
        ));
    }
    
        
}
