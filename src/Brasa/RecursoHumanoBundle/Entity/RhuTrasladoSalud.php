<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_traslado_salud")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTrasladoSaludRepository")
 */
class RhuTrasladoSalud
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_traslado_salud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTrasladoSaludPk;        
    
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
     * @ORM\Column(name="codigo_entidad_salud_anterior_fk", type="integer")
     */    
    private $codigoEntidadSaludAnteriorFk;    

    /**
     * @ORM\Column(name="codigo_entidad_salud_nueva_fk", type="integer")
     */    
    private $codigoEntidadSaludNuevaFk;            
    
    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */    
    private $detalle; 

    /**
     * @ORM\Column(name="tipo", type="integer", nullable=true)
     */    
    private $tipo;
    
    /**     
     * @ORM\Column(name="estado_afiliado", type="boolean")
     */    
    private $estadoAfiliado = false;
    
    /**
     * @ORM\Column(name="fecha_fosyga", type="date", nullable=true)
     */    
    private $fechaFosyga;
    
    /**
     * @ORM\Column(name="fecha_cambio_afiliacion", type="date", nullable=true)
     */    
    private $fechaCambioAfiliacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="trasladosSaludEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="RhuContrato", inversedBy="trasladosSaludContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="trasladosSaludEntidadSaludAnteriorRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_anterior_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludAnteriorRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="trasladosSaludEntidadSaludNuevaRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_nueva_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludNuevaRel;



    


    /**
     * Get codigoTrasladoSaludPk
     *
     * @return integer
     */
    public function getCodigoTrasladoSaludPk()
    {
        return $this->codigoTrasladoSaludPk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return RhuTrasladoSalud
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
     * @return RhuTrasladoSalud
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
     * @return RhuTrasladoSalud
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
     * Set codigoEntidadSaludAnteriorFk
     *
     * @param integer $codigoEntidadSaludAnteriorFk
     *
     * @return RhuTrasladoSalud
     */
    public function setCodigoEntidadSaludAnteriorFk($codigoEntidadSaludAnteriorFk)
    {
        $this->codigoEntidadSaludAnteriorFk = $codigoEntidadSaludAnteriorFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludAnteriorFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludAnteriorFk()
    {
        return $this->codigoEntidadSaludAnteriorFk;
    }

    /**
     * Set codigoEntidadSaludNuevaFk
     *
     * @param integer $codigoEntidadSaludNuevaFk
     *
     * @return RhuTrasladoSalud
     */
    public function setCodigoEntidadSaludNuevaFk($codigoEntidadSaludNuevaFk)
    {
        $this->codigoEntidadSaludNuevaFk = $codigoEntidadSaludNuevaFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludNuevaFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludNuevaFk()
    {
        return $this->codigoEntidadSaludNuevaFk;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return RhuTrasladoSalud
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
     * Set tipo
     *
     * @param integer $tipo
     *
     * @return RhuTrasladoSalud
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
     * @return RhuTrasladoSalud
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
     * Set fechaFosyga
     *
     * @param \DateTime $fechaFosyga
     *
     * @return RhuTrasladoSalud
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
     * @return RhuTrasladoSalud
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
     * @return RhuTrasladoSalud
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
     * @return RhuTrasladoSalud
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
     * Set entidadSaludAnteriorRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludAnteriorRel
     *
     * @return RhuTrasladoSalud
     */
    public function setEntidadSaludAnteriorRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludAnteriorRel = null)
    {
        $this->entidadSaludAnteriorRel = $entidadSaludAnteriorRel;

        return $this;
    }

    /**
     * Get entidadSaludAnteriorRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud
     */
    public function getEntidadSaludAnteriorRel()
    {
        return $this->entidadSaludAnteriorRel;
    }

    /**
     * Set entidadSaludNuevaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludNuevaRel
     *
     * @return RhuTrasladoSalud
     */
    public function setEntidadSaludNuevaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludNuevaRel = null)
    {
        $this->entidadSaludNuevaRel = $entidadSaludNuevaRel;

        return $this;
    }

    /**
     * Get entidadSaludNuevaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud
     */
    public function getEntidadSaludNuevaRel()
    {
        return $this->entidadSaludNuevaRel;
    }
}
