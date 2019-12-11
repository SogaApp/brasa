<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_cargo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenCargoRepository")
 */
class RhuExamenCargo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_cargo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenCargoPk;    
    
    /**
     * @ORM\Column(name="codigo_examen_tipo_fk", type="integer")
     */
    private $codigoExamenTipoFk;                    

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer")
     */
    private $codigoCargoFk; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenTipo", inversedBy="examenesCargosExamenTipoRel")
     * @ORM\JoinColumn(name="codigo_examen_tipo_fk", referencedColumnName="codigo_examen_tipo_pk")
     */
    protected $examenTipoRel;  
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="examenesCargosCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;    
    
    

    /**
     * Get codigoExamenCargoPk
     *
     * @return integer
     */
    public function getCodigoExamenCargoPk()
    {
        return $this->codigoExamenCargoPk;
    }

    /**
     * Set codigoExamenTipoFk
     *
     * @param integer $codigoExamenTipoFk
     *
     * @return RhuExamenCargo
     */
    public function setCodigoExamenTipoFk($codigoExamenTipoFk)
    {
        $this->codigoExamenTipoFk = $codigoExamenTipoFk;

        return $this;
    }

    /**
     * Get codigoExamenTipoFk
     *
     * @return integer
     */
    public function getCodigoExamenTipoFk()
    {
        return $this->codigoExamenTipoFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuExamenCargo
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
     * Set examenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel
     *
     * @return RhuExamenCargo
     */
    public function setExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo $examenTipoRel = null)
    {
        $this->examenTipoRel = $examenTipoRel;

        return $this;
    }

    /**
     * Get examenTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenTipo
     */
    public function getExamenTipoRel()
    {
        return $this->examenTipoRel;
    }

    /**
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuExamenCargo
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
}
