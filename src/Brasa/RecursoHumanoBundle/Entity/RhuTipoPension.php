<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_tipo_pension")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTipoPensionRepository")
 */
class RhuTipoPension
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_pension_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoPensionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="porcentaje_empleado", type="float")
     */    
    private $porcentajeEmpleado = 0;        
    
    /**
     * @ORM\Column(name="porcentaje_empleador", type="float")
     */    
    private $porcentajeEmpleador = 0;    
    
    /**
     * @ORM\Column(name="codigo_pago_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFk;

    /**
     * @ORM\Column(name="codigo_pago_concepto_fondo_fk", type="integer", nullable=true)
     */    
    private $codigoPagoConceptoFondoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="tiposPensionesPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="tiposPensionesPagoConceptoFondoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fondo_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoFondoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="tipoPensionRel")
     */
    protected $contratosTipoPensionRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="tipoPensionRel")
     */
    protected $empleadosTipoPensionRel;             
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosTipoPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosTipoPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoPensionPk
     *
     * @return integer
     */
    public function getCodigoTipoPensionPk()
    {
        return $this->codigoTipoPensionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTipoPension
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
     * Set porcentajeEmpleado
     *
     * @param float $porcentajeEmpleado
     *
     * @return RhuTipoPension
     */
    public function setPorcentajeEmpleado($porcentajeEmpleado)
    {
        $this->porcentajeEmpleado = $porcentajeEmpleado;

        return $this;
    }

    /**
     * Get porcentajeEmpleado
     *
     * @return float
     */
    public function getPorcentajeEmpleado()
    {
        return $this->porcentajeEmpleado;
    }

    /**
     * Set porcentajeEmpleador
     *
     * @param float $porcentajeEmpleador
     *
     * @return RhuTipoPension
     */
    public function setPorcentajeEmpleador($porcentajeEmpleador)
    {
        $this->porcentajeEmpleador = $porcentajeEmpleador;

        return $this;
    }

    /**
     * Get porcentajeEmpleador
     *
     * @return float
     */
    public function getPorcentajeEmpleador()
    {
        return $this->porcentajeEmpleador;
    }

    /**
     * Set codigoPagoConceptoFk
     *
     * @param integer $codigoPagoConceptoFk
     *
     * @return RhuTipoPension
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
     * @return RhuTipoPension
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
     * Add contratosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel
     *
     * @return RhuTipoPension
     */
    public function addContratosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel)
    {
        $this->contratosTipoPensionRel[] = $contratosTipoPensionRel;

        return $this;
    }

    /**
     * Remove contratosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel
     */
    public function removeContratosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoPensionRel)
    {
        $this->contratosTipoPensionRel->removeElement($contratosTipoPensionRel);
    }

    /**
     * Get contratosTipoPensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosTipoPensionRel()
    {
        return $this->contratosTipoPensionRel;
    }

    /**
     * Add empleadosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel
     *
     * @return RhuTipoPension
     */
    public function addEmpleadosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel)
    {
        $this->empleadosTipoPensionRel[] = $empleadosTipoPensionRel;

        return $this;
    }

    /**
     * Remove empleadosTipoPensionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel
     */
    public function removeEmpleadosTipoPensionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoPensionRel)
    {
        $this->empleadosTipoPensionRel->removeElement($empleadosTipoPensionRel);
    }

    /**
     * Get empleadosTipoPensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosTipoPensionRel()
    {
        return $this->empleadosTipoPensionRel;
    }

    /**
     * Set codigoPagoConceptoFondoFk
     *
     * @param integer $codigoPagoConceptoFondoFk
     *
     * @return RhuTipoPension
     */
    public function setCodigoPagoConceptoFondoFk($codigoPagoConceptoFondoFk)
    {
        $this->codigoPagoConceptoFondoFk = $codigoPagoConceptoFondoFk;

        return $this;
    }

    /**
     * Get codigoPagoConceptoFondoFk
     *
     * @return integer
     */
    public function getCodigoPagoConceptoFondoFk()
    {
        return $this->codigoPagoConceptoFondoFk;
    }

    /**
     * Set pagoConceptoFondoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoFondoRel
     *
     * @return RhuTipoPension
     */
    public function setPagoConceptoFondoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto $pagoConceptoFondoRel = null)
    {
        $this->pagoConceptoFondoRel = $pagoConceptoFondoRel;

        return $this;
    }

    /**
     * Get pagoConceptoFondoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuPagoConcepto
     */
    public function getPagoConceptoFondoRel()
    {
        return $this->pagoConceptoFondoRel;
    }
}
