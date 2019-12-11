<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="gen_clasificacion_tributaria")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenClasificacionTributariaRepository")
 */
class GenClasificacionTributaria
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_clasificacion_tributaria_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoClasificacionTributariaPk;

    /**
     * @ORM\Column(name="nombre_clasificacion_tributaria", type="string", length=50, nullable=true)
     */
    private $nombreClasificacionTributaria;

    /**
     * @ORM\Column(name="retencion_iva_ventas", type="boolean")
     */    
    private $retencionIvaVentas = 0;    

    /**
     * @ORM\Column(name="retencion_cree", type="boolean")
     */    
    private $retencionCREE = 0;        

    /**
     * @ORM\Column(name="retencion_fuente", type="boolean")
     */    
    private $retencionFuente = 0;     
    

    /**
     * Get codigoClasificacionTributariaPk
     *
     * @return integer
     */
    public function getCodigoClasificacionTributariaPk()
    {
        return $this->codigoClasificacionTributariaPk;
    }

    /**
     * Set nombreClasificacionTributaria
     *
     * @param string $nombreClasificacionTributaria
     *
     * @return GenClasificacionTributaria
     */
    public function setNombreClasificacionTributaria($nombreClasificacionTributaria)
    {
        $this->nombreClasificacionTributaria = $nombreClasificacionTributaria;

        return $this;
    }

    /**
     * Get nombreClasificacionTributaria
     *
     * @return string
     */
    public function getNombreClasificacionTributaria()
    {
        return $this->nombreClasificacionTributaria;
    }

    /**
     * Set retencionIvaVentas
     *
     * @param boolean $retencionIvaVentas
     *
     * @return GenClasificacionTributaria
     */
    public function setRetencionIvaVentas($retencionIvaVentas)
    {
        $this->retencionIvaVentas = $retencionIvaVentas;

        return $this;
    }

    /**
     * Get retencionIvaVentas
     *
     * @return boolean
     */
    public function getRetencionIvaVentas()
    {
        return $this->retencionIvaVentas;
    }

    /**
     * Set retencionCREE
     *
     * @param boolean $retencionCREE
     *
     * @return GenClasificacionTributaria
     */
    public function setRetencionCREE($retencionCREE)
    {
        $this->retencionCREE = $retencionCREE;

        return $this;
    }

    /**
     * Get retencionCREE
     *
     * @return boolean
     */
    public function getRetencionCREE()
    {
        return $this->retencionCREE;
    }

    /**
     * Set retencionFuente
     *
     * @param boolean $retencionFuente
     *
     * @return GenClasificacionTributaria
     */
    public function setRetencionFuente($retencionFuente)
    {
        $this->retencionFuente = $retencionFuente;

        return $this;
    }

    /**
     * Get retencionFuente
     *
     * @return boolean
     */
    public function getRetencionFuente()
    {
        return $this->retencionFuente;
    }
}
