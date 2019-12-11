<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="inv_documento_clase")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDocumentoClaseRepository")
 */
class InvDocumentoClase
{   
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_documento_clase_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */ 
    private $codigoDocumentoClasePk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80)
     */        
    private $nombre;    
        

    /**
     * Get codigoDocumentoClasePk
     *
     * @return integer
     */
    public function getCodigoDocumentoClasePk()
    {
        return $this->codigoDocumentoClasePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvDocumentoClase
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
