<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_traslado_pension")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTrasladoPensionRepository")
 */
class RhuTrasladoPension
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_traslado_pension_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTrasladoPensionPk;        
    
    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */    
    private $codigoContratoFk;         
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */    
    private $codigoEmpleadoFk;    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;                 
    
    /**
     * @ORM\Column(name="codigo_entidad_pension_anterior_fk", type="integer")
     */    
    private $codigoEntidadPensionAnteriorFk;    

    /**
     * @ORM\Column(name="codigo_entidad_pension_nueva_fk", type="integer")
     */    
    private $codigoEntidadPensionNuevaFk;
    
    /**
     * @ORM\Column(name="tipo", type="integer", nullable=true)
     */    
    private $tipo;
    
    /**     
     * @ORM\Column(name="estado_afiliado", type="boolean")
     */    
    private $estadoAfiliado = false;
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle;
    
    /**
     * @ORM\Column(name="fecha_fosyga", type="date", nullable=true)
     */    
    private $fechaFosyga;
    
    /**
     * @ORM\Column(name="fecha_cambio_afiliacion", type="date", nullable=true)
     */    
    private $fechaCambioAfiliacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="trasladosPensionesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="trasladosPensionesContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadPension", inversedBy="trasladosPensionesEntidadPensionAnteriorRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_anterior_fk", referencedColumnName="codigo_entidad_pension_pk")
     */
    protected $entidadPensionAnteriorRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadPension", inversedBy="trasladosPensionesEntidadPensionNuevaRel")
     * @ORM\JoinColumn(name="codigo_entidad_pension_nueva_fk", referencedColumnName="codigo_entidad_pension_pk")
     */
    protected $entidadPensionNuevaRel;





    /**
     * Get codigoTrasladoPensionPk
     *
     * @return integer
     */
    public function getCodigoTrasladoPensionPk()
    {
        return $this->codigoTrasladoPensionPk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuTrasladoPension
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
     * @return RhuTrasladoPension
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuTrasladoPension
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
     * Set codigoEntidadPensionAnteriorFk
     *
     * @param integer $codigoEntidadPensionAnteriorFk
     *
     * @return RhuTrasladoPension
     */
    public function setCodigoEntidadPensionAnteriorFk($codigoEntidadPensionAnteriorFk)
    {
        $this->codigoEntidadPensionAnteriorFk = $codigoEntidadPensionAnteriorFk;

        return $this;
    }

    /**
     * Get codigoEntidadPensionAnteriorFk
     *
     * @return integer
     */
    public function getCodigoEntidadPensionAnteriorFk()
    {
        return $this->codigoEntidadPensionAnteriorFk;
    }

    /**
     * Set codigoEntidadPensionNuevaFk
     *
     * @param integer $codigoEntidadPensionNuevaFk
     *
     * @return RhuTrasladoPension
     */
    public function setCodigoEntidadPensionNuevaFk($codigoEntidadPensionNuevaFk)
    {
        $this->codigoEntidadPensionNuevaFk = $codigoEntidadPensionNuevaFk;

        return $this;
    }

    /**
     * Get codigoEntidadPensionNuevaFk
     *
     * @return integer
     */
    public function getCodigoEntidadPensionNuevaFk()
    {
        return $this->codigoEntidadPensionNuevaFk;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return RhuTrasladoPension
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
     * Set estadoAfiliado
     *
     * @param boolean $estadoAfiliado
     *
     * @return RhuTrasladoPension
     */
    public function setEstadoAfiliado($estadoAfiliado)
    {
        $this->estadoAfiliado = $estadoAfiliado;

        return $this;
    }

    /**
     * Get estadoAfiliado
     *
     * @return boolean
     */
    public function getEstadoAfiliado()
    {
        return $this->estadoAfiliado;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuTrasladoPension
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
     * Set fechaFosyga
     *
     * @param \DateTime $fechaFosyga
     *
     * @return RhuTrasladoPension
     */
    public function setFechaFosyga($fechaFosyga)
    {
        $this->fechaFosyga = $fechaFosyga;

        return $this;
    }

    /**
     * Get fechaFosyga
     *
     * @return \DateTime
     */
    public function getFechaFosyga()
    {
        return $this->fechaFosyga;
    }

    /**
     * Set fechaCambioAfiliacion
     *
     * @param \DateTime $fechaCambioAfiliacion
     *
     * @return RhuTrasladoPension
     */
    public function setFechaCambioAfiliacion($fechaCambioAfiliacion)
    {
        $this->fechaCambioAfiliacion = $fechaCambioAfiliacion;

        return $this;
    }

    /**
     * Get fechaCambioAfiliacion
     *
     * @return \DateTime
     */
    public function getFechaCambioAfiliacion()
    {
        return $this->fechaCambioAfiliacion;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuTrasladoPension
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
     * @return RhuTrasladoPension
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
     * Set entidadPensionAnteriorRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionAnteriorRel
     *
     * @return RhuTrasladoPension
     */
    public function setEntidadPensionAnteriorRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionAnteriorRel = null)
    {
        $this->entidadPensionAnteriorRel = $entidadPensionAnteriorRel;

        return $this;
    }

    /**
     * Get entidadPensionAnteriorRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension
     */
    public function getEntidadPensionAnteriorRel()
    {
        return $this->entidadPensionAnteriorRel;
    }

    /**
     * Set entidadPensionNuevaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionNuevaRel
     *
     * @return RhuTrasladoPension
     */
    public function setEntidadPensionNuevaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension $entidadPensionNuevaRel = null)
    {
        $this->entidadPensionNuevaRel = $entidadPensionNuevaRel;

        return $this;
    }

    /**
     * Get entidadPensionNuevaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadPension
     */
    public function getEntidadPensionNuevaRel()
    {
        return $this->entidadPensionNuevaRel;
    }
}
