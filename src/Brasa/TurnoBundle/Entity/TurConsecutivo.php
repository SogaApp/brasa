<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_consecutivo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurConsecutivoRepository")
 */
class TurConsecutivo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_consecutivo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoConsecutivoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;       
    
    /**
     * @ORM\Column(name="consecutivo", type="integer")
     */    
    private $consecutivo;    
    



    /**
     * Get codigoConsecutivoPk
     *
     * @return integer
     */
    public function getCodigoConsecutivoPk()
    {
        return $this->codigoConsecutivoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurConsecutivo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set consecutivo
     *
     * @param integer $consecutivo
     *
     * @return TurConsecutivo
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return integer
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }
}
