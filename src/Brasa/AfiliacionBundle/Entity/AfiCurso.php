<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_curso")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCursoRepository")
 */
class AfiCurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoPk;         

    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0; 
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;        
    
    /**
     * @ORM\Column(name="fecha_vence", type="date", nullable=true)
     */    
    private $fechaVence;        
    
    /**
     * @ORM\Column(name="fecha_programacion", type="date", nullable=true)
     */    
    private $fechaProgramacion;    
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;      
    
    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer", nullable=true)
     */    
    private $codigoEmpleadoFk;    
    
    /**
     * @ORM\Column(name="codigo_entidad_entrenamiento_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadEntrenamientoFk;            
    
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=20, nullable=true)
     */         
    private $numeroIdentificacion;    
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=80, nullable=true)
     */    
    private $nombreCorto;     
    
    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;    

    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;             

    /**     
     * @ORM\Column(name="asistencia", type="boolean")
     */    
    private $asistencia = 0;    
    
    /**     
     * @ORM\Column(name="certificado", type="boolean")
     */    
    private $certificado = 0;    
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;            

    /**     
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = false;     

    /**     
     * @ORM\Column(name="estado_facturado", type="boolean")
     */    
    private $estadoFacturado = false;    

    /**     
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = false;      
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCliente", inversedBy="cursosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    

    /**
     * @ORM\ManyToOne(targetEntity="AfiEmpleado", inversedBy="cursosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="AfiEntidadEntrenamiento", inversedBy="cursosEntidadEntrenamientoRel")
     * @ORM\JoinColumn(name="codigo_entidad_entrenamiento_fk", referencedColumnName="codigo_entidad_entrenamiento_pk")
     */
    protected $entidadEntrenamientoRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="AfiCursoDetalle", mappedBy="cursoRel", cascade={"persist", "remove"})
     */
    protected $cursosDetallesCursoRel;     

    /**
     * @ORM\OneToMany(targetEntity="AfiFacturaDetalleCurso", mappedBy="cursoRel")
     */
    protected $facturasDetallesCursosCursoRel;    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cursosDetallesCursoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->facturasDetallesCursosCursoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCursoPk
     *
     * @return integer
     */
    public function getCodigoCursoPk()
    {
        return $this->codigoCursoPk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return AfiCurso
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AfiCurso
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
     * Set fechaVence
     *
     * @param \DateTime $fechaVence
     *
     * @return AfiCurso
     */
    public function setFechaVence($fechaVence)
    {
        $this->fechaVence = $fechaVence;

        return $this;
    }

    /**
     * Get fechaVence
     *
     * @return \DateTime
     */
    public function getFechaVence()
    {
        return $this->fechaVence;
    }

    /**
     * Set fechaProgramacion
     *
     * @param \DateTime $fechaProgramacion
     *
     * @return AfiCurso
     */
    public function setFechaProgramacion($fechaProgramacion)
    {
        $this->fechaProgramacion = $fechaProgramacion;

        return $this;
    }

    /**
     * Get fechaProgramacion
     *
     * @return \DateTime
     */
    public function getFechaProgramacion()
    {
        return $this->fechaProgramacion;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiCurso
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return AfiCurso
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
     * Set codigoEntidadEntrenamientoFk
     *
     * @param integer $codigoEntidadEntrenamientoFk
     *
     * @return AfiCurso
     */
    public function setCodigoEntidadEntrenamientoFk($codigoEntidadEntrenamientoFk)
    {
        $this->codigoEntidadEntrenamientoFk = $codigoEntidadEntrenamientoFk;

        return $this;
    }

    /**
     * Get codigoEntidadEntrenamientoFk
     *
     * @return integer
     */
    public function getCodigoEntidadEntrenamientoFk()
    {
        return $this->codigoEntidadEntrenamientoFk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return AfiCurso
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return AfiCurso
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
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiCurso
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiCurso
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set asistencia
     *
     * @param boolean $asistencia
     *
     * @return AfiCurso
     */
    public function setAsistencia($asistencia)
    {
        $this->asistencia = $asistencia;

        return $this;
    }

    /**
     * Get asistencia
     *
     * @return boolean
     */
    public function getAsistencia()
    {
        return $this->asistencia;
    }

    /**
     * Set certificado
     *
     * @param boolean $certificado
     *
     * @return AfiCurso
     */
    public function setCertificado($certificado)
    {
        $this->certificado = $certificado;

        return $this;
    }

    /**
     * Get certificado
     *
     * @return boolean
     */
    public function getCertificado()
    {
        return $this->certificado;
    }

    /**
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return AfiCurso
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
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return AfiCurso
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set estadoFacturado
     *
     * @param boolean $estadoFacturado
     *
     * @return AfiCurso
     */
    public function setEstadoFacturado($estadoFacturado)
    {
        $this->estadoFacturado = $estadoFacturado;

        return $this;
    }

    /**
     * Get estadoFacturado
     *
     * @return boolean
     */
    public function getEstadoFacturado()
    {
        return $this->estadoFacturado;
    }

    /**
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return AfiCurso
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
     * Set clienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel
     *
     * @return AfiCurso
     */
    public function setClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set empleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel
     *
     * @return AfiCurso
     */
    public function setEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set entidadEntrenamientoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento $entidadEntrenamientoRel
     *
     * @return AfiCurso
     */
    public function setEntidadEntrenamientoRel(\Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento $entidadEntrenamientoRel = null)
    {
        $this->entidadEntrenamientoRel = $entidadEntrenamientoRel;

        return $this;
    }

    /**
     * Get entidadEntrenamientoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento
     */
    public function getEntidadEntrenamientoRel()
    {
        return $this->entidadEntrenamientoRel;
    }

    /**
     * Add cursosDetallesCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel
     *
     * @return AfiCurso
     */
    public function addCursosDetallesCursoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel)
    {
        $this->cursosDetallesCursoRel[] = $cursosDetallesCursoRel;

        return $this;
    }

    /**
     * Remove cursosDetallesCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel
     */
    public function removeCursosDetallesCursoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursosDetallesCursoRel)
    {
        $this->cursosDetallesCursoRel->removeElement($cursosDetallesCursoRel);
    }

    /**
     * Get cursosDetallesCursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursosDetallesCursoRel()
    {
        return $this->cursosDetallesCursoRel;
    }

    /**
     * Add facturasDetallesCursosCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosCursoRel
     *
     * @return AfiCurso
     */
    public function addFacturasDetallesCursosCursoRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosCursoRel)
    {
        $this->facturasDetallesCursosCursoRel[] = $facturasDetallesCursosCursoRel;

        return $this;
    }

    /**
     * Remove facturasDetallesCursosCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosCursoRel
     */
    public function removeFacturasDetallesCursosCursoRel(\Brasa\AfiliacionBundle\Entity\AfiFacturaDetalleCurso $facturasDetallesCursosCursoRel)
    {
        $this->facturasDetallesCursosCursoRel->removeElement($facturasDetallesCursosCursoRel);
    }

    /**
     * Get facturasDetallesCursosCursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacturasDetallesCursosCursoRel()
    {
        return $this->facturasDetallesCursosCursoRel;
    }
}
