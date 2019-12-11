<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_dotacion_cargo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuDotacionCargoRepository")
 */
class RhuDotacionCargo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_dotacion_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoDotacionCargoPk;    
    
    /**
     * @ORM\Column(name="codigo_dotacion_elemento_fk", type="integer")
     */
    private $codigoElementoTipoFk;                    

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer")
     */
    private $codigoCargoFk;
    
    /**
     * @ORM\Column(name="cantidad_asignada", type="integer", nullable=true)
     */    
    private $cantidadAsignada = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDotacionElemento", inversedBy="dotacionesCargosDotacionElementoRel")
     * @ORM\JoinColumn(name="codigo_dotacion_elemento_fk", referencedColumnName="codigo_dotacion_elemento_pk")
     */
    protected $dotacionElementoRel;  
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="dotacionesCargosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;    
    

    /**
     * Get codigoDotacionCargoPk
     *
     * @return integer
     */
    public function getCodigoDotacionCargoPk()
    {
        return $this->codigoDotacionCargoPk;
    }

    /**
     * Set codigoElementoTipoFk
     *
     * @param integer $codigoElementoTipoFk
     *
     * @return RhuDotacionCargo
     */
    public function setCodigoElementoTipoFk($codigoElementoTipoFk)
    {
        $this->codigoElementoTipoFk = $codigoElementoTipoFk;

        return $this;
    }

    /**
     * Get codigoElementoTipoFk
     *
     * @return integer
     */
    public function getCodigoElementoTipoFk()
    {
        return $this->codigoElementoTipoFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuDotacionCargo
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set dotacionElementoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionElementoRel
     *
     * @return RhuDotacionCargo
     */
    public function setDotacionElementoRel(\Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento $dotacionElementoRel = null)
    {
        $this->dotacionElementoRel = $dotacionElementoRel;

        return $this;
    }

    /**
     * Get dotacionElementoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDotacionElemento
     */
    public function getDotacionElementoRel()
    {
        return $this->dotacionElementoRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuDotacionCargo
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Set cantidadAsignada
     *
     * @param integer $cantidadAsignada
     *
     * @return RhuDotacionCargo
     */
    public function setCantidadAsignada($cantidadAsignada)
    {
        $this->cantidadAsignada = $cantidadAsignada;

        return $this;
    }

    /**
     * Get cantidadAsignada
     *
     * @return integer
     */
    public function getCantidadAsignada()
    {
        return $this->cantidadAsignada;
    }
}
