<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_secuencia")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurSecuenciaRepository")
 */
class TurSecuencia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_secuencia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSecuenciaPk;             
    
    /**
     * @ORM\Column(name="filas", type="integer", nullable=true)
     */    
    private $filas = 0;    
      

    /**
     * Get codigoSecuenciaPk
     *
     * @return integer
     */
    public function getCodigoSecuenciaPk()
    {
        return $this->codigoSecuenciaPk;
    }

    /**
     * Set filas
     *
     * @param integer $filas
     *
     * @return TurSecuencia
     */
    public function setFilas($filas)
    {
        $this->filas = $filas;

        return $this;
    }

    /**
     * Get filas
     *
     * @return integer
     */
    public function getFilas()
    {
        return $this->filas;
    }
}
