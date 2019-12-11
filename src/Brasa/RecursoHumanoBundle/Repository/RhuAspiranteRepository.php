<?php

namespace Brasa\RecursoHumanoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RhuAspiranteRepository extends EntityRepository {
    
    public function listaDQL($strNombre = "", $strIdentificacion = "", $boolBloqueado = "", $boolReintegro = "", $codigoZona = "") {
        $dql   = "SELECT a FROM BrasaRecursoHumanoBundle:RhuAspirante a WHERE a.codigoAspirantePk <> 0 ";
        if($strNombre != "" ) {
            $dql .= " AND a.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND a.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        if($boolBloqueado == 1 ) {
            $dql .= " AND a.bloqueado = 1";
        } 
        if($boolBloqueado == '0') {
            $dql .= " AND a.bloqueado = 0";
        }
        if($boolReintegro == 1 ) {
            $dql .= " AND a.reintegro = 1";
        } 
        if($boolReintegro == '0') {
            $dql .= " AND a.reintegro = 0";
        }
        if($codigoZona != "" ) {
            $dql .= " AND a.codigoZonaFk = " . $codigoZona;
        }
        $dql .= " ORDER BY a.fecha";
        return $dql;
    }
    
    public function aspirantesInconsistenciaDQL($strNombre = "", $strIdentificacion = "") {
        $dql   = "SELECT a FROM BrasaRecursoHumanoBundle:RhuAspirante a WHERE a.codigoAspirantePk <> 0 and a.bloqueado = 1";
        if($strNombre != "" ) {
            $dql .= " AND a.nombreCorto LIKE '%" . $strNombre . "%'";
        }
        if($strIdentificacion != "" ) {
            $dql .= " AND a.numeroIdentificacion LIKE '%" . $strIdentificacion . "%'";
        }
        $dql .= " ORDER BY a.fecha";
        return $dql;
    }

    public function estadoAprobadoAspirantes($codigoAspitante) {
        $em = $this->getEntityManager();
        $strRespuesta = '';
        $arAspitante = new \Brasa\RecursoHumanoBundle\Entity\RhuAspirante();
        $arAspitante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspitante);       
        if ($arAspitante->getEstadoAprobado() == 0){
            
                $arAspitante->setEstadoAprobado(1);
                $arAspitante->setEstadoCerrado(1);

                //Se inserta el aspirante aprobado en la entidad seleccion
                $arSelecion = new \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion();
                $arSelecion->setFecha(new \ DateTime("now"));
                $arSelecion->setCargoRel($arAspitante->getCargoRel());
                $arSelecion->setCiudadRel($arAspitante->getCiudadRel());
                $arSelecion->setCiudadExpedicionRel($arAspitante->getCiudadExpedicionRel());
                $arSelecion->setCiudadNacimientoRel($arAspitante->getCiudadNacimientoRel());
                $arSelecion->setTipoIdentificacionRel($arAspitante->getTipoIdentificacionRel());
                $arSelecion->setEstadoCivilRel($arAspitante->getEstadoCivilRel());
                $arSelecion->setNumeroIdentificacion($arAspitante->getNumeroIdentificacion());
                $arSelecion->setCentroCostoRel($arAspitante->getCentroCostoRel());
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
                $arSelecion->setSeleccionRequisitoRel($arAspitante->getSeleccionRequisitoRel());
                $em->persist($arSelecion);
                $em->flush();
            
        } else {
            $strRespuesta = "El aspirante debe estar sin aprobar";
        }
        return $strRespuesta;
    }

    public function cerrarAspitante($codigoAspitante) {
        $em = $this->getEntityManager();

        $arAspitante = $em->getRepository('BrasaRecursoHumanoBundle:RhuAspirante')->find($codigoAspitante);
        if ($arAspitante->getEstadoCerrado() == 0){
            $arAspitante->setEstadoCerrado(1);
            $em->persist($arAspitante);
            $em->flush();
        }
    }

}