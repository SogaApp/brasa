<?php

namespace Brasa\TransporteBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_cliente")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteClienteRepository")
 * @DoctrineAssert\UniqueEntity(fields={"nit"},message="Ya existe este nit")
 */
class TteCliente
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cliente_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoClientePk;  
    
    /**
     * @ORM\Column(name="nit", type="string", length=15, nullable=false, unique=true)
     */
    private $nit;    
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=50)
     */
    private $nombreCorto;    

    /**
     * @ORM\Column(name="codigo_lista_precio_fk", type="integer", nullable=true)
     */    
    private $codigoListaPrecioFk;
    
    /**
     * @ORM\Column(name="liquidar_automaticamente_flete", type="boolean")
     */    
    private $liquidarAutomaticamenteFlete = 0;     

    /**
     * @ORM\Column(name="liquidar_automaticamente_manejo", type="boolean")
     */    
    private $liquidarAutomaticamenteManejo = 0;         
    
    /**
     * @ORM\Column(name="porcentaje_manejo", type="float")
     */
    private $porcentajeManejo = 0;     

    /**
     * @ORM\Column(name="vr_manejo_minimo_unidad", type="float")
     */
    private $vrManejoMinimoUnidad = 0;    

    /**
     * @ORM\Column(name="vr_manejo_minimo_despacho", type="float")
     */
    private $vrManejoMinimoDespacho = 0;        
    
    /**
     * @ORM\Column(name="descuento_kilos", type="float")
     */
    private $descuentoKilos = 0;     

    /**
     * @ORM\Column(name="ct_peso_minimo_unidad", type="float")
     */
    private $ctPesoMinimoUnidad = 0;        

    /**
     * @ORM\Column(name="paga_manejo_corriente", type="boolean")
     */
    private $pagaManejoCorriente = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TteListaPrecio", inversedBy="clientesListaPrecioRel")
     * @ORM\JoinColumn(name="codigo_lista_precio_fk", referencedColumnName="codigo_lista_precio_pk")
     */
    protected $listaPrecioRel;         



    /**
     * Get codigoClientePk
     *
     * @return integer
     */
    public function getCodigoClientePk()
    {
        return $this->codigoClientePk;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return TteCliente
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return TteCliente
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
     * Set codigoListaPrecioFk
     *
     * @param integer $codigoListaPrecioFk
     *
     * @return TteCliente
     */
    public function setCodigoListaPrecioFk($codigoListaPrecioFk)
    {
        $this->codigoListaPrecioFk = $codigoListaPrecioFk;

        return $this;
    }

    /**
     * Get codigoListaPrecioFk
     *
     * @return integer
     */
    public function getCodigoListaPrecioFk()
    {
        return $this->codigoListaPrecioFk;
    }

    /**
     * Set liquidarAutomaticamenteFlete
     *
     * @param boolean $liquidarAutomaticamenteFlete
     *
     * @return TteCliente
     */
    public function setLiquidarAutomaticamenteFlete($liquidarAutomaticamenteFlete)
    {
        $this->liquidarAutomaticamenteFlete = $liquidarAutomaticamenteFlete;

        return $this;
    }

    /**
     * Get liquidarAutomaticamenteFlete
     *
     * @return boolean
     */
    public function getLiquidarAutomaticamenteFlete()
    {
        return $this->liquidarAutomaticamenteFlete;
    }

    /**
     * Set liquidarAutomaticamenteManejo
     *
     * @param boolean $liquidarAutomaticamenteManejo
     *
     * @return TteCliente
     */
    public function setLiquidarAutomaticamenteManejo($liquidarAutomaticamenteManejo)
    {
        $this->liquidarAutomaticamenteManejo = $liquidarAutomaticamenteManejo;

        return $this;
    }

    /**
     * Get liquidarAutomaticamenteManejo
     *
     * @return boolean
     */
    public function getLiquidarAutomaticamenteManejo()
    {
        return $this->liquidarAutomaticamenteManejo;
    }

    /**
     * Set porcentajeManejo
     *
     * @param float $porcentajeManejo
     *
     * @return TteCliente
     */
    public function setPorcentajeManejo($porcentajeManejo)
    {
        $this->porcentajeManejo = $porcentajeManejo;

        return $this;
    }

    /**
     * Get porcentajeManejo
     *
     * @return float
     */
    public function getPorcentajeManejo()
    {
        return $this->porcentajeManejo;
    }

    /**
     * Set vrManejoMinimoUnidad
     *
     * @param float $vrManejoMinimoUnidad
     *
     * @return TteCliente
     */
    public function setVrManejoMinimoUnidad($vrManejoMinimoUnidad)
    {
        $this->vrManejoMinimoUnidad = $vrManejoMinimoUnidad;

        return $this;
    }

    /**
     * Get vrManejoMinimoUnidad
     *
     * @return float
     */
    public function getVrManejoMinimoUnidad()
    {
        return $this->vrManejoMinimoUnidad;
    }

    /**
     * Set vrManejoMinimoDespacho
     *
     * @param float $vrManejoMinimoDespacho
     *
     * @return TteCliente
     */
    public function setVrManejoMinimoDespacho($vrManejoMinimoDespacho)
    {
        $this->vrManejoMinimoDespacho = $vrManejoMinimoDespacho;

        return $this;
    }

    /**
     * Get vrManejoMinimoDespacho
     *
     * @return float
     */
    public function getVrManejoMinimoDespacho()
    {
        return $this->vrManejoMinimoDespacho;
    }

    /**
     * Set descuentoKilos
     *
     * @param float $descuentoKilos
     *
     * @return TteCliente
     */
    public function setDescuentoKilos($descuentoKilos)
    {
        $this->descuentoKilos = $descuentoKilos;

        return $this;
    }

    /**
     * Get descuentoKilos
     *
     * @return float
     */
    public function getDescuentoKilos()
    {
        return $this->descuentoKilos;
    }

    /**
     * Set ctPesoMinimoUnidad
     *
     * @param float $ctPesoMinimoUnidad
     *
     * @return TteCliente
     */
    public function setCtPesoMinimoUnidad($ctPesoMinimoUnidad)
    {
        $this->ctPesoMinimoUnidad = $ctPesoMinimoUnidad;

        return $this;
    }

    /**
     * Get ctPesoMinimoUnidad
     *
     * @return float
     */
    public function getCtPesoMinimoUnidad()
    {
        return $this->ctPesoMinimoUnidad;
    }

    /**
     * Set pagaManejoCorriente
     *
     * @param boolean $pagaManejoCorriente
     *
     * @return TteCliente
     */
    public function setPagaManejoCorriente($pagaManejoCorriente)
    {
        $this->pagaManejoCorriente = $pagaManejoCorriente;

        return $this;
    }

    /**
     * Get pagaManejoCorriente
     *
     * @return boolean
     */
    public function getPagaManejoCorriente()
    {
        return $this->pagaManejoCorriente;
    }

    /**
     * Set listaPrecioRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteListaPrecio $listaPrecioRel
     *
     * @return TteCliente
     */
    public function setListaPrecioRel(\Brasa\TransporteBundle\Entity\TteListaPrecio $listaPrecioRel = null)
    {
        $this->listaPrecioRel = $listaPrecioRel;

        return $this;
    }

    /**
     * Get listaPrecioRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteListaPrecio
     */
    public function getListaPrecioRel()
    {
        return $this->listaPrecioRel;
    }
}
