<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_licencia_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuLicenciaTipoRepository")
 */
class RhuLicenciaTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_licencia_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLicenciaTipoPk;                    
        
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */    
    private $nombre;         
    
    /**     
     * @ORM\Column(name="afecta_salud", type="boolean")
     */    
    private $afectaSalud = 0;      

    /**     
     * @ORM\Column(name="ausentismo", type="boolean")
     */    
    private $ausentismo = 0;    
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;     
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="licenciasTiposPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuLicencia", mappedBy="licenciaTipoRel")
     */
    protected $licenciasLicenciaTipoRel;     

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->licenciasLicenciaTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoLicenciaTipoPk
     *
     * @return integer
     */
    public function getCodigoLicenciaTipoPk()
    {
        return $this->codigoLicenciaTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuLicenciaTipo
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
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuLicenciaTipo
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
     * @return RhuLicenciaTipo
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
     * Add licenciasLicenciaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasLicenciaTipoRel
     *
     * @return RhuLicenciaTipo
     */
    public function addLicenciasLicenciaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasLicenciaTipoRel)
    {
        $this->licenciasLicenciaTipoRel[] = $licenciasLicenciaTipoRel;

        return $this;
    }

    /**
     * Remove licenciasLicenciaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasLicenciaTipoRel
     */
    public function removeLicenciasLicenciaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuLicencia $licenciasLicenciaTipoRel)
    {
        $this->licenciasLicenciaTipoRel->removeElement($licenciasLicenciaTipoRel);
    }

    /**
     * Get licenciasLicenciaTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLicenciasLicenciaTipoRel()
    {
        return $this->licenciasLicenciaTipoRel;
    }

    /**
     * Set afectaSalud
     *
     * @param boolean $afectaSalud
     *
     * @return RhuLicenciaTipo
     */
    public function setAfectaSalud($afectaSalud)
    {
        $this->afectaSalud = $afectaSalud;

        return $this;
    }

    /**
     * Get afectaSalud
     *
     * @return boolean
     */
    public function getAfectaSalud()
    {
        return $this->afectaSalud;
    }

    /**
     * Set ausentismo
     *
     * @param boolean $ausentismo
     *
     * @return RhuLicenciaTipo
     */
    public function setAusentismo($ausentismo)
    {
        $this->ausentismo = $ausentismo;

        return $this;
    }

    /**
     * Get ausentismo
     *
     * @return boolean
     */
    public function getAusentismo()
    {
        return $this->ausentismo;
    }
}
