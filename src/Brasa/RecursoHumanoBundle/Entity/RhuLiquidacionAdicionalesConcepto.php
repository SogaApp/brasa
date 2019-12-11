<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_liquidacion_adicionales_concepto")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLiquidacionAdicionalesConceptoRepository")
 */
class RhuLiquidacionAdicionalesConcepto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_liquidacion_adicional_concepto_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLiquidacionAdicionalConceptoPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=100, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=100, nullable=true)
     */    
    private $tipo;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLiquidacionAdicionales", mappedBy="liquidacionAdicionalConceptoRel")
     */
    protected $liquidacionesAdicionalesLiquidacionAdicionalConceptoRel;     
        
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->liquidacionesAdicionalesLiquidacionAdicionalConceptoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoLiquidacionAdicionalConceptoPk
     *
     * @return integer
     */
    public function getCodigoLiquidacionAdicionalConceptoPk()
    {
        return $this->codigoLiquidacionAdicionalConceptoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuLiquidacionAdicionalesConcepto
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
     * Add liquidacionesAdicionalesLiquidacionAdicionalConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionAdicionalConceptoRel
     *
     * @return RhuLiquidacionAdicionalesConcepto
     */
    public function addLiquidacionesAdicionalesLiquidacionAdicionalConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionAdicionalConceptoRel)
    {
        $this->liquidacionesAdicionalesLiquidacionAdicionalConceptoRel[] = $liquidacionesAdicionalesLiquidacionAdicionalConceptoRel;

        return $this;
    }

    /**
     * Remove liquidacionesAdicionalesLiquidacionAdicionalConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionAdicionalConceptoRel
     */
    public function removeLiquidacionesAdicionalesLiquidacionAdicionalConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLiquidacionAdicionales $liquidacionesAdicionalesLiquidacionAdicionalConceptoRel)
    {
        $this->liquidacionesAdicionalesLiquidacionAdicionalConceptoRel->removeElement($liquidacionesAdicionalesLiquidacionAdicionalConceptoRel);
    }

    /**
     * Get liquidacionesAdicionalesLiquidacionAdicionalConceptoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLiquidacionesAdicionalesLiquidacionAdicionalConceptoRel()
    {
        return $this->liquidacionesAdicionalesLiquidacionAdicionalConceptoRel;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return RhuLiquidacionAdicionalesConcepto
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }
}
