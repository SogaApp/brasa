<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_relacion_cumplido")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteRelacionCumplidoRepository")
 */
class TteRelacionCumplido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_relacion_cumplidos_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRelacionCumplidosPk;        
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;        
    
    /**
     * @ORM\Column(name="ct_guias", type="integer")
     */
    private $ctGuias = 0;        

    /**
     * @ORM\Column(name="estado_generada", type="boolean")
     */    
    private $estadoGenerada = 0;      

    /**
     * @ORM\Column(name="estado_descargada", type="boolean")
     */    
    private $estadoDescargada = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;     
       

    /**
     * Get codigoRelacionCumplidosPk
     *
     * @return integer 
     */
    public function getCodigoRelacionCumplidosPk()
    {
        return $this->codigoRelacionCumplidosPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return TteRelacionCumplido
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set ctGuias
     *
     * @param integer $ctGuias
     * @return TteRelacionCumplido
     */
    public function setCtGuias($ctGuias)
    {
        $this->ctGuias = $ctGuias;

        return $this;
    }

    /**
     * Get ctGuias
     *
     * @return integer 
     */
    public function getCtGuias()
    {
        return $this->ctGuias;
    }

    /**
     * Set estadoGenerada
     *
     * @param boolean $estadoGenerada
     * @return TteRelacionCumplido
     */
    public function setEstadoGenerada($estadoGenerada)
    {
        $this->estadoGenerada = $estadoGenerada;

        return $this;
    }

    /**
     * Get estadoGenerada
     *
     * @return boolean 
     */
    public function getEstadoGenerada()
    {
        return $this->estadoGenerada;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     * @return TteRelacionCumplido
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string 
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set estadoDescargada
     *
     * @param boolean $estadoDescargada
     * @return TteRelacionCumplido
     */
    public function setEstadoDescargada($estadoDescargada)
    {
        $this->estadoDescargada = $estadoDescargada;

        return $this;
    }

    /**
     * Get estadoDescargada
     *
     * @return boolean 
     */
    public function getEstadoDescargada()
    {
        return $this->estadoDescargada;
    }
}
