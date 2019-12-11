<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenTipoRepository")
 */
class RhuExamenTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenTipoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;            
    
    /**     
     * Aplica para cuando se crea el examen de ingreso automaticamente
     * cree un examen con estos tipos
     * 
     * @ORM\Column(name="ingreso", type="boolean")
     */    
    private $ingreso = 0;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenTipoRel")
     */
    protected $examenesDetallesExamenTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenCargo", mappedBy="examenTipoRel")
     */
    protected $examenesCargosExamenTipoRel;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesDetallesExamenTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesCargosExamenTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenTipoPk
     *
     * @return integer
     */
    public function getCodigoExamenTipoPk()
    {
        return $this->codigoExamenTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuExamenTipo
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
     * Set ingreso
     *
     * @param boolean $ingreso
     *
     * @return RhuExamenTipo
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return boolean
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Add examenesDetallesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel
     *
     * @return RhuExamenTipo
     */
    public function addExamenesDetallesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel)
    {
        $this->examenesDetallesExamenTipoRel[] = $examenesDetallesExamenTipoRel;

        return $this;
    }

    /**
     * Remove examenesDetallesExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel
     */
    public function removeExamenesDetallesExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesDetallesExamenTipoRel)
    {
        $this->examenesDetallesExamenTipoRel->removeElement($examenesDetallesExamenTipoRel);
    }

    /**
     * Get examenesDetallesExamenTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesDetallesExamenTipoRel()
    {
        return $this->examenesDetallesExamenTipoRel;
    }

    /**
     * Add examenesCargosExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosExamenTipoRel
     *
     * @return RhuExamenTipo
     */
    public function addExamenesCargosExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosExamenTipoRel)
    {
        $this->examenesCargosExamenTipoRel[] = $examenesCargosExamenTipoRel;

        return $this;
    }

    /**
     * Remove examenesCargosExamenTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosExamenTipoRel
     */
    public function removeExamenesCargosExamenTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenCargo $examenesCargosExamenTipoRel)
    {
        $this->examenesCargosExamenTipoRel->removeElement($examenesCargosExamenTipoRel);
    }

    /**
     * Get examenesCargosExamenTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesCargosExamenTipoRel()
    {
        return $this->examenesCargosExamenTipoRel;
    }
}
