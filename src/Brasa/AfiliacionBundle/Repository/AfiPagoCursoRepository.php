<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiPagoCursoRepository extends EntityRepository {  
    
    public function listaDql() {
        $em = $this->getEntityManager();
        $dql   = "SELECT pc FROM BrasaAfiliacionBundle:AfiPagoCurso pc WHERE pc.codigoPagoCursoPk <> 0";
        $dql .= " ORDER BY pc.codigoPagoCursoPk";
        return $dql;
    }            
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigo);
                $em->remove($ar);
            }
            $em->flush();
        }
    }             

    public function liquidar($codigoPagoCurso) {        
        $em = $this->getEntityManager();        
        $arPagoCurso = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();        
        $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);                 
        $total = 0;        
        $arPagoCursosDetalle = new \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle();        
        $arPagoCursosDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCursoDetalle')->findBy(array('codigoPagoCursoFk' => $codigoPagoCurso));                 
        foreach ($arPagoCursosDetalle as $arPagoCursoDetalle) {
            $total +=  $arPagoCursoDetalle->getCosto();
        }                                          
        $arPagoCurso->setTotal($total);
        $em->persist($arPagoCurso);
        $em->flush();
        return true;
    }
    
    public function autorizar($codigoPagoCurso) {
        $em = $this->getEntityManager();                
        $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);            
        $strResultado = "";        
        if($arPagoCurso->getEstadoAutorizado() == 0) {            
            if($strResultado == "") {
                $arPagoCurso->setEstadoAutorizado(1);
                $em->persist($arPagoCurso);
                $em->flush();                              
            }                          
        } else {
            $strResultado = "Ya esta autorizado";
        }        
        return $strResultado;
    } 
    
    public function desAutorizar($codigoPagoCurso) {
        $em = $this->getEntityManager();                
        $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);            
        $strResultado = "";        
        if($arPagoCurso->getEstadoAutorizado() == 1 && $arPagoCurso->getEstadoAnulado() == 0 && $arPagoCurso->getNumero() == 0) {                                            
            $arPagoCurso->setEstadoAutorizado(0);
            $em->persist($arPagoCurso);
            $em->flush();                                                        
        } else {
            $strResultado = "El factura debe estas autorizado y no puede estar anulada o impresa";
        }        
        return $strResultado;
    }    
    
    public function imprimir($codigoPagoCurso) {
        $em = $this->getEntityManager();                
        $strResultado = "";
        $arPagoCurso = new \Brasa\AfiliacionBundle\Entity\AfiPagoCurso();        
        $arPagoCurso = $em->getRepository('BrasaAfiliacionBundle:AfiPagoCurso')->find($codigoPagoCurso);        
        if($arPagoCurso->getEstadoAutorizado() == 1) {
            if($arPagoCurso->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(4);   
                $arPagoCurso->setNumero($intNumero);              
            }              
            $em->persist($arPagoCurso);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar la factura para imprimir";
        }
        return $strResultado;
    }            
}