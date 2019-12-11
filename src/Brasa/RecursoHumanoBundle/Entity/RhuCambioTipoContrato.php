<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_cambio_tipo_contrato")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCambioTipoContratoRepository")
 */
class RhuCambioTipoContrato
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cambio_tipo_contrato_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCambioTipoContratoPk;        
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;         
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="codigo_contrato_tipo_anterior_fk", type="integer")
     */    
    private $codigoContratoTipoAnteriorFk;
    
    /**
     * @ORM\Column(name="codigo_contrato_tipo_nuevo_fk", type="integer")
     */    
    private $codigoContratoTipoNuevoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;                 
    
    /**
     * @ORM\Column(name="vr_salario_anterior", type="float")
     */
    private $VrSalarioAnterior = 0;    

    /**
     * @ORM\Column(name="vr_salario_nuevo", type="float")
     */
    private $VrSalarioNuevo = 0;            
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="cambiosTiposContratosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="cambiosTiposContratosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoTipo", inversedBy="cambiosTiposContratosAnterioresContratoTipoRel")
     * @ORM\JoinColumn(name="codigo_contrato_tipo_anterior_fk", referencedColumnName="codigo_contrato_tipo_pk")
     */
    protected $contratoTipoAnteriorRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoTipo", inversedBy="cambiosTiposContratosNuevosContratoTipoRel")
     * @ORM\JoinColumn(name="codigo_contrato_tipo_nuevo_fk", referencedColumnName="codigo_contrato_tipo_pk")
     */
    protected $contratoTipoNuevoRel;




    /**
     * Get codigoCambioTipoContratoPk
     *
     * @return integer
     */
    public function getCodigoCambioTipoContratoPk()
    {
        return $this->codigoCambioTipoContratoPk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuCambioTipoContrato
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuCambioTipoContrato
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set codigoContratoTipoAnteriorFk
     *
     * @param integer $codigoContratoTipoAnteriorFk
     *
     * @return RhuCambioTipoContrato
     */
    public function setCodigoContratoTipoAnteriorFk($codigoContratoTipoAnteriorFk)
    {
        $this->codigoContratoTipoAnteriorFk = $codigoContratoTipoAnteriorFk;

        return $this;
    }

    /**
     * Get codigoContratoTipoAnteriorFk
     *
     * @return integer
     */
    public function getCodigoContratoTipoAnteriorFk()
    {
        return $this->codigoContratoTipoAnteriorFk;
    }

    /**
     * Set codigoContratoTipoNuevoFk
     *
     * @param integer $codigoContratoTipoNuevoFk
     *
     * @return RhuCambioTipoContrato
     */
    public function setCodigoContratoTipoNuevoFk($codigoContratoTipoNuevoFk)
    {
        $this->codigoContratoTipoNuevoFk = $codigoContratoTipoNuevoFk;

        return $this;
    }

    /**
     * Get codigoContratoTipoNuevoFk
     *
     * @return integer
     */
    public function getCodigoContratoTipoNuevoFk()
    {
        return $this->codigoContratoTipoNuevoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuCambioTipoContrato
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set vrSalarioAnterior
     *
     * @param float $vrSalarioAnterior
     *
     * @return RhuCambioTipoContrato
     */
    public function setVrSalarioAnterior($vrSalarioAnterior)
    {
        $this->VrSalarioAnterior = $vrSalarioAnterior;

        return $this;
    }

    /**
     * Get vrSalarioAnterior
     *
     * @return float
     */
    public function getVrSalarioAnterior()
    {
        return $this->VrSalarioAnterior;
    }

    /**
     * Set vrSalarioNuevo
     *
     * @param float $vrSalarioNuevo
     *
     * @return RhuCambioTipoContrato
     */
    public function setVrSalarioNuevo($vrSalarioNuevo)
    {
        $this->VrSalarioNuevo = $vrSalarioNuevo;

        return $this;
    }

    /**
     * Get vrSalarioNuevo
     *
     * @return float
     */
    public function getVrSalarioNuevo()
    {
        return $this->VrSalarioNuevo;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuCambioTipoContrato
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuCambioTipoContrato
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuCambioTipoContrato
     */
    public function setEmpleadoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel
     *
     * @return RhuCambioTipoContrato
     */
    public function setContratoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }

    /**
     * Set contratoTipoAnteriorRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoAnteriorRel
     *
     * @return RhuCambioTipoContrato
     */
    public function setContratoTipoAnteriorRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoAnteriorRel = null)
    {
        $this->contratoTipoAnteriorRel = $contratoTipoAnteriorRel;

        return $this;
    }

    /**
     * Get contratoTipoAnteriorRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo
     */
    public function getContratoTipoAnteriorRel()
    {
        return $this->contratoTipoAnteriorRel;
    }

    /**
     * Set contratoTipoNuevoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoNuevoRel
     *
     * @return RhuCambioTipoContrato
     */
    public function setContratoTipoNuevoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo $contratoTipoNuevoRel = null)
    {
        $this->contratoTipoNuevoRel = $contratoTipoNuevoRel;

        return $this;
    }

    /**
     * Get contratoTipoNuevoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoTipo
     */
    public function getContratoTipoNuevoRel()
    {
        return $this->contratoTipoNuevoRel;
    }
}
