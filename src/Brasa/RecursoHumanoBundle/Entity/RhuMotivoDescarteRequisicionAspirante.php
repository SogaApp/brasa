<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_motivo_descarte_requisicion_aspirante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuMotivoDescarteRequisicionAspiranteRepository")
 */
class RhuMotivoDescarteRequisicionAspirante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_motivo_descarte_requisicion_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMotivoDescarteRequisicionAspirantePk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSeleccionRequisicionAspirante", mappedBy="motivoDescarteRequisicionAspiranteRel")
     */
    protected $motivosDescartesseleccionRequisicionAspiranteRel;

    
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->motivosDescartesseleccionRequisicionAspiranteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoMotivoDescarteRequisicionAspirantePk
     *
     * @return integer
     */
    public function getCodigoMotivoDescarteRequisicionAspirantePk()
    {
        return $this->codigoMotivoDescarteRequisicionAspirantePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuMotivoDescarteRequisicionAspirante
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
     * Add motivosDescartesseleccionRequisicionAspiranteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $motivosDescartesseleccionRequisicionAspiranteRel
     *
     * @return RhuMotivoDescarteRequisicionAspirante
     */
    public function addMotivosDescartesseleccionRequisicionAspiranteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $motivosDescartesseleccionRequisicionAspiranteRel)
    {
        $this->motivosDescartesseleccionRequisicionAspiranteRel[] = $motivosDescartesseleccionRequisicionAspiranteRel;

        return $this;
    }

    /**
     * Remove motivosDescartesseleccionRequisicionAspiranteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $motivosDescartesseleccionRequisicionAspiranteRel
     */
    public function removeMotivosDescartesseleccionRequisicionAspiranteRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisicionAspirante $motivosDescartesseleccionRequisicionAspiranteRel)
    {
        $this->motivosDescartesseleccionRequisicionAspiranteRel->removeElement($motivosDescartesseleccionRequisicionAspiranteRel);
    }

    /**
     * Get motivosDescartesseleccionRequisicionAspiranteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMotivosDescartesseleccionRequisicionAspiranteRel()
    {
        return $this->motivosDescartesseleccionRequisicionAspiranteRel;
    }
}
