<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_pago_curso")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiPagoCursoRepository")
 */
class AfiPagoCurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_curso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoCursoPk;    
          
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero = 0;
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;           
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="integer", nullable=true)
     */    
    private $codigoCuentaFk;    
    
    /**
     * @ORM\Column(name="soporte", type="string", length=30, nullable=true)
     */
    private $soporte;     
    
    /**
     * @ORM\Column(name="codigo_proveedor_fk", type="integer", nullable=true)
     */    
    private $codigoProveedorFk;               
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = false;

    /**     
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = false;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiProveedor", inversedBy="pagosCursosProveedorRel")
     * @ORM\JoinColumn(name="codigo_proveedor_fk", referencedColumnName="codigo_proveedor_pk")
     */
    protected $proveedorRel;    

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCuenta", inversedBy="afiPagosCursosCuentaRel")
     * @ORM\JoinColumn(name="codigo_cuenta_fk", referencedColumnName="codigo_cuenta_pk")
     */
    protected $cuentaRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="AfiPagoCursoDetalle", mappedBy="pagoCursoRel")
     */
    protected $pagosCursosDetallesPagoCursoRel;     


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosCursosDetallesPagoCursoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoCursoPk
     *
     * @return integer
     */
    public function getCodigoPagoCursoPk()
    {
        return $this->codigoPagoCursoPk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return AfiPagoCurso
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
     * @return AfiPagoCurso
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
     * Set codigoCuentaFk
     *
     * @param integer $codigoCuentaFk
     *
     * @return AfiPagoCurso
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return integer
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set soporte
     *
     * @param string $soporte
     *
     * @return AfiPagoCurso
     */
    public function setSoporte($soporte)
    {
        $this->soporte = $soporte;

        return $this;
    }

    /**
     * Get soporte
     *
     * @return string
     */
    public function getSoporte()
    {
        return $this->soporte;
    }

    /**
     * Set codigoProveedorFk
     *
     * @param integer $codigoProveedorFk
     *
     * @return AfiPagoCurso
     */
    public function setCodigoProveedorFk($codigoProveedorFk)
    {
        $this->codigoProveedorFk = $codigoProveedorFk;

        return $this;
    }

    /**
     * Get codigoProveedorFk
     *
     * @return integer
     */
    public function getCodigoProveedorFk()
    {
        return $this->codigoProveedorFk;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiPagoCurso
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
     * @return AfiPagoCurso
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
     * @return AfiPagoCurso
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
     * Set usuario
     *
     * @param string $usuario
     *
     * @return AfiPagoCurso
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return AfiPagoCurso
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
     * Set proveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiProveedor $proveedorRel
     *
     * @return AfiPagoCurso
     */
    public function setProveedorRel(\Brasa\AfiliacionBundle\Entity\AfiProveedor $proveedorRel = null)
    {
        $this->proveedorRel = $proveedorRel;

        return $this;
    }

    /**
     * Get proveedorRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiProveedor
     */
    public function getProveedorRel()
    {
        return $this->proveedorRel;
    }

    /**
     * Set cuentaRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel
     *
     * @return AfiPagoCurso
     */
    public function setCuentaRel(\Brasa\GeneralBundle\Entity\GenCuenta $cuentaRel = null)
    {
        $this->cuentaRel = $cuentaRel;

        return $this;
    }

    /**
     * Get cuentaRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCuenta
     */
    public function getCuentaRel()
    {
        return $this->cuentaRel;
    }

    /**
     * Add pagosCursosDetallesPagoCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesPagoCursoRel
     *
     * @return AfiPagoCurso
     */
    public function addPagosCursosDetallesPagoCursoRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesPagoCursoRel)
    {
        $this->pagosCursosDetallesPagoCursoRel[] = $pagosCursosDetallesPagoCursoRel;

        return $this;
    }

    /**
     * Remove pagosCursosDetallesPagoCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesPagoCursoRel
     */
    public function removePagosCursosDetallesPagoCursoRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesPagoCursoRel)
    {
        $this->pagosCursosDetallesPagoCursoRel->removeElement($pagosCursosDetallesPagoCursoRel);
    }

    /**
     * Get pagosCursosDetallesPagoCursoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosCursosDetallesPagoCursoRel()
    {
        return $this->pagosCursosDetallesPagoCursoRel;
    }
}
