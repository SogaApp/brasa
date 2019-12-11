<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ctb_activo_fijo_tipo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbActivoFijoTipoRepository")
 */
class CtbActivoFijoTipo
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_activo_fijo_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoActivoFijoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;



    /**
     * Get codigoActivoFijoTipoPk
     *
     * @return integer
     */
    public function getCodigoActivoFijoTipoPk()
    {
        return $this->codigoActivoFijoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbActivoFijoTipo
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
}
