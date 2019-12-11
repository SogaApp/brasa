<?php

namespace Brasa\AfiliacionBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AfiCursoRepository extends EntityRepository {  
    
    public function listaDql($numero = "", $codigoCliente = "", $boolEstadoAutorizado = "", $boolAsistencia = "", $boolEstadoFacturado = "", $boolEstadoPagado = "", $boolEstadoAnulado = "", $strFechaDesde = "", $strFechaHasta = "", $codigoEmpleado = "") {
        $em = $this->getEntityManager();
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiCurso c WHERE c.codigoCursoPk <> 0";
        if($numero != "") {
            $dql .= " AND c.numero = " . $numero;  
        }        
        if($codigoCliente != "") {
            $dql .= " AND c.codigoClienteFk = " . $codigoCliente;  
        }  
        if($codigoEmpleado != "") {
            $dql .= " AND c.codigoEmpleadoFk = " . $codigoEmpleado;  
        }        
        if($boolEstadoAutorizado == 1 ) {
            $dql .= " AND c.estadoAutorizado = 1";
        }
        if($boolEstadoAutorizado == "0") {
            $dql .= " AND c.estadoAutorizado = 0";
        }        
        if($boolAsistencia == 1 ) {
            $dql .= " AND c.asistencia = 1";
        }
        if($boolAsistencia == "0") {
            $dql .= " AND c.asistencia = 0";
        }    
        if($boolEstadoFacturado == 1 ) {
            $dql .= " AND c.estadoFacturado = 1";
        }
        if($boolEstadoFacturado == "0") {
            $dql .= " AND c.estadoFacturado = 0";
        }
        if($boolEstadoPagado == 1 ) {
            $dql .= " AND c.estadoPagado = 1";
        }
        if($boolEstadoPagado == "0") {
            $dql .= " AND c.estadoPagado = 0";
        }        
        if($boolEstadoAnulado == 1 ) {
            $dql .= " AND c.estadoAnulado = 1";
        }
        if($boolEstadoAnulado == "0") {
            $dql .= " AND c.estadoAnulado = 0";
        }        
        if($strFechaDesde != "") {
            $dql .= " AND c.fecha >= '" . $strFechaDesde . "'";
        }        
        if($strFechaHasta != "") {
            $dql .= " AND c.fecha <= '" . $strFechaHasta . "'";
        }        
        $dql .= " ORDER BY c.fecha DESC";
        return $dql;
    }            
    
    public function pendienteDql($codigoCliente) {        
        $dql   = "SELECT c FROM BrasaAfiliacionBundle:AfiCurso c WHERE c.estadoFacturado = 0 AND c.estadoAnulado = 0 AND c.codigoClienteFk = " . $codigoCliente;
        $dql .= " ORDER BY c.codigoCursoPk DESC";
        return $dql;
    }                                              
    
    public function eliminar($arrSeleccionados) {
        $em = $this->getEntityManager();
        if(count($arrSeleccionados) > 0) {
            foreach ($arrSeleccionados AS $codigo) {
                $ar = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigo);
                if($ar->getNumero() <= 0) {
                    $em->remove($ar);
                }                
            }
            $em->flush();
        }
    }        
    
    public function liquidar($codigoCurso) {        
        $em = $this->getEntityManager();        
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();        
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);                 
        $costo = 0;        
        $floSubTotal = 0;        
        $arCursosDetalle = new \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle();        
        $arCursosDetalle = $em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->findBy(array('codigoCursoFk' => $codigoCurso));                 
        foreach ($arCursosDetalle as $arCursoDetalle) {
            $costo +=  $arCursoDetalle->getCosto();
            $floSubTotal +=  $arCursoDetalle->getPrecio();
        }                           
        $arCurso->setCosto($costo);
        $arCurso->setTotal($floSubTotal);
        $em->persist($arCurso);
        $em->flush();
        return true;
    }
    
    public function autorizar($codigoCurso) {
        $em = $this->getEntityManager();                
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);            
        $strResultado = "";        
        if($arCurso->getEstadoAutorizado() == 0) {
            if($em->getRepository('BrasaAfiliacionBundle:AfiCursoDetalle')->numeroRegistros($codigoCurso) > 0) {                            
                if($strResultado == "") {
                    $arCurso->setEstadoAutorizado(1);
                    $em->persist($arCurso);
                    $em->flush();                              
                }
              
            } else {
                $strResultado = "Debe adicionar detalles";
            }            
        } else {
            $strResultado = "Ya esta autorizado";
        }        
        return $strResultado;
    } 
    
    public function anular($codigoCurso) {        
        $em = $this->getEntityManager();        
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();        
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);                                                        
        $arCurso->setEstadoAnulado(1);
        $arCurso->setTotal(0);
        $arCurso->setCosto(0);        
        $em->persist($arCurso);
        $em->flush();
        return "";
    }        
    
    public function desAutorizar($codigoCurso) {
        $em = $this->getEntityManager();                
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);            
        $strResultado = "";        
        if($arCurso->getEstadoAutorizado() == 1 && $arCurso->getEstadoAnulado() == 0) {                                            
            $arCurso->setEstadoAutorizado(0);
            $em->persist($arCurso);
            $em->flush();                                                        
        } else {
            $strResultado = "El curso debe estas autorizado y no puede estar anulada o impresa";
        }        
        return $strResultado;
    }    
    
    public function imprimir($codigoCurso) {
        $em = $this->getEntityManager();        
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $strResultado = "";
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();        
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);        
        if($arCurso->getEstadoAutorizado() == 1) {
            if($arCurso->getNumero() == 0) {            
                $intNumero = $em->getRepository('BrasaAfiliacionBundle:AfiConsecutivo')->consecutivo(1);
                $arCurso->setNumero($intNumero);
                /*$arServicio = new \Brasa\AfiliacionBundle\Entity\AfiServicio();
                $arServicio->setClienteRel($arCurso->getClienteRel());
                $arServicio->setCurso($arCurso->getTotal());
                $arServicio->setTotal($arCurso->getTotal());
                $arServicio->setPendiente($arCurso->getTotal());
                $em->persist($arServicio);                
                 * 
                 */
            }   
            $em->persist($arCurso);
            $em->flush();
        } else {
            $strResultado = "Debe autorizar el curso para imprimir";
        }
        return $strResultado;
    }    
    
    public function facturar($codigoCurso, $usuario, $tipo) {
        $em = $this->getEntityManager();
        $objFunciones = new \Brasa\GeneralBundle\MisClases\Funciones();
        $codigoFactura = 0;
        $arCurso = new \Brasa\AfiliacionBundle\Entity\AfiCurso();
        $arCurso = $em->getRepository('BrasaAfiliacionBundle:AfiCurso')->find($codigoCurso);             
        $arCurso->setEstadoFacturado(1);
        $em->persist($arCurso);    
        $arFacturaTipo = new \Brasa\AfiliacionBundle\Entity\AfiFacturaTipo();
        $arFacturaTipo = $em->getRepository('BrasaAfiliacionBundle:AfiFacturaTipo')->find($tipo);          
        $arFactura = new \Brasa\AfiliacionBundle\Entity\AfiFactura();
        $arFactura->setFecha(new \DateTime('now'));
        $dateFechaVence = $objFunciones->sumarDiasFecha($arCurso->getClienteRel()->getPlazoPago(), $arFactura->getFecha());
        $arFactura->setFacturaTipoRel($arFacturaTipo);
        $arFactura->setFechaVence($dateFechaVence);            
        $arFactura->setClienteRel($arCurso->getClienteRel());         
        $arFactura->setUsuario($usuario);             
        $em->persist($arFactura);                        
        $arFacturaDetalleCurso = new \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso();
        $arFacturaDetalleCurso->setFacturaRel($arFactura);
        $arFacturaDetalleCurso->setCursoRel($arCurso);
        $arFacturaDetalleCurso->setPrecio($arCurso->getTotal());
        $arFacturaDetalleCurso->setTotal($arCurso->getTotal());
        $em->persist($arFacturaDetalleCurso);        
        $em->flush();  
        $codigoFactura = $arFactura->getCodigoFacturaPk();
        $em->getRepository('BrasaAfiliacionBundle:AfiFactura')->liquidar($codigoFactura);                    
        return $codigoFactura;
    }        
}