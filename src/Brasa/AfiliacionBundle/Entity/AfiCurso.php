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
     * @ORM\OneToMany(targetEntity="AfiCursoDetalle", mappedBy="cursoRel")
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
     * Set vence
     *
     * @param \DateTime $vence
     *
     * @return AfiCurso
     */
    public function setVence($vence)
    {
        $this->vence = $vence;

        return $this;
    }

    /**
     * Get vence
     *
     * @return \DateTime
     */
    public function getVence()
    {
        return $this->vence;
    }

    /**
     * Set programacion
     *
     * @param \DateTime $programacion
     *
     * @return AfiCurso
     */
    public function setProgramacion($programacion)
    {
        $this->programacion = $programacion;

        return $this;
    }

    /**
     * Get programacion
     *
     * @return \DateTime
     */
    public function getProgramacion()
    {
        return $this->programacion;
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
}
