<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuSeleccionRepository extends EntityRepository {
    
    public function listaDQL($strNombre = "", $strIdentificacion = "", $boolCerrado = "", $boolAprobado = "", $codigoCentroCosto = "", $codigoRequisicion = "") {
        $dql   = "SELECT s FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionPk <> 0";
        if($strNombre != "" ) {
            $dql .= " AND s.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND s.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        
        if($boolCerrado == 1 ) {
            $dql .= " AND s.estadoCerrado = 1";
        } elseif($boolCerrado == '0' ) {
            $dql .= " AND s.estadoCerrado = 0";
        }
        
        
        if($boolAprobado == 1 ) {
            $dql .= " AND s.estadoAprobado = 1";
        } elseif($boolAprobado == '0') {
            $dql .= " AND s.estadoAprobado = 0";
        }
        
        if($codigoCentroCosto != "" ) {
            $dql .= " AND s.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        if($codigoRequisicion != "" ) {
            $dql .= " AND s.codigoSeleccionPk = " . $codigoRequisicion;
        }        
        $dql .= " ORDER BY s.fecha desc";
        return $dql;
    }

    public function devuelveNumeroSelecciones($codigoSeleccionRequisito) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionPk) FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.codigoSeleccionRequisitoFk = " . $codigoSeleccionRequisito;
        $query = $em->createQuery($dql);
        $douNumeroSelecciones = $query->getSingleScalarResult();
        return $douNumeroSelecciones;
    }

    public function devuelveNumeroReferencias($id) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionReferenciaPk) FROM BrasaRecursoHumanoBundle:RhuSeleccionReferencia s WHERE s.codigoSeleccionFk = " . $id;
        $query = $em->createQuery($dql);
        $douNumeroReferencias = $query->getSingleScalarResult();
        return $douNumeroReferencias;
    }
    
    public function devuelveNumeroPruebas($id) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionPruebaPk) FROM BrasaRecursoHumanoBundle:RhuSeleccionPrueba s WHERE s.codigoSeleccionFk = " . $id;
        $query = $em->createQuery($dql);
        $douNumeroPruebas = $query->getSingleScalarResult();
        return $douNumeroPruebas;
    }
    
    public function devuelveNumeroVisitas($id) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionVisitaPk) FROM BrasaRecursoHumanoBundle:RhuSeleccionVisita s WHERE s.codigoSeleccionFk = " . $id;
        $query = $em->createQuery($dql);
        $douNumeroVisitas = $query->getSingleScalarResult();
        return $douNumeroVisitas;
    }

    public function devuelveNumeroReferenciasSinVerificar($codigoSeleccion) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoSeleccionReferenciaPk) FROM BrasaRecursoHumanoBundle:RhuSeleccionReferencia s WHERE s.estadoVerificada = 0  and s.codigoSeleccionFk = " . $codigoSeleccion;
        $query = $em->createQuery($dql);
        return $query->getSingleScalarResult();
    }

    public function presentaPruebasSelecciones($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {
                $arSelecciones = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                if ($arSelecciones->getPresentaPruebas() == 0){
                    $arSelecciones->setPresentaPruebas(1);
                }
                $em->persist($arSelecciones);
            }
            $em->flush();
        }
    }

    public function referenciasVerificadsSelecciones($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoSeleccion) {
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
                if($arSeleccion->getReferenciasVerificadas() == 0) {
                    if($em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->devuelveNumeroReferenciasSinVerificar($codigoSeleccion) <= 0) {
                        $arSeleccion->setReferenciasVerificadas(1);
                    }
                }
                $em->persist($arSeleccion);
            }
            $em->flush();

        }
        return false;
    }

    public function estadoAprobadoSelecciones($codigoSeleccion) {
        $em = $this->getEntityManager();
        $strRespuesta = '';
        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);       
        if ($arSeleccion->getEstadoAprobado() == 0){
            $arAspirante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante;
            $arAspirante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->findOneBy(array('numeroIdentificacion' => $arSeleccion->getNumeroIdentificacion()));
            if ($arAspirante == null){
                $bloqueado = 0;
            }else {
                $bloqueado = $arAspirante->getBloqueado();
            }
            if ($bloqueado == 0){
                $intReferenciasVerificadas = $this->devuelveNumeroReferenciasSinVerificar($codigoSeleccion);
                if ($intReferenciasVerificadas == 0){
                    $arSeleccion->setReferenciasVerificadas(1);
                    $arSeleccion->setEstadoAprobado(1);
                    $arSeleccion->setPresentaPruebas(1);
                    $arSeleccion->setEstadoCerrado(1);
                    $arSeleccion->setFechaCierre(new \DateTime('now'));
                    //Se inserta la seleccion aprobada en la entidad examen
                    $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                    $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                    $arEntidadExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen();
                    $arEntidadExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuEntidadExamen')->find($arConfiguracion->getCodigoEntidadExamenIngreso());
                    $arExamenClase = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenClase();
                    $arExamenClase = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenClase')->find(1);
                    $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
                    $arExamen->setFecha(new \ DateTime("now"));
                    //$arExamen->setCentroCostoRel($arSeleccion->getCentroCostoRel());
                    $arExamen->setCargoRel($arSeleccion->getCargoRel());
                    $arExamen->setCiudadRel($arSeleccion->getCiudadRel());
                    $arExamen->setIdentificacion($arSeleccion->getNumeroIdentificacion());
                    $arExamen->setFechaNacimiento($arSeleccion->getFechaNacimiento());
                    $arExamen->setCodigoSexoFk($arSeleccion->getCodigoSexoFk());
                    $arExamen->setNombreCorto($arSeleccion->getNombreCorto());
                    $arExamen->setExamenClaseRel($arExamenClase);
                    $arExamen->setEntidadExamenRel($arEntidadExamen);
                    $arExamen->setControlPago($arConfiguracion->getControlPago());
                    $arExamen->setCodigoUsuario($arSeleccion->getCodigoUsuario());
                    $em->persist($arExamen);
                    $arExamenTipos = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo();
                    $arExamenTipos = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenTipo')->findBy(array('ingreso' => 1));
                    foreach ($arExamenTipos as $arExamenTipo) {
                        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                        $arExamenDetalle->setExamenRel($arExamen);
                        $arExamenDetalle->setExamenTipoRel($arExamenTipo);
                        $floPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->devuelvePrecio($arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk(), $arExamenTipo->getCodigoExamenTipoPk());
                        $arExamenDetalle->setVrPrecio($floPrecio); 
                        $arExamenDetalle->setFechaVence(new \DateTime('now'));
                        $arExamenDetalle->setFechaExamen(new \DateTime('now'));
                        $em->persist($arExamenDetalle);
                    }
                    //examen por cargo insertar
                    $arExamenCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo();
                    $arExamenCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenCargo')->findBy(array('codigoCargoFk' => $arSeleccion->getCodigoCargoFk()));
                    foreach ($arExamenCargo as $arExamenCargo) {
                        $arExamenDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
                        $arExamenDetalle->setExamenRel($arExamen);
                        $arExamenDetalle->setExamenTipoRel($arExamenCargo->getExamenTipoRel());
                        $floPrecio = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenListaPrecio')->devuelvePrecio($arExamen->getEntidadExamenRel()->getCodigoEntidadExamenPk(), $arExamenCargo->getCodigoExamenTipoFk());
                        $arExamenDetalle->setVrPrecio($floPrecio); 
                        $arExamenDetalle->setFechaVence(new \DateTime('now'));
                        $arExamenDetalle->setFechaExamen(new \DateTime('now'));
                        $em->persist($arExamenDetalle);
                    }
                    $em->persist($arSeleccion);
                    $em->flush();
                    $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->liquidar($arExamen->getCodigoExamenPk());
                }else{
                    $strRespuesta = "Todas las referencias deben estar verificadas";
                }
            } else {
                $strRespuesta = "El seleccionado esta bloqueado en la hoja de vida(aspirante)";
            }
        } else {
            $strRespuesta = "El proceso de seleccion debe estar sin aprobar";
        }
        return $strRespuesta;
    }

    public function cerrarSeleccion($codigoSeleccion) {
        $em = $this->getEntityManager();

        $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuSeleccion')->find($codigoSeleccion);
        if ($arSeleccion->getEstadoCerrado() == 0){
            $arSeleccion->setEstadoCerrado(1);
            $em->persist($arSeleccion);
            $em->flush();
        }
    }

    public function pendienteCobrar($codigoCentroCosto) {
        $em = $this->getEntityManager();
        $dql   = "SELECT s FROM BrasaRecursoHumanoBundle:RhuSeleccion s WHERE s.estadoCobrado = 0 "
                . " AND s.codigoCentroCostoFk = " . $codigoCentroCosto;
        return $dql;
    }
}