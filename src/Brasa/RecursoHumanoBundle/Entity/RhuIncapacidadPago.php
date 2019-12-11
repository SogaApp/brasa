<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_incapacidad_pago")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuIncapacidadPagoRepository")
 */
class RhuIncapacidadPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_incapacidad_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoIncapacidadPagoPk;                    
         
    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="codigo_entidad_salud_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadSaludFk;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="vr_total", type="float")
     */
    private $vrTotal = 0;
    
    /**     
     * @ORM\Column(name="estado_autorizado", type="boolean")
     */    
    private $estadoAutorizado = 0;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuEntidadSalud", inversedBy="incapacidadesPagosEntidadSaludRel")
     * @ORM\JoinColumn(name="codigo_entidad_salud_fk", referencedColumnName="codigo_entidad_salud_pk")
     */
    protected $entidadSaludRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidadPagoDetalle", mappedBy="incapacidadPagoRel")
     */
    protected $incapacidadesPagosDetallesIncapacidadPagoRel;
    
    
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->incapacidadesPagosDetallesIncapacidadPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoIncapacidadPagoPk
     *
     * @return integer
     */
    public function getCodigoIncapacidadPagoPk()
    {
        return $this->codigoIncapacidadPagoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuIncapacidadPago
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
     * Set codigoEntidadSaludFk
     *
     * @param integer $codigoEntidadSaludFk
     *
     * @return RhuIncapacidadPago
     */
    public function setCodigoEntidadSaludFk($codigoEntidadSaludFk)
    {
        $this->codigoEntidadSaludFk = $codigoEntidadSaludFk;

        return $this;
    }

    /**
     * Get codigoEntidadSaludFk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludFk()
    {
        return $this->codigoEntidadSaludFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuIncapacidadPago
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
     * Set vrTotal
     *
     * @param float $vrTotal
     *
     * @return RhuIncapacidadPago
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
     * Set estadoAutorizado
     *
     * @param boolean $estadoAutorizado
     *
     * @return RhuIncapacidadPago
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
     * Set entidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel
     *
     * @return RhuIncapacidadPago
     */
    public function setEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud $entidadSaludRel = null)
    {
        $this->entidadSaludRel = $entidadSaludRel;

        return $this;
    }

    /**
     * Get entidadSaludRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuEntidadSalud
     */
    public function getEntidadSaludRel()
    {
        return $this->entidadSaludRel;
    }

    /**
     * Add incapacidadesPagosDetallesIncapacidadPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesPagosDetallesIncapacidadPagoRel
     *
     * @return RhuIncapacidadPago
     */
    public function addIncapacidadesPagosDetallesIncapacidadPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesPagosDetallesIncapacidadPagoRel)
    {
        $this->incapacidadesPagosDetallesIncapacidadPagoRel[] = $incapacidadesPagosDetallesIncapacidadPagoRel;

        return $this;
    }

    /**
     * Remove incapacidadesPagosDetallesIncapacidadPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesPagosDetallesIncapacidadPagoRel
     */
    public function removeIncapacidadesPagosDetallesIncapacidadPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPagoDetalle $incapacidadesPagosDetallesIncapacidadPagoRel)
    {
        $this->incapacidadesPagosDetallesIncapacidadPagoRel->removeElement($incapacidadesPagosDetallesIncapacidadPagoRel);
    }

    /**
     * Get incapacidadesPagosDetallesIncapacidadPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesPagosDetallesIncapacidadPagoRel()
    {
        return $this->incapacidadesPagosDetallesIncapacidadPagoRel;
    }
}
