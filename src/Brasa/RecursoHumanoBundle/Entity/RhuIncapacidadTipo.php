<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadTipoRepository")
 */
class RhuIncapacidadTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadTipoPk;                              
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      
    
    /**
     * @ORM\Column(name="abreviatura", type="string", length=20, nullable=true)
     */    
    private $abreviatura;    
    
    /**
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo = 0;      
    
    /**     
     * @ORM\Column(name="genera_pago", type="boolean")
     */    
    private $generaPago = false;     
    
    /**     
     * @ORM\Column(name="genera_ibc", type="boolean")
     */    
    private $generaIbc = false;     
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="incapacidadesTiposPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="incapacidadTipoRel")
     */
    protected $incapacidadesIncapacidadTipoRel; 
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->incapacidadesIncapacidadTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoIncapacidadTipoPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadTipoPk()
    {
        return $this->codigoIncapacidadTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuIncapacidadTipo
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
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return RhuIncapacidadTipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return integer
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set generaPago
     *
     * @param boolean $generaPago
     *
     * @return RhuIncapacidadTipo
     */
    public function setGeneraPago($generaPago)
    {
        $this->generaPago = $generaPago;

        return $this;
    }

    /**
     * Get generaPago
     *
     * @return boolean
     */
    public function getGeneraPago()
    {
        return $this->generaPago;
    }

    /**
     * Set generaIbc
     *
     * @param boolean $generaIbc
     *
     * @return RhuIncapacidadTipo
     */
    public function setGeneraIbc($generaIbc)
    {
        $this->generaIbc = $generaIbc;

        return $this;
    }

    /**
     * Get generaIbc
     *
     * @return boolean
     */
    public function getGeneraIbc()
    {
        return $this->generaIbc;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuIncapacidadTipo
     */
    public function setCodigoPagoConceptoFk($codigoPagoConceptoFk)
    {
        $this->codigoPagoConceptoFk = $codigoPagoConceptoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFk()
    {
        return $this->codigoPagoConceptoFk;
    }

    /**
     * Set pagoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel
     *
     * @return RhuIncapacidadTipo
     */
    public function setPagoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoRel = null)
    {
        $this->pagoConceptoRel = $pagoConceptoRel;

        return $this;
    }

    /**
     * Get pagoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto
     */
    public function getPagoConceptoRel()
    {
        return $this->pagoConceptoRel;
    }

    /**
     * Add incapacidadesIncapacidadTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel
     *
     * @return RhuIncapacidadTipo
     */
    public function addIncapacidadesIncapacidadTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel)
    {
        $this->incapacidadesIncapacidadTipoRel[] = $incapacidadesIncapacidadTipoRel;

        return $this;
    }

    /**
     * Remove incapacidadesIncapacidadTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel
     */
    public function removeIncapacidadesIncapacidadTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesIncapacidadTipoRel)
    {
        $this->incapacidadesIncapacidadTipoRel->removeElement($incapacidadesIncapacidadTipoRel);
    }

    /**
     * Get incapacidadesIncapacidadTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesIncapacidadTipoRel()
    {
        return $this->incapacidadesIncapacidadTipoRel;
    }

    /**
     * Set abreviatura
     *
     * @param string $abreviatura
     *
     * @return RhuIncapacidadTipo
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;

        return $this;
    }

    /**
     * Get abreviatura
     *
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }
}
