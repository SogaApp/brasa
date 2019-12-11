<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_examen")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuExamenRepository")
 */
class RhuExamen
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_examen_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoExamenPk;
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk;
    
    /**
     * @ORM\Column(name="codigo_examen_clase_fk", type="integer", nullable=false)
     */    
    private $codigoExamenClaseFk;    
    
    /**
     * @ORM\Column(name="codigo_entidad_examen_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadExamenFk;
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer", nullable=true)
     */    
    private $codigoFacturaFk;

    /**
     * @ORM\Column(name="codigo_cargo_fk", type="integer", nullable=true)
     */    
    private $codigoCargoFk;
    
    /**
     * @ORM\Column(name="fecha", type="date")
     */    
    private $fecha;                   
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = 0;     

    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;    
    
    /**
     * @ORM\Column(name="nombreCorto", type="string")
     */
    private $nombreCorto;        
    
    /**
     * @ORM\Column(name="identificacion", type="string", length=20, nullable=false)
     */
    private $identificacion; 
    
    /**
     * @ORM\Column(name="codigo_sexo_fk", type="string", length=1, nullable=true)
     */    
    private $codigoSexoFk;
    
    /**
     * @ORM\Column(name="cargo_descripcion", type="string", length=60, nullable=true)
     */    
    private $cargoDescripcion;

    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;
    
    /**
     * @ORM\Column(name="codigo_ciudad_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadFk;
    
    /**
     * @ORM\Column(name="fecha_nacimiento", type="date", nullable=true)
     */ 
    
    private $fechaNacimiento;
    
    /**     
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = 0;

    /**     
     * @ORM\Column(name="estado_cobrado", type="boolean")
     */    
    private $estadoCobrado = 0;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;    
    
    /**     
     * @ORM\Column(name="control", type="boolean")
     */    
    private $control = 0;

    /**     
     * @ORM\Column(name="control_pago", type="boolean")
     */    
    private $controlPago = 0;
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuExamenClase", inversedBy="examenesExamenClaseRel")
     * @ORM\JoinColumn(name="codigo_examen_clase_fk", referencedColumnName="codigo_examen_clase_pk")
     */
    protected $examenClaseRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadExamen", inversedBy="examenesEntidadExamenRel")
     * @ORM\JoinColumn(name="codigo_entidad_examen_fk", referencedColumnName="codigo_entidad_examen_pk")
     */
    protected $entidadExamenRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuFactura", inversedBy="examenesFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="rhuExamenesCiudadRel")
     * @ORM\JoinColumn(name="codigo_ciudad_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEmpleado", inversedBy="examenesEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCargo", inversedBy="examenesCargoRel")
     * @ORM\JoinColumn(name="codigo_cargo_fk", referencedColumnName="codigo_cargo_pk")
     */
    protected $cargoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenDetalle", mappedBy="examenRel")
     */
    protected $examenesExamenDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoExamenDetalle", mappedBy="examenRel")
     */
    protected $pagosExamenesDetallesExamenRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuExamenRestriccionMedica", mappedBy="examenRel")
     */
    protected $examenesExamenRestriccionMedicaRel;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examenesExamenDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosExamenesDetallesExamenRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->examenesExamenRestriccionMedicaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoExamenPk
     *
     * @return integer
     */
    public function getCodigoExamenPk()
    {
        return $this->codigoExamenPk;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuExamen
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set codigoExamenClaseFk
     *
     * @param integer $codigoExamenClaseFk
     *
     * @return RhuExamen
     */
    public function setCodigoExamenClaseFk($codigoExamenClaseFk)
    {
        $this->codigoExamenClaseFk = $codigoExamenClaseFk;

        return $this;
    }

    /**
     * Get codigoExamenClaseFk
     *
     * @return integer
     */
    public function getCodigoExamenClaseFk()
    {
        return $this->codigoExamenClaseFk;
    }

    /**
     * Set codigoEntidadExamenFk
     *
     * @param integer $codigoEntidadExamenFk
     *
     * @return RhuExamen
     */
    public function setCodigoEntidadExamenFk($codigoEntidadExamenFk)
    {
        $this->codigoEntidadExamenFk = $codigoEntidadExamenFk;

        return $this;
    }

    /**
     * Get codigoEntidadExamenFk
     *
     * @return integer
     */
    public function getCodigoEntidadExamenFk()
    {
        return $this->codigoEntidadExamenFk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return RhuExamen
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set codigoCargoFk
     *
     * @param integer $codigoCargoFk
     *
     * @return RhuExamen
     */
    public function setCodigoCargoFk($codigoCargoFk)
    {
        $this->codigoCargoFk = $codigoCargoFk;

        return $this;
    }

    /**
     * Get codigoCargoFk
     *
     * @return integer
     */
    public function getCodigoCargoFk()
    {
        return $this->codigoCargoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuExamen
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
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuExamen
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuExamen
     */
    public function setEstadoAutorizado($estadoAutorizado)
    {
        $this->estadoAutorizado = $estadoAutorizado;

        return $this;
    }

    /**
     * Get estadoAutorizado
     *
     * @return boolean
     */
    public function getEstadoAutorizado()
    {
        return $this->estadoAutorizado;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuExamen
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set identificacion
     *
     * @param string $identificacion
     *
     * @return RhuExamen
     */
    public function setIdentificacion($identificacion)
    {
        $this->identificacion = $identificacion;

        return $this;
    }

    /**
     * Get identificacion
     *
     * @return string
     */
    public function getIdentificacion()
    {
        return $this->identificacion;
    }

    /**
     * Set codigoSexoFk
     *
     * @param string $codigoSexoFk
     *
     * @return RhuExamen
     */
    public function setCodigoSexoFk($codigoSexoFk)
    {
        $this->codigoSexoFk = $codigoSexoFk;

        return $this;
    }

    /**
     * Get codigoSexoFk
     *
     * @return string
     */
    public function getCodigoSexoFk()
    {
        return $this->codigoSexoFk;
    }

    /**
     * Set cargoDescripcion
     *
     * @param string $cargoDescripcion
     *
     * @return RhuExamen
     */
    public function setCargoDescripcion($cargoDescripcion)
    {
        $this->cargoDescripcion = $cargoDescripcion;

        return $this;
    }

    /**
     * Get cargoDescripcion
     *
     * @return string
     */
    public function getCargoDescripcion()
    {
        return $this->cargoDescripcion;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuExamen
     */
    public function setVrTotal($vrTotal)
    {
        $this->vrTotal = $vrTotal;

        return $this;
    }

    /**
     * Get vrTotal
     *
     * @return float
     */
    public function getVrTotal()
    {
        return $this->vrTotal;
    }

    /**
     * Set codigoCiudadFk
     *
     * @param integer $codigoCiudadFk
     *
     * @return RhuExamen
     */
    public function setCodigoCiudadFk($codigoCiudadFk)
    {
        $this->codigoCiudadFk = $codigoCiudadFk;

        return $this;
    }

    /**
     * Get codigoCiudadFk
     *
     * @return integer
     */
    public function getCodigoCiudadFk()
    {
        return $this->codigoCiudadFk;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return RhuExamen
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return RhuExamen
     */
    public function setEstadoPagado($estadoPagado)
    {
        $this->estadoPagado = $estadoPagado;

        return $this;
    }

    /**
     * Get estadoPagado
     *
     * @return boolean
     */
    public function getEstadoPagado()
    {
        return $this->estadoPagado;
    }

    /**
     * Set estadoCobrado
     *
     * @param boolean $estadoCobrado
     *
     * @return RhuExamen
     */
    public function setEstadoCobrado($estadoCobrado)
    {
        $this->estadoCobrado = $estadoCobrado;

        return $this;
    }

    /**
     * Get estadoCobrado
     *
     * @return boolean
     */
    public function getEstadoCobrado()
    {
        return $this->estadoCobrado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuExamen
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return RhuExamen
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
     * Set control
     *
     * @param boolean $control
     *
     * @return RhuExamen
     */
    public function setControl($control)
    {
        $this->control = $control;

        return $this;
    }

    /**
     * Get control
     *
     * @return boolean
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Set controlPago
     *
     * @param boolean $controlPago
     *
     * @return RhuExamen
     */
    public function setControlPago($controlPago)
    {
        $this->controlPago = $controlPago;

        return $this;
    }

    /**
     * Get controlPago
     *
     * @return boolean
     */
    public function getControlPago()
    {
        return $this->controlPago;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuExamen
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
     * Set examenClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenClase $examenClaseRel
     *
     * @return RhuExamen
     */
    public function setExamenClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenClase $examenClaseRel = null)
    {
        $this->examenClaseRel = $examenClaseRel;

        return $this;
    }

    /**
     * Get examenClaseRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuExamenClase
     */
    public function getExamenClaseRel()
    {
        return $this->examenClaseRel;
    }

    /**
     * Set entidadExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel
     *
     * @return RhuExamen
     */
    public function setEntidadExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen $entidadExamenRel = null)
    {
        $this->entidadExamenRel = $entidadExamenRel;

        return $this;
    }

    /**
     * Get entidadExamenRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadExamen
     */
    public function getEntidadExamenRel()
    {
        return $this->entidadExamenRel;
    }

    /**
     * Set facturaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel
     *
     * @return RhuExamen
     */
    public function setFacturaRel(\Brasa\RecursoHumanoBundle\Entity\RhuFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set ciudadRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel
     *
     * @return RhuExamen
     */
    public function setCiudadRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadRel = null)
    {
        $this->ciudadRel = $ciudadRel;

        return $this;
    }

    /**
     * Get ciudadRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadRel()
    {
        return $this->ciudadRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadoRel
     *
     * @return RhuExamen
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
     * Set cargoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel
     *
     * @return RhuExamen
     */
    public function setCargoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCargo $cargoRel = null)
    {
        $this->cargoRel = $cargoRel;

        return $this;
    }

    /**
     * Get cargoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCargo
     */
    public function getCargoRel()
    {
        return $this->cargoRel;
    }

    /**
     * Add examenesExamenDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel
     *
     * @return RhuExamen
     */
    public function addExamenesExamenDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel)
    {
        $this->examenesExamenDetalleRel[] = $examenesExamenDetalleRel;

        return $this;
    }

    /**
     * Remove examenesExamenDetalleRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel
     */
    public function removeExamenesExamenDetalleRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenDetalle $examenesExamenDetalleRel)
    {
        $this->examenesExamenDetalleRel->removeElement($examenesExamenDetalleRel);
    }

    /**
     * Get examenesExamenDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesExamenDetalleRel()
    {
        return $this->examenesExamenDetalleRel;
    }

    /**
     * Add pagosExamenesDetallesExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel
     *
     * @return RhuExamen
     */
    public function addPagosExamenesDetallesExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel)
    {
        $this->pagosExamenesDetallesExamenRel[] = $pagosExamenesDetallesExamenRel;

        return $this;
    }

    /**
     * Remove pagosExamenesDetallesExamenRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel
     */
    public function removePagosExamenesDetallesExamenRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoExamenDetalle $pagosExamenesDetallesExamenRel)
    {
        $this->pagosExamenesDetallesExamenRel->removeElement($pagosExamenesDetallesExamenRel);
    }

    /**
     * Get pagosExamenesDetallesExamenRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosExamenesDetallesExamenRel()
    {
        return $this->pagosExamenesDetallesExamenRel;
    }

    /**
     * Add examenesExamenRestriccionMedicaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesExamenRestriccionMedicaRel
     *
     * @return RhuExamen
     */
    public function addExamenesExamenRestriccionMedicaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesExamenRestriccionMedicaRel)
    {
        $this->examenesExamenRestriccionMedicaRel[] = $examenesExamenRestriccionMedicaRel;

        return $this;
    }

    /**
     * Remove examenesExamenRestriccionMedicaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesExamenRestriccionMedicaRel
     */
    public function removeExamenesExamenRestriccionMedicaRel(\Brasa\RecursoHumanoBundle\Entity\RhuExamenRestriccionMedica $examenesExamenRestriccionMedicaRel)
    {
        $this->examenesExamenRestriccionMedicaRel->removeElement($examenesExamenRestriccionMedicaRel);
    }

    /**
     * Get examenesExamenRestriccionMedicaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExamenesExamenRestriccionMedicaRel()
    {
        return $this->examenesExamenRestriccionMedicaRel;
    }
}
