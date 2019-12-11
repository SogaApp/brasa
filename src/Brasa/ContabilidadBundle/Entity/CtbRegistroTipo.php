<?php

namespace Brasa\ContabilidadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="ctb_registro_tipo")
 * @ORM\Entity(repositoryClass="Brasa\ContabilidadBundle\Repository\CtbRegistroTipoRepository")
 */
class CtbRegistroTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_tipo_pk", type="smallint")
     */        
    private $codigoRegistroTipoPk;                      
    
    /**
     * @ORM\Column(name="nombre", type="string", length=20)
     */     
    private $nombre;      
    



    /**
     * Set codigoRegistroTipoPk
     *
     * @param integer $codigoRegistroTipoPk
     *
     * @return CtbRegistroTipo
     */
    public function setCodigoRegistroTipoPk($codigoRegistroTipoPk)
    {
        $this->codigoRegistroTipoPk = $codigoRegistroTipoPk;

        return $this;
    }

    /**
     * Get codigoRegistroTipoPk
     *
     * @return integer
     */
    public function getCodigoRegistroTipoPk()
    {
        return $this->codigoRegistroTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CtbRegistroTipo
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
