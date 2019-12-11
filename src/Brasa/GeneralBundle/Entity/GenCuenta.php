<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_cuenta")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenCuentaRepository")
 */
class GenCuenta
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cuenta_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCuentaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=60)
     */
    private $nombre;
    
    /**
     * @ORM\Column(name="cuenta", type="string", length=20)
     */
    private $cuenta;
    
    /**
     * @ORM\Column(name="tipo", type="string", length=60)
     */
    private $tipo;
    
    /**
     * @ORM\Column(name="codigo_banco_fk", type="integer")
     */
    private $codigoBancoFk;
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20)
     */
    private $codigoCuentaFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="GenBanco", inversedBy="cuentasBancoRel")
     * @ORM\JoinColumn(name="codigo_banco_fk", referencedColumnName="codigo_banco_pk")
     */
    protected $bancoRel;        

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco", mappedBy="cuentaRel")
     */
    protected $rhuPagosBancosCuentaRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarRecibo", mappedBy="cuentaRel")
     */
    protected $carRecibosCuentaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarNotaDebito", mappedBy="cuentaRel")
     */
    protected $carNotasDebitosCuentaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarNotaCredito", mappedBy="cuentaRel")
     */
    protected $carNotasCreditosCuentaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiPagoCurso", mappedBy="cuentaRel")
     */
    protected $afiPagosCursosCuentaRel;  
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarAnticipo", mappedBy="cuentaRel")
     */
    protected $carAnticiposCuentaRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuPagosBancosCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carRecibosCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carNotasDebitosCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carNotasCreditosCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiPagosCursosCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carAnticiposCuentaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoCuentaPk
     *
     * @return integer
     */
    public function getCodigoCuentaPk()
    {
        return $this->codigoCuentaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenCuenta
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
     * Set cuenta
     *
     * @param string $cuenta
     *
     * @return GenCuenta
     */
    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;

        return $this;
    }

    /**
     * Get cuenta
     *
     * @return string
     */
    public function getCuenta()
    {
        return $this->cuenta;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return GenCuenta
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set codigoBancoFk
     *
     * @param integer $codigoBancoFk
     *
     * @return GenCuenta
     */
    public function setCodigoBancoFk($codigoBancoFk)
    {
        $this->codigoBancoFk = $codigoBancoFk;

        return $this;
    }

    /**
     * Get codigoBancoFk
     *
     * @return integer
     */
    public function getCodigoBancoFk()
    {
        return $this->codigoBancoFk;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return GenCuenta
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
     * Set bancoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenBanco $bancoRel
     *
     * @return GenCuenta
     */
    public function setBancoRel(\Brasa\GeneralBundle\Entity\GenBanco $bancoRel = null)
    {
        $this->bancoRel = $bancoRel;

        return $this;
    }

    /**
     * Get bancoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenBanco
     */
    public function getBancoRel()
    {
        return $this->bancoRel;
    }

    /**
     * Add rhuPagosBancosCuentaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel
     *
     * @return GenCuenta
     */
    public function addRhuPagosBancosCuentaRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel)
    {
        $this->rhuPagosBancosCuentaRel[] = $rhuPagosBancosCuentaRel;

        return $this;
    }

    /**
     * Remove rhuPagosBancosCuentaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel
     */
    public function removeRhuPagosBancosCuentaRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $rhuPagosBancosCuentaRel)
    {
        $this->rhuPagosBancosCuentaRel->removeElement($rhuPagosBancosCuentaRel);
    }

    /**
     * Get rhuPagosBancosCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuPagosBancosCuentaRel()
    {
        return $this->rhuPagosBancosCuentaRel;
    }

    /**
     * Add carRecibosCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $carRecibosCuentaRel
     *
     * @return GenCuenta
     */
    public function addCarRecibosCuentaRel(\Brasa\CarteraBundle\Entity\CarRecibo $carRecibosCuentaRel)
    {
        $this->carRecibosCuentaRel[] = $carRecibosCuentaRel;

        return $this;
    }

    /**
     * Remove carRecibosCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarRecibo $carRecibosCuentaRel
     */
    public function removeCarRecibosCuentaRel(\Brasa\CarteraBundle\Entity\CarRecibo $carRecibosCuentaRel)
    {
        $this->carRecibosCuentaRel->removeElement($carRecibosCuentaRel);
    }

    /**
     * Get carRecibosCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarRecibosCuentaRel()
    {
        return $this->carRecibosCuentaRel;
    }

    /**
     * Add carNotasDebitosCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebito $carNotasDebitosCuentaRel
     *
     * @return GenCuenta
     */
    public function addCarNotasDebitosCuentaRel(\Brasa\CarteraBundle\Entity\CarNotaDebito $carNotasDebitosCuentaRel)
    {
        $this->carNotasDebitosCuentaRel[] = $carNotasDebitosCuentaRel;

        return $this;
    }

    /**
     * Remove carNotasDebitosCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaDebito $carNotasDebitosCuentaRel
     */
    public function removeCarNotasDebitosCuentaRel(\Brasa\CarteraBundle\Entity\CarNotaDebito $carNotasDebitosCuentaRel)
    {
        $this->carNotasDebitosCuentaRel->removeElement($carNotasDebitosCuentaRel);
    }

    /**
     * Get carNotasDebitosCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarNotasDebitosCuentaRel()
    {
        return $this->carNotasDebitosCuentaRel;
    }

    /**
     * Add carNotasCreditosCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCredito $carNotasCreditosCuentaRel
     *
     * @return GenCuenta
     */
    public function addCarNotasCreditosCuentaRel(\Brasa\CarteraBundle\Entity\CarNotaCredito $carNotasCreditosCuentaRel)
    {
        $this->carNotasCreditosCuentaRel[] = $carNotasCreditosCuentaRel;

        return $this;
    }

    /**
     * Remove carNotasCreditosCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarNotaCredito $carNotasCreditosCuentaRel
     */
    public function removeCarNotasCreditosCuentaRel(\Brasa\CarteraBundle\Entity\CarNotaCredito $carNotasCreditosCuentaRel)
    {
        $this->carNotasCreditosCuentaRel->removeElement($carNotasCreditosCuentaRel);
    }

    /**
     * Get carNotasCreditosCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarNotasCreditosCuentaRel()
    {
        return $this->carNotasCreditosCuentaRel;
    }

    /**
     * Add afiPagosCursosCuentaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCurso $afiPagosCursosCuentaRel
     *
     * @return GenCuenta
     */
    public function addAfiPagosCursosCuentaRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCurso $afiPagosCursosCuentaRel)
    {
        $this->afiPagosCursosCuentaRel[] = $afiPagosCursosCuentaRel;

        return $this;
    }

    /**
     * Remove afiPagosCursosCuentaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCurso $afiPagosCursosCuentaRel
     */
    public function removeAfiPagosCursosCuentaRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCurso $afiPagosCursosCuentaRel)
    {
        $this->afiPagosCursosCuentaRel->removeElement($afiPagosCursosCuentaRel);
    }

    /**
     * Get afiPagosCursosCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiPagosCursosCuentaRel()
    {
        return $this->afiPagosCursosCuentaRel;
    }

    /**
     * Add carAnticiposCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposCuentaRel
     *
     * @return GenCuenta
     */
    public function addCarAnticiposCuentaRel(\Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposCuentaRel)
    {
        $this->carAnticiposCuentaRel[] = $carAnticiposCuentaRel;

        return $this;
    }

    /**
     * Remove carAnticiposCuentaRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposCuentaRel
     */
    public function removeCarAnticiposCuentaRel(\Brasa\CarteraBundle\Entity\CarAnticipo $carAnticiposCuentaRel)
    {
        $this->carAnticiposCuentaRel->removeElement($carAnticiposCuentaRel);
    }

    /**
     * Get carAnticiposCuentaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarAnticiposCuentaRel()
    {
        return $this->carAnticiposCuentaRel;
    }
}
