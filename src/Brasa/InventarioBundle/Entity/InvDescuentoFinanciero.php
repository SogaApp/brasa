<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="inv_descuento_financiero")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvDescuentoFinancieroRepository")
 */
class InvDescuentoFinanciero
{
    /** 
     * @ORM\Id
     * @ORM\Column(name="codigo_descuento_financiero_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */    
    private $codigoDescuentoFinancieroPk;
        
    /**
     * @ORM\Column(name="nombre", type="string", length=40, nullable=true)
     */      
    private $nombre; 

    /**
     * @ORM\Column(name="porcenaje", type="float")
     */    
    private $porcentaje = 0;    
    
    /**
     * @ORM\Column(name="referencia", type="string", length=40, nullable=true)
     */      
    private $referencia;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */     
    private $codigoCuentaFk; 
    
    /**
     * @ORM\Column(name="tipo_registro", type="smallint")
     */    
    private $tipoRegistro = 1;     
                 
    
    /**
     * @ORM\OneToMany(targetEntity="InvMovimientoDescuentoFinanciero", mappedBy="descuentoFinancieroRel")
     */
    protected $movimientosDescuentosFinancierosDescuentoFinancieroRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movimientosDescuentosFinancierosDescuentoFinancieroRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoDescuentoFinancieroPk
     *
     * @return integer
     */
    public function getCodigoDescuentoFinancieroPk()
    {
        return $this->codigoDescuentoFinancieroPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvDescuentoFinanciero
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
     * Set porcentaje
     *
     * @param float $porcentaje
     *
     * @return InvDescuentoFinanciero
     */
    public function setPorcentaje($porcentaje)
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * Get porcentaje
     *
     * @return float
     */
    public function getPorcentaje()
    {
        return $this->porcentaje;
    }

    /**
     * Set referencia
     *
     * @param string $referencia
     *
     * @return InvDescuentoFinanciero
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return string
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return InvDescuentoFinanciero
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set tipoRegistro
     *
     * @param integer $tipoRegistro
     *
     * @return InvDescuentoFinanciero
     */
    public function setTipoRegistro($tipoRegistro)
    {
        $this->tipoRegistro = $tipoRegistro;

        return $this;
    }

    /**
     * Get tipoRegistro
     *
     * @return integer
     */
    public function getTipoRegistro()
    {
        return $this->tipoRegistro;
    }

    /**
     * Add movimientosDescuentosFinancierosDescuentoFinancieroRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosDescuentoFinancieroRel
     *
     * @return InvDescuentoFinanciero
     */
    public function addMovimientosDescuentosFinancierosDescuentoFinancieroRel(\Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosDescuentoFinancieroRel)
    {
        $this->movimientosDescuentosFinancierosDescuentoFinancieroRel[] = $movimientosDescuentosFinancierosDescuentoFinancieroRel;

        return $this;
    }

    /**
     * Remove movimientosDescuentosFinancierosDescuentoFinancieroRel
     *
     * @param \Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosDescuentoFinancieroRel
     */
    public function removeMovimientosDescuentosFinancierosDescuentoFinancieroRel(\Brasa\InventarioBundle\Entity\InvMovimientoDescuentoFinanciero $movimientosDescuentosFinancierosDescuentoFinancieroRel)
    {
        $this->movimientosDescuentosFinancierosDescuentoFinancieroRel->removeElement($movimientosDescuentosFinancierosDescuentoFinancieroRel);
    }

    /**
     * Get movimientosDescuentosFinancierosDescuentoFinancieroRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMovimientosDescuentosFinancierosDescuentoFinancieroRel()
    {
        return $this->movimientosDescuentosFinancierosDescuentoFinancieroRel;
    }
}
