<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_modulo")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenModuloRepository")
 */
class GenModulo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_modulo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoModuloPk;

    /**     
     * @ORM\Column(name="general", type="boolean")
     */    
    private $general = false;     
    
    /**     
     * @ORM\Column(name="contabilidad", type="boolean")
     */    
    private $contabilidad = false;         
    
    /**     
     * @ORM\Column(name="recurso_humano", type="boolean")
     */    
    private $recurso_humano = false;         

    /**     
     * @ORM\Column(name="transporte", type="boolean")
     */    
    private $transporte = false; 
    
    /**     
     * @ORM\Column(name="turno", type="boolean")
     */    
    private $turno = false; 

    /**     
     * @ORM\Column(name="cartera", type="boolean")
     */    
    private $cartera = false;     
    
    /**     
     * @ORM\Column(name="afiliacion", type="boolean")
     */    
    private $afiliacion = false;     

    /**     
     * @ORM\Column(name="inventario", type="boolean")
     */    
    private $inventario = false;     

    /**
     * Get codigoModuloPk
     *
     * @return integer
     */
    public function getCodigoModuloPk()
    {
        return $this->codigoModuloPk;
    }

    /**
     * Set general
     *
     * @param boolean $general
     *
     * @return GenModulo
     */
    public function setGeneral($general)
    {
        $this->general = $general;

        return $this;
    }

    /**
     * Get general
     *
     * @return boolean
     */
    public function getGeneral()
    {
        return $this->general;
    }

    /**
     * Set contabilidad
     *
     * @param boolean $contabilidad
     *
     * @return GenModulo
     */
    public function setContabilidad($contabilidad)
    {
        $this->contabilidad = $contabilidad;

        return $this;
    }

    /**
     * Get contabilidad
     *
     * @return boolean
     */
    public function getContabilidad()
    {
        return $this->contabilidad;
    }

    /**
     * Set recursoHumano
     *
     * @param boolean $recursoHumano
     *
     * @return GenModulo
     */
    public function setRecursoHumano($recursoHumano)
    {
        $this->recurso_humano = $recursoHumano;

        return $this;
    }

    /**
     * Get recursoHumano
     *
     * @return boolean
     */
    public function getRecursoHumano()
    {
        return $this->recurso_humano;
    }

    /**
     * Set transporte
     *
     * @param boolean $transporte
     *
     * @return GenModulo
     */
    public function setTransporte($transporte)
    {
        $this->transporte = $transporte;

        return $this;
    }

    /**
     * Get transporte
     *
     * @return boolean
     */
    public function getTransporte()
    {
        return $this->transporte;
    }

    /**
     * Set turno
     *
     * @param boolean $turno
     *
     * @return GenModulo
     */
    public function setTurno($turno)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return boolean
     */
    public function getTurno()
    {
        return $this->turno;
    }

    /**
     * Set cartera
     *
     * @param boolean $cartera
     *
     * @return GenModulo
     */
    public function setCartera($cartera)
    {
        $this->cartera = $cartera;

        return $this;
    }

    /**
     * Get cartera
     *
     * @return boolean
     */
    public function getCartera()
    {
        return $this->cartera;
    }

    /**
     * Set afiliacion
     *
     * @param boolean $afiliacion
     *
     * @return GenModulo
     */
    public function setAfiliacion($afiliacion)
    {
        $this->afiliacion = $afiliacion;

        return $this;
    }

    /**
     * Get afiliacion
     *
     * @return boolean
     */
    public function getAfiliacion()
    {
        return $this->afiliacion;
    }

    /**
     * Set inventario
     *
     * @param boolean $inventario
     *
     * @return GenModulo
     */
    public function setInventario($inventario)
    {
        $this->inventario = $inventario;

        return $this;
    }

    /**
     * Get inventario
     *
     * @return boolean
     */
    public function getInventario()
    {
        return $this->inventario;
    }
}
