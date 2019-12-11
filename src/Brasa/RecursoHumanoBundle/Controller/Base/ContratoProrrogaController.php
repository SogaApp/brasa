<?php

namespace Brasa\RecursoHumanoBundle\Controller\Base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

class ContratoProrrogaController extends Controller
{
    /**
     * @Route("/rhu/contrato/prorroga/nuevo/{codigoContrato}/{codigoContratoProrroga}", name="brs_rhu_contrato_prorroga_nuevo")
     */
    public function nuevoAction(Request $request, $codigoContrato, $codigoContratoProrroga = 0) {
        $em = $this->getDoctrine()->getManager();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        $arContratoProrroga = new \Brasa\RecursoHumanoBundle\Entity\RhuContratoProrroga();
        $arContrato = new \Brasa\RecursoHumanoBundle\Entity\RhuContrato();
        $arContrato = $em->getRepository('BrasaRecursoHumanoBundle:RhuContrato')->find($codigoContrato);
        if ($codigoContratoProrroga != 0)
        {
            $arContratoProrroga = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoProrroga')->find($codigoContratoProrroga);
            $fechaDesdeProrroga = $arContratoProrroga->getFechaInicialNueva();
            $fechaHastaProrroga = $arContratoProrroga->getFechaFinalNueva();
            $detalle = $arContratoProrroga->getDetalle();
        } else {
            $fechaDesdeProrroga = new \DateTime('now');
            $fechaHastaProrroga = new \DateTime('now');
            $detalle = $arContratoProrroga->getDetalle();
        }    
        $form = $this->createFormBuilder()
            ->add('fechaInicioNueva', DateType::class, array('data' => $fechaDesdeProrroga, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('fechaFinalNueva', DateType::class, array('data' => $fechaHastaProrroga, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('detalle', TextType::class, array('data' => $detalle,'required' => true))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid())
        {
            $arUsuario = $this->get('security.context')->getToken()->getUser();
            $boolValidarContratoFijo = TRUE;
            $fechaDesde = $form->get('fechaInicioNueva')->getData();
            $fechaHasta = $form->get('fechaFinalNueva')->getData();
            $nuevafecha = strtotime ( '+1 year' , strtotime ( $fechaDesde->format('Y-m-d'))) ;
            $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
            if ($fechaHasta->format('Y-m-d') >= $nuevafecha){
                 $boolValidarContratoFijo = FALSE;
            }
            if ($fechaHasta <= $fechaDesde ){
               $objMensaje->Mensaje("error", "La fecha hasta no puede ser menor o igual a la fecha desde", $this);
            } else {
               if ($boolValidarContratoFijo == FALSE){
                    $objMensaje->Mensaje("error", "La prorroga no puede ser mayor o igual a un año", $this);;
               } else {
                    if ($codigoContratoProrroga == 0){
                        if ($arContrato->getFechaProrrogaInicio() == null){
                            if ($arContrato->getFechaHasta() >= $fechaDesde || $arContrato->getFechaHasta() >= $fechaHasta){
                                $objMensaje->Mensaje("error", "La fecha desde y/o hasta ya estan en el contrato inicial, si desea realizar una prorroga debe asignar fecha diferentes al del contrato inicial", $this);
                            } else {
                                //codigo nueva prorroga
                                $arContratoProrroga->setContratoRel($arContrato);
                                $arContratoProrroga->setCodigoEmpleadoFk($arContrato->getEmpleadoRel()->getCodigoEmpleadoPk());
                                $arContratoProrroga->setFecha(new \DateTime('now'));
                                if ($arContrato->getFechaProrrogaInicio() == null){
                                    $arContratoProrroga->setFechaInicialAnterior($arContrato->getFechaDesde());
                                    $arContratoProrroga->setFechaFinalAnterior($arContrato->getFechaHasta());
                                }
                                else {
                                    $arContratoProrroga->setFechaInicialAnterior($arContrato->getFechaProrrogaInicio());
                                    $arContratoProrroga->setFechaFinalAnterior($arContrato->getFechaProrrogaFinal());
                                }
                                $arContratoProrroga->setFechaInicialNueva($form->get('fechaInicioNueva')->getData());
                                $arContratoProrroga->setFechaFinalNueva($form->get('fechaFinalNueva')->getData());
                                $dateFechaInicialProrroga = $form->get('fechaInicioNueva')->getData();
                                $dateFechaFinalProrroga = $form->get('fechaFinalNueva')->getData();
                                //inicio calculo meses           
                                $interval = $dateFechaInicialProrroga->diff($dateFechaFinalProrroga);
                                $interval = round($interval->format('%a%') / 30);
                                //fin calculo meses
                                $arContratoProrroga->setMeses($interval);
                                $arContratoProrroga->setDetalle($form->get('detalle')->getData());
                                $arContratoProrroga->setEstadoVigente(1);
                                $arContratoProrroga->setCodigoUsuario($arUsuario->getUserName());
                                $arContrato->setFechaHasta($form->get('fechaFinalNueva')->getData());
                                $arContrato->setFechaProrrogaInicio($form->get('fechaInicioNueva')->getData());
                                $arContrato->setFechaProrrogaFinal($form->get('fechaFinalNueva')->getData());
                                $em->persist($arContratoProrroga);
                                $em->persist($arContrato);
                                $em->flush();                                
                                echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
                            }
                        }
                        $arRegistosContratosProrrogas = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoProrroga')->contratoProrroga($codigoContrato,$fechaDesde,$fechaHasta,"");
                        if ($arRegistosContratosProrrogas == null){
                            //codigo nueva prorroga
                            $arContratoProrroga->setContratoRel($arContrato);
                            $arContratoProrroga->setCodigoEmpleadoFk($arContrato->getEmpleadoRel()->getCodigoEmpleadoPk());
                            $arContratoProrroga->setFecha(new \DateTime('now'));
                            if ($arContrato->getFechaProrrogaInicio() == null){
                                $arContratoProrroga->setFechaInicialAnterior($arContrato->getFechaDesde());
                                $arContratoProrroga->setFechaFinalAnterior($arContrato->getFechaHasta());
                            }
                            else {
                                $arContratoProrroga->setFechaInicialAnterior($arContrato->getFechaProrrogaInicio());
                                $arContratoProrroga->setFechaFinalAnterior($arContrato->getFechaProrrogaFinal());
                            }
                            $arContratoProrroga->setFechaInicialNueva($form->get('fechaInicioNueva')->getData());
                            $arContratoProrroga->setFechaFinalNueva($form->get('fechaFinalNueva')->getData());
                            $dateFechaInicialProrroga = $form->get('fechaInicioNueva')->getData();
                            $dateFechaFinalProrroga = $form->get('fechaFinalNueva')->getData();
                            //inicio calculo meses           
                            $interval = $dateFechaInicialProrroga->diff($dateFechaFinalProrroga);
                            $interval = round($interval->format('%a%') / 30);
                            //fin calculo meses
                            $arContratoProrroga->setMeses($interval);
                            $arContratoProrroga->setDetalle($form->get('detalle')->getData());
                            $arContratoProrroga->setEstadoVigente(1);
                            $arContratoProrroga->setCodigoUsuario($arUsuario->getUserName());
                            $arContrato->setFechaHasta($form->get('fechaFinalNueva')->getData());
                            $arContrato->setFechaProrrogaInicio($form->get('fechaInicioNueva')->getData());
                            $arContrato->setFechaProrrogaFinal($form->get('fechaFinalNueva')->getData());
                            $em->persist($arContratoProrroga);
                            $em->persist($arContrato);
                            $em->flush();
                            $strSql = "UPDATE rhu_contrato_prorroga SET estado_vigente = '0' WHERE codigo_contrato_prorroga_pk <> " . $arContratoProrroga->getCodigoContratoProrrogaPk() ." AND codigo_contrato_fk = " . $codigoContrato . " ";           
                            $em->getConnection()->executeQuery($strSql);
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";                 
                        } else {
                            $objMensaje->Mensaje("error", "La fecha desde y/o hasta ya estan en prorrogas anteriores en este contrato", $this);
                        }
                    } else {
                        $arRegistosContratosProrrogas = $em->getRepository('BrasaRecursoHumanoBundle:RhuContratoProrroga')->contratoProrroga($codigoContrato,$fechaDesde,$fechaHasta,$codigoContratoProrroga);
                        if ($arRegistosContratosProrrogas == null){
                            //codigo editar prorroga
                            $arContratoProrroga->setContratoRel($arContrato);
                            $arContratoProrroga->setCodigoEmpleadoFk($arContrato->getEmpleadoRel()->getCodigoEmpleadoPk());
                            $arContratoProrroga->setFecha(new \DateTime('now'));
                            $dateFechaInicialProrroga = $form->get('fechaInicioNueva')->getData();
                            $dateFechaFinalProrroga = $form->get('fechaFinalNueva')->getData();
                            //inicio calculo meses           
                            $interval = $dateFechaInicialProrroga->diff($dateFechaFinalProrroga);
                            $interval = round($interval->format('%a%') / 30);
                            //fin calculo meses
                            $arContratoProrroga->setMeses($interval);
                            $arContratoProrroga->setDetalle($form->get('detalle')->getData());
                            $arContratoProrroga->setFechaInicialNueva($form->get('fechaInicioNueva')->getData());
                            $arContratoProrroga->setFechaFinalNueva($form->get('fechaFinalNueva')->getData());
                            $arContrato->setFechaHasta($form->get('fechaFinalNueva')->getData());
                            $arContrato->setFechaProrrogaInicio($form->get('fechaInicioNueva')->getData());
                            $arContrato->setFechaProrrogaFinal($form->get('fechaFinalNueva')->getData());
                            $em->persist($arContratoProrroga);
                            $em->persist($arContrato);
                            $em->flush();
                            echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>"; 
                        } else {
                            $objMensaje->Mensaje("error", "La fecha desde y/o hasta ya estan en prorrogas anteriores en este contrato", $this);
                        }
                        
                      }        
                }   
            }
        }
        return $this->render('BrasaRecursoHumanoBundle:ContratoProrroga:nuevo.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
