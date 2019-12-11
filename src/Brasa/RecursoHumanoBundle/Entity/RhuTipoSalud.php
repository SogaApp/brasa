<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_tipo_salud")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTipoSaludRepository")
 */
class RhuTipoSalud
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_salud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoSaludPk;
    
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
     * @ORM\ManyToOne(targetEntity="RhuPagoConcepto", inversedBy="tiposSaludPagoConceptoRel")
     * @ORM\JoinColumn(name="codigo_pago_concepto_fk", referencedColumnName="codigo_pago_concepto_pk")
     */
    protected $pagoConceptoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="tipoSaludRel")
     */
    protected $contratosTipoSaludRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="tipoSaludRel")
     */
    protected $empleadosTipoSaludRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosTipoSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosTipoSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoSaludPk
     *
     * @return integer
     */
    public function getCodigoTipoSaludPk()
    {
        return $this->codigoTipoSaludPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTipoSalud
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
     * @return RhuTipoSalud
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
     * @return RhuTipoSalud
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
     * @return RhuTipoSalud
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
     * @return RhuTipoSalud
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
     * Add contratosTipoSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoSaludRel
     *
     * @return RhuTipoSalud
     */
    public function addContratosTipoSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoSaludRel)
    {
        $this->contratosTipoSaludRel[] = $contratosTipoSaludRel;

        return $this;
    }

    /**
     * Remove contratosTipoSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoSaludRel
     */
    public function removeContratosTipoSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosTipoSaludRel)
    {
        $this->contratosTipoSaludRel->removeElement($contratosTipoSaludRel);
    }

    /**
     * Get contratosTipoSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosTipoSaludRel()
    {
        return $this->contratosTipoSaludRel;
    }

    /**
     * Add empleadosTipoSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoSaludRel
     *
     * @return RhuTipoSalud
     */
    public function addEmpleadosTipoSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoSaludRel)
    {
        $this->empleadosTipoSaludRel[] = $empleadosTipoSaludRel;

        return $this;
    }

    /**
     * Remove empleadosTipoSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoSaludRel
     */
    public function removeEmpleadosTipoSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosTipoSaludRel)
    {
        $this->empleadosTipoSaludRel->removeElement($empleadosTipoSaludRel);
    }

    /**
     * Get empleadosTipoSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosTipoSaludRel()
    {
        return $this->empleadosTipoSaludRel;
    }
}
