<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_visitante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuVisitanteRepository")
 */
class RhuVisitante
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_visitante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoVisitantePk;
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=25, nullable=true)
     */    
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;          
    
    

    /**
     * Get codigoVisitantePk
     *
     * @return integer
     */
    public function getCodigoVisitantePk()
    {
        return $this->codigoVisitantePk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuVisitante
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuVisitante
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
