<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cierre_anio")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCierreAnioRepository")
 */
class RhuCierreAnio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cierre_anio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCierreAnioPk;    
    
    /**
     * @ORM\Column(name="anio", type="integer")
     */    
    private $anio = 0;
    
    /**
     * @ORM\Column(name="estadoCerrado", type="boolean")
     */    
    private $estadoCerrado = 0;    
    

    /**
     * Get codigoCierreAnioPk
     *
     * @return integer
     */
    public function getCodigoCierreAnioPk()
    {
        return $this->codigoCierreAnioPk;
    }

    /**
     * Set anio
     *
     * @param integer $anio
     *
     * @return RhuCierreAnio
     */
    public function setAnio($anio)
    {
        $this->anio = $anio;

        return $this;
    }

    /**
     * Get anio
     *
     * @return integer
     */
    public function getAnio()
    {
        return $this->anio;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuCierreAnio
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }
}
