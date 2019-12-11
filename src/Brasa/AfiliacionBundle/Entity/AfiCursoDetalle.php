<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_curso_detalle")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCursoDetalleRepository")
 */
class AfiCursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_curso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCursoDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_curso_fk", type="integer")
     */    
    private $codigoCursoFk;                
    
    /**
     * @ORM\Column(name="codigo_curso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCursoTipoFk;    

    /**
     * @ORM\Column(name="codigo_proveedor_fk", type="integer", nullable=true)
     */    
    private $codigoProveedorFk;    
    
    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;    

    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;    
    
    /**     
     * @ORM\Column(name="estado_pagado", type="boolean")
     */    
    private $estadoPagado = false;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCurso", inversedBy="cursosDetallesCursoRel")
     * @ORM\JoinColumn(name="codigo_curso_fk", referencedColumnName="codigo_curso_pk")
     */
    protected $cursoRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCursoTipo", inversedBy="cursosDetallesCursoTipoRel")
     * @ORM\JoinColumn(name="codigo_curso_tipo_fk", referencedColumnName="codigo_curso_tipo_pk")
     */
    protected $cursoTipoRel;     

    /**
     * @ORM\ManyToOne(targetEntity="AfiProveedor", inversedBy="cursosDetallesProveedorRel")
     * @ORM\JoinColumn(name="codigo_proveedor_fk", referencedColumnName="codigo_proveedor_pk")
     */
    protected $proveedorRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="AfiPagoCursoDetalle", mappedBy="cursoDetalleRel")
     */
    protected $pagosCursosDetallesCursoDetalleRel;  

    /**
     * Get codigoCursoDetallePk
     *
     * @return integer
     */
    public function getCodigoCursoDetallePk()
    {
        return $this->codigoCursoDetallePk;
    }

    /**
     * Set codigoCursoFk
     *
     * @param integer $codigoCursoFk
     *
     * @return AfiCursoDetalle
     */
    public function setCodigoCursoFk($codigoCursoFk)
    {
        $this->codigoCursoFk = $codigoCursoFk;

        return $this;
    }

    /**
     * Get codigoCursoFk
     *
     * @return integer
     */
    public function getCodigoCursoFk()
    {
        return $this->codigoCursoFk;
    }

    /**
     * Set codigoCursoTipoFk
     *
     * @param integer $codigoCursoTipoFk
     *
     * @return AfiCursoDetalle
     */
    public function setCodigoCursoTipoFk($codigoCursoTipoFk)
    {
        $this->codigoCursoTipoFk = $codigoCursoTipoFk;

        return $this;
    }

    /**
     * Get codigoCursoTipoFk
     *
     * @return integer
     */
    public function getCodigoCursoTipoFk()
    {
        return $this->codigoCursoTipoFk;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiCursoDetalle
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
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiCursoDetalle
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set cursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursoRel
     *
     * @return AfiCursoDetalle
     */
    public function setCursoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursoRel = null)
    {
        $this->cursoRel = $cursoRel;

        return $this;
    }

    /**
     * Get cursoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCurso
     */
    public function getCursoRel()
    {
        return $this->cursoRel;
    }

    /**
     * Set cursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursoTipoRel
     *
     * @return AfiCursoDetalle
     */
    public function setCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursoTipoRel = null)
    {
        $this->cursoTipoRel = $cursoTipoRel;

        return $this;
    }

    /**
     * Get cursoTipoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCursoTipo
     */
    public function getCursoTipoRel()
    {
        return $this->cursoTipoRel;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosCursosDetallesCursoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pagosCursosDetallesCursoDetalleRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesCursoDetalleRel
     *
     * @return AfiCursoDetalle
     */
    public function addPagosCursosDetallesCursoDetalleRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesCursoDetalleRel)
    {
        $this->pagosCursosDetallesCursoDetalleRel[] = $pagosCursosDetallesCursoDetalleRel;

        return $this;
    }

    /**
     * Remove pagosCursosDetallesCursoDetalleRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesCursoDetalleRel
     */
    public function removePagosCursosDetallesCursoDetalleRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCursoDetalle $pagosCursosDetallesCursoDetalleRel)
    {
        $this->pagosCursosDetallesCursoDetalleRel->removeElement($pagosCursosDetallesCursoDetalleRel);
    }

    /**
     * Get pagosCursosDetallesCursoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosCursosDetallesCursoDetalleRel()
    {
        return $this->pagosCursosDetallesCursoDetalleRel;
    }

    /**
     * Set codigoProveedorFk
     *
     * @param integer $codigoProveedorFk
     *
     * @return AfiCursoDetalle
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
     * Set proveedorRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiProveedor $proveedorRel
     *
     * @return AfiCursoDetalle
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
     * Set estadoPagado
     *
     * @param boolean $estadoPagado
     *
     * @return AfiCursoDetalle
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
}
