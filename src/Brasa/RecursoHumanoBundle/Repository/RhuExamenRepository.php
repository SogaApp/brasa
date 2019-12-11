<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuExamenRepository extends EntityRepository {

    public function listaDQL($codigoCentroCosto = "", $strIdentificacion = "", $boolAprobado = "", $boolControlPago = "", $boolAutorizado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuExamen e WHERE e.codigoExamenPk <> 0";
        if($codigoCentroCosto != "" ) {
            $dql .= " AND e.codigoCentroCostoFk = " . $codigoCentroCosto;
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND e.identificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolAprobado == 1 ) {
            $dql .= " AND e.estadoAprobado = 1";
        } elseif($boolAprobado == '0' or $boolAprobado == "") {
            $dql .= " AND e.estadoAprobado = 0";
        }
        if($boolAutorizado == 1 ) {
            $dql .= " AND e.estadoAutorizado = 1";
        } elseif($boolAutorizado == '0' or $boolAutorizado == "") {
            $dql .= " AND e.estadoAutorizado = 0";
        }        
        if($boolControlPago == 1 ) {
            $dql .= " AND e.controlPago = 1";
        } elseif($boolControlPago == '0' or $boolControlPago == "") {
            $dql .= " AND e.controlPago = 0";
        }
        $dql .= " ORDER BY e.codigoExamenPk DESC";
        return $dql;
    }

    public function liquidar($codigoExamen) {
        $em = $this->getEntityManager();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
        $arExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen));
        $douTotal = 0;
        foreach ($arExamenDetalles AS $arExamenDetalle) {
            $douTotal += $arExamenDetalle->getVrPrecio();
        }
        $arExamen->setVrTotal($douTotal);
        $em->persist($arExamen);
        $em->flush();
    }
    
    public function autorizar($codigoExamen) {
        $em = $this->getEntityManager();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);       
        $arExamen->setEstadoAutorizado(1);
        $em->persist($arExamen);
        $em->flush();
    }    

    public function eliminarExamen($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigoExamen) {
                $arSeleccion = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);
                $arSeleccionExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen));
                if ($arSeleccion->getEstadoAutorizado() == 0 ){
                    foreach ($arSeleccionExamenDetalles as $arSeleccionExamenDetalle){
                        $em->remove($arSeleccionExamenDetalle);
                    }
                    $em->remove($arSeleccion);
                }
            }
            $em->flush();
        }
    }

    public function aprobarExamen($codigoExamen) {
        
        $em = $this->getEntityManager(); 
        $strRespuesta = '';
        $arExamen = new \Brasa\RecursoHumanoBundle\Entity\RhuExamen();
        $arExamen = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamen')->find($codigoExamen);        
        if($arExamen->getEstadoAprobado() == 0 && $arExamen->getEstadoAutorizado() == 1) {
            $arExamenDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle();
            $arExamenDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuExamenDetalle')->findBy(array('codigoExamenFk' => $codigoExamen, 'estadoAprobado' => 0));
            if(count($arExamenDetalles) <= 0) {
                $arExamen->setEstadoAprobado(1);
                $em->persist($arExamen);
                //se crea el registro del empleado en requisitos si el examen fue aprobado satisfactoriamente
                $arRequisito = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisito();
                $arRequisito->setFecha(new \ DateTime("now"));
                $arRequisito->setCargoRel($arExamen->getCargoRel());
                $arRequisito->setNumeroIdentificacion($arExamen->getIdentificacion());
                $arRequisito->setNombreCorto($arExamen->getNombreCorto());
                $arRequisito->setCodigoUsuario($arExamen->getCodigoUsuario());
                $em->persist($arRequisito);
                $arRequisitoConceptos = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto();
                $arRequisitoConceptos = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoConcepto')->findBy(array('general' => 1));
                foreach ($arRequisitoConceptos as $arRequisitoConcepto) {
                    $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                    $arRequisitoDetalle->setRequisitoRel($arRequisito);
                    $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoConcepto);
                    $arRequisitoDetalle->setTipo('GENERAL');
                    $arRequisitoDetalle->setCantidad(1);
                    $arRequisitoDetalle->setCantidadPendiente(1);
                    $em->persist($arRequisitoDetalle);
                }
                $arRequisitoCargo = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoCargo();
                $arRequisitoCargo = $em->getRepository('BrasaRecursoHumanoBundle:RhuRequisitoCargo')->findBy(array('codigoCargoFk' => $arExamen->getCodigoCargoFk()));
                foreach ($arRequisitoCargo as $arRequisitoCargo) {
                    $arRequisitoDetalle = new \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoDetalle();
                    $arRequisitoDetalle->setRequisitoRel($arRequisito);
                    $arRequisitoDetalle->setRequisitoConceptoRel($arRequisitoCargo->getRequisitoConceptoRel());
                    $arRequisitoDetalle->setTipo('CARGO');
                    $arRequisitoDetalle->setCantidad(1);
                    $arRequisitoDetalle->setCantidadPendiente(1);
                    $em->persist($arRequisitoDetalle);
                }
                $em->flush();
            } else {
                $strRespuesta = "Todos los detalles del examen deben estar aprobados";
            }
        } else {
            $strRespuesta = "El examen ya esta aprobado o no esta autorizado";
        }
        return $strRespuesta;
    }

    public function devuelveNumeroDetalleExamen($codigoSeleccionGrupo) {
        $em = $this->getEntityManager();
        $dql   = "SELECT COUNT(s.codigoExamenDetallePk) FROM BrasaRecursoHumanoBundle:RhuExamenDetalle s WHERE s.codigoExamenFk = " . $codigoSeleccionGrupo;
        $query = $em->createQuery($dql);
        $douNumeroDetalleExamen = $query->getSingleScalarResult();
        return $douNumeroDetalleExamen;
    }
    
    public function pendienteCobrar($codigoCentroCosto) {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuExamen e WHERE e.estadoCobrado = 0 "
                . " AND e.codigoCentroCostoFk = " . $codigoCentroCosto;
        return $dql;
    }    
    
    public function pendienteCobrarConsulta() {        
        $em = $this->getEntityManager();
        $dql   = "SELECT e FROM BrasaRecursoHumanoBundle:RhuExamen e WHERE e.estadoPagado = 0 ";
        return $dql;
    }
        
}
