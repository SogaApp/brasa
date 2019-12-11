<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_salario_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSalarioTipoRepository")
 */
class RhuSalarioTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_salario_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSalarioTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre; 

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="salarioTipoRel")
     */
    protected $contratosSalarioTipoRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosSalarioTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSalarioTipoPk
     *
     * @return integer
     */
    public function getCodigoSalarioTipoPk()
    {
        return $this->codigoSalarioTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSalarioTipo
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
     * Add contratosSalarioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioTipoRel
     *
     * @return RhuSalarioTipo
     */
    public function addContratosSalarioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioTipoRel)
    {
        $this->contratosSalarioTipoRel[] = $contratosSalarioTipoRel;

        return $this;
    }

    /**
     * Remove contratosSalarioTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioTipoRel
     */
    public function removeContratosSalarioTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSalarioTipoRel)
    {
        $this->contratosSalarioTipoRel->removeElement($contratosSalarioTipoRel);
    }

    /**
     * Get contratosSalarioTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSalarioTipoRel()
    {
        return $this->contratosSalarioTipoRel;
    }
}
