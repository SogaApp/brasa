<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_festivo")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenFestivoRepository")
 */
class GenFestivo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_festivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoFestivoPk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;     
    

    /**
     * Get codigoFestivoPk
     *
     * @return integer
     */
    public function getCodigoFestivoPk()
    {
        return $this->codigoFestivoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return GenFestivo
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
}
