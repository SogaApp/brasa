<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_tipo_acceso")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTipoAccesoRepository")
 */
class RhuTipoAcceso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_acceso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoAccesoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=20, nullable=true)
     */    
    private $nombre;        
    
    

    /**
     * Get codigoTipoAccesoPk
     *
     * @return integer
     */
    public function getCodigoTipoAccesoPk()
    {
        return $this->codigoTipoAccesoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTipoAcceso
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
