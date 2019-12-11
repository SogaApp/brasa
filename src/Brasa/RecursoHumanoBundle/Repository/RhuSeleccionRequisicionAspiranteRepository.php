<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionRequisicionAspiranteRepository extends EntityRepository {
    
    public function eliminarDetallesSeleccionados($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoRequisicionDetalle) {                
                $arRequisicionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->find($codigoRequisicionDetalle);
                if ($arRequisicionDetalle->getEstadoAprobado() == 0) {
                    $em->remove($arRequisicionDetalle);
                }
                  
            }                                         
        }
        $em->flush();       
    }
    
    public function aprobarDetallesSeleccionados($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoRequisicionDetalle) {
                $arRequisicionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
                $arRequisicionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->find($codigoRequisicionDetalle);
                if ($arRequisicionDetalle->getEstadoAprobado()== 0){
                    $arRequisicionDetalle->setEstadoAprobado(1);
                    $arAspitante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
                    $arAspitante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($arRequisicionDetalle->getCodigoAspiranteFk());       
                    //Se inserta el aspirante aprobado en la entidad seleccion
                    $arSelecion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                    $arSelecion->setFecha(new \ DateTime("now"));
                    $arSelecion->setCargoRel($arRequisicionDetalle->getSeleccionRequisitoRel()->getCargoRel());
                    $arSelecion->setCiudadRel($arAspitante->getCiudadRel());
                    $arSelecion->setCiudadExpedicionRel($arAspitante->getCiudadExpedicionRel());
                    $arSelecion->setCiudadNacimientoRel($arAspitante->getCiudadNacimientoRel());
                    $arSelecion->setTipoIdentificacionRel($arAspitante->getTipoIdentificacionRel());
                    $arSelecion->setEstadoCivilRel($arAspitante->getEstadoCivilRel());
                    $arSelecion->setNumeroIdentificacion($arAspitante->getNumeroIdentificacion());
                    $arSelecion->setCentroCostoRel($arRequisicionDetalle->getSeleccionRequisitoRel()->getCentroCostoRel());
                    $arSelecion->setFechaNacimiento($arAspitante->getFechaNacimiento());
                    $arSelecion->setCodigoSexoFk($arAspitante->getCodigoSexoFk());
                    $arSelecion->setRhRel($arAspitante->getRhRel());
                    $arSelecion->setNombreCorto($arAspitante->getNombreCorto());
                    $arSelecion->setNombre1($arAspitante->getNombre1());
                    $arSelecion->setNombre2($arAspitante->getNombre2());
                    $arSelecion->setApellido1($arAspitante->getApellido1());
                    $arSelecion->setApellido2($arAspitante->getApellido2());
                    $arSelecion->setTelefono($arAspitante->getTelefono());
                    $arSelecion->setCelular($arAspitante->getCelular());
                    $arSelecion->setDireccion($arAspitante->getDireccion());
                    $arSelecion->setCodigoUsuario($arAspitante->getCodigoUsuario());
                    $em->persist($arSelecion);
                    $arSeleccionTipo = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionTipo();
                    $arSeleccionTipo = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionTipo')->find(3);
                    $arSelecion->setSeleccionTipoRel($arSeleccionTipo);
                    $arSelecion->setSeleccionRequisitoRel($arRequisicionDetalle->getSeleccionRequisitoRel());
                    $em->persist($arSelecion);
                }
            }                                            
        }
            $em->flush();       
    }
    
    public function desaprobarDetallesSeleccionados($arrSeleccionados) {
        $em = $this->getEntityManager();
        $mensaje = '';
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoRequisicionDetalle) {
                $arRequisicionDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante();
                $arRequisicionDetalle = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionRequisicionAspirante')->find($codigoRequisicionDetalle);
                $codigoRequisicion = $arRequisicionDetalle->getCodigoSeleccionRequisitoFk();
                if ($arRequisicionDetalle->getEstadoAprobado() == 1){
                    
                    $arAspitante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
                    $arAspitante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($arRequisicionDetalle->getCodigoAspiranteFk());
                    $numeroIdentificacion = $arAspitante->getNumeroIdentificacion();
                    
                    $arSeleccion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                    $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->findBy(array('codigoSeleccionRequisitoFk' => $codigoRequisicion, 'numeroIdentificacion' => $numeroIdentificacion));
                    if ($arSeleccion){
                        
                        foreach ($arSeleccion as $arSeleccion){
                            if ($arSeleccion->getEstadoCerrado() == 1 || $arSeleccion->getEstadoAprobado() == 1){
                                $mensaje = "El proceso de seleccion esta aprobado y/o cerrado, no se puede desaprobar";
                            } else {
                              if ($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevista')->findBy(array('codigoSeleccionFk' => $arSeleccion)) || $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionPrueba')->findBy(array('codigoSeleccionFk' => $arSeleccion)) || $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionReferencia')->findBy(array('codigoSeleccionFk' => $arSeleccion)) || $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccionEntrevista')->findBy(array('codigoSeleccionFk' => $arSeleccion))){
                                $mensaje = "El proceso de seleccion tiene detalles asociados, no se puede desaprobar";   
                              } else {
                                  $em->remove($arSeleccion);
                                  
                              }  
                            }

                        }
                    $arRequisicionDetalle->setEstadoAprobado(0);
                    $em->persist($arRequisicionDetalle);
                    } 
                    
                    $em->flush();
                    
                }
            }                                            
        }
        return $mensaje;           
    }

}