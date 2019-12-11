<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_cuenta_cobrar_tipo")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarCuentaCobrarTipoRepository")
 */
class CarCuentaCobrarTipo
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_cobrar_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */        
    private $codigoCuentaCobrarTipoPk;        

    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="CarCuentaCobrar", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposCuentaCobrarRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarReciboDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposReciboDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarAnticipoDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposAnticipoDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaDebitoDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposNotaDebitoDetalleRel;
    
    /**
     * @ORM\OneToMany(targetEntity="CarNotaCreditoDetalle", mappedBy="cuentaCobrarTipoRel")
     */
    protected $cuentasCobrarTiposNotaCreditoDetalleRel;
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cuentasCobrarTiposCuentaCobrarRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposReciboDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposAnticipoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposNotaDebitoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cuentasCobrarTiposNotaCreditoDetalleRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCuentaCobrarTipoPk
     *
     * @return integer
     */
    public function getCodigoCuentaCobrarTipoPk()
    {
        return $this->codigoCuentaCobrarTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return CarCuentaCobrarTipo
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
     * Add cuentasCobrarTiposCuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel)
    {
        $this->cuentasCobrarTiposCuentaCobrarRel[] = $cuentasCobrarTiposCuentaCobrarRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposCuentaCobrarRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel
     */
    public function removeCuentasCobrarTiposCuentaCobrarRel(\Brasa\CarteraBundle\Entity\CarCuentaCobrar $cuentasCobrarTiposCuentaCobrarRel)
    {
        $this->cuentasCobrarTiposCuentaCobrarRel->removeElement($cuentasCobrarTiposCuentaCobrarRel);
    }

    /**
     * Get cuentasCobrarTiposCuentaCobrarRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposCuentaCobrarRel()
    {
        return $this->cuentasCobrarTiposCuentaCobrarRel;
    }

    /**
     * Add cuentasCobrarTiposReciboDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposReciboDetalleRel(\Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel)
    {
        $this->cuentasCobrarTiposReciboDetalleRel[] = $cuentasCobrarTiposReciboDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposReciboDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel
     */
    public function removeCuentasCobrarTiposReciboDetalleRel(\Brasa\CarteraBundle\Entity\CarReciboDetalle $cuentasCobrarTiposReciboDetalleRel)
    {
        $this->cuentasCobrarTiposReciboDetalleRel->removeElement($cuentasCobrarTiposReciboDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposReciboDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposReciboDetalleRel()
    {
        return $this->cuentasCobrarTiposReciboDetalleRel;
    }

    /**
     * Add cuentasCobrarTiposAnticipoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposAnticipoDetalleRel(\Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel)
    {
        $this->cuentasCobrarTiposAnticipoDetalleRel[] = $cuentasCobrarTiposAnticipoDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposAnticipoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel
     */
    public function removeCuentasCobrarTiposAnticipoDetalleRel(\Brasa\CarteraBundle\Entity\CarAnticipoDetalle $cuentasCobrarTiposAnticipoDetalleRel)
    {
        $this->cuentasCobrarTiposAnticipoDetalleRel->removeElement($cuentasCobrarTiposAnticipoDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposAnticipoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposAnticipoDetalleRel()
    {
        return $this->cuentasCobrarTiposAnticipoDetalleRel;
    }

    /**
     * Add cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposNotaDebitoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaDebitoDetalleRel[] = $cuentasCobrarTiposNotaDebitoDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel
     */
    public function removeCuentasCobrarTiposNotaDebitoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaDebitoDetalle $cuentasCobrarTiposNotaDebitoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaDebitoDetalleRel->removeElement($cuentasCobrarTiposNotaDebitoDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposNotaDebitoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposNotaDebitoDetalleRel()
    {
        return $this->cuentasCobrarTiposNotaDebitoDetalleRel;
    }

    /**
     * Add cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @return CarCuentaCobrarTipo
     */
    public function addCuentasCobrarTiposNotaCreditoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaCreditoDetalleRel[] = $cuentasCobrarTiposNotaCreditoDetalleRel;

        return $this;
    }

    /**
     * Remove cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel
     */
    public function removeCuentasCobrarTiposNotaCreditoDetalleRel(\Brasa\CarteraBundle\Entity\CarNotaCreditoDetalle $cuentasCobrarTiposNotaCreditoDetalleRel)
    {
        $this->cuentasCobrarTiposNotaCreditoDetalleRel->removeElement($cuentasCobrarTiposNotaCreditoDetalleRel);
    }

    /**
     * Get cuentasCobrarTiposNotaCreditoDetalleRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuentasCobrarTiposNotaCreditoDetalleRel()
    {
        return $this->cuentasCobrarTiposNotaCreditoDetalleRel;
    }
}
