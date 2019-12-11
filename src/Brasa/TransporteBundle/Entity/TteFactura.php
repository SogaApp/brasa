<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_factura")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteFacturaRepository")
 */
class TteFactura
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaPk;  
    
    /**
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */    
    private $numero;    
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;  
    
    /**
     * @ORM\Column(name="fecha_vencimiento", type="datetime", nullable=true)
     */    
    private $fechaVencimiento;    
    
    /**
     * @ORM\Column(name="codigo_tercero_fk", type="integer", nullable=true)
     */    
    private $codigoTerceroFk;    
    
    /**
     * @ORM\Column(name="ct_unidades", type="float")
     */
    private $ctUnidades = 0;    
    
    /**
     * @ORM\Column(name="vr_flete", type="float")
     */
    private $vrFlete = 0;    
    
    /**
     * @ORM\Column(name="vr_manejo", type="float")
     */
    private $vrManejo = 0;    

    /**
     * @ORM\Column(name="vr_otros", type="float")
     */
    private $vrOtros = 0;    
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;        

    /**
     * @ORM\Column(name="ct_guias", type="float")
     */
    private $ctGuias = 0;    
    
    /**
     * @ORM\Column(name="ct_planillas", type="float")
     */
    private $ctPlanillas = 0;    
    
    /**
     * @ORM\Column(name="ct_conceptos", type="float")
     */
    private $ctConceptos = 0;    
    
    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpreso = 0;     
    
    /**
     * @ORM\Column(name="estado_anulada", type="boolean")
     */    
    private $estadoAnulada = 0;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="facturaRel")
     */
    protected $guiasDetallesRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->guiasDetallesRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFacturaPk
     *
     * @return integer
     */
    public function getCodigoFacturaPk()
    {
        return $this->codigoFacturaPk;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return TteFactura
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
     * @return TteFactura
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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return TteFactura
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fechaVencimiento;
    }

    /**
     * Set codigoTerceroFk
     *
     * @param integer $codigoTerceroFk
     *
     * @return TteFactura
     */
    public function setCodigoTerceroFk($codigoTerceroFk)
    {
        $this->codigoTerceroFk = $codigoTerceroFk;

        return $this;
    }

    /**
     * Get codigoTerceroFk
     *
     * @return integer
     */
    public function getCodigoTerceroFk()
    {
        return $this->codigoTerceroFk;
    }

    /**
     * Set ctUnidades
     *
     * @param float $ctUnidades
     *
     * @return TteFactura
     */
    public function setCtUnidades($ctUnidades)
    {
        $this->ctUnidades = $ctUnidades;

        return $this;
    }

    /**
     * Get ctUnidades
     *
     * @return float
     */
    public function getCtUnidades()
    {
        return $this->ctUnidades;
    }

    /**
     * Set vrFlete
     *
     * @param float $vrFlete
     *
     * @return TteFactura
     */
    public function setVrFlete($vrFlete)
    {
        $this->vrFlete = $vrFlete;

        return $this;
    }

    /**
     * Get vrFlete
     *
     * @return float
     */
    public function getVrFlete()
    {
        return $this->vrFlete;
    }

    /**
     * Set vrManejo
     *
     * @param float $vrManejo
     *
     * @return TteFactura
     */
    public function setVrManejo($vrManejo)
    {
        $this->vrManejo = $vrManejo;

        return $this;
    }

    /**
     * Get vrManejo
     *
     * @return float
     */
    public function getVrManejo()
    {
        return $this->vrManejo;
    }

    /**
     * Set vrOtros
     *
     * @param float $vrOtros
     *
     * @return TteFactura
     */
    public function setVrOtros($vrOtros)
    {
        $this->vrOtros = $vrOtros;

        return $this;
    }

    /**
     * Get vrOtros
     *
     * @return float
     */
    public function getVrOtros()
    {
        return $this->vrOtros;
    }

    /**
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return TteFactura
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
     * Set ctGuias
     *
     * @param float $ctGuias
     *
     * @return TteFactura
     */
    public function setCtGuias($ctGuias)
    {
        $this->ctGuias = $ctGuias;

        return $this;
    }

    /**
     * Get ctGuias
     *
     * @return float
     */
    public function getCtGuias()
    {
        return $this->ctGuias;
    }

    /**
     * Set ctPlanillas
     *
     * @param float $ctPlanillas
     *
     * @return TteFactura
     */
    public function setCtPlanillas($ctPlanillas)
    {
        $this->ctPlanillas = $ctPlanillas;

        return $this;
    }

    /**
     * Get ctPlanillas
     *
     * @return float
     */
    public function getCtPlanillas()
    {
        return $this->ctPlanillas;
    }

    /**
     * Set ctConceptos
     *
     * @param float $ctConceptos
     *
     * @return TteFactura
     */
    public function setCtConceptos($ctConceptos)
    {
        $this->ctConceptos = $ctConceptos;

        return $this;
    }

    /**
     * Get ctConceptos
     *
     * @return float
     */
    public function getCtConceptos()
    {
        return $this->ctConceptos;
    }

    /**
     * Set estadoImpreso
     *
     * @param boolean $estadoImpreso
     *
     * @return TteFactura
     */
    public function setEstadoImpreso($estadoImpreso)
    {
        $this->estadoImpreso = $estadoImpreso;

        return $this;
    }

    /**
     * Get estadoImpreso
     *
     * @return boolean
     */
    public function getEstadoImpreso()
    {
        return $this->estadoImpreso;
    }

    /**
     * Set estadoAnulada
     *
     * @param boolean $estadoAnulada
     *
     * @return TteFactura
     */
    public function setEstadoAnulada($estadoAnulada)
    {
        $this->estadoAnulada = $estadoAnulada;

        return $this;
    }

    /**
     * Get estadoAnulada
     *
     * @return boolean
     */
    public function getEstadoAnulada()
    {
        return $this->estadoAnulada;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TteFactura
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
     * Add guiasDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel
     *
     * @return TteFactura
     */
    public function addGuiasDetallesRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel)
    {
        $this->guiasDetallesRel[] = $guiasDetallesRel;

        return $this;
    }

    /**
     * Remove guiasDetallesRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel
     */
    public function removeGuiasDetallesRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasDetallesRel)
    {
        $this->guiasDetallesRel->removeElement($guiasDetallesRel);
    }

    /**
     * Get guiasDetallesRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuiasDetallesRel()
    {
        return $this->guiasDetallesRel;
    }
}
