<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_consecutivo")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarConsecutivoRepository")
 */
class CarConsecutivo
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
     * @return CarConsecutivo
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
     * @return CarConsecutivo
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
