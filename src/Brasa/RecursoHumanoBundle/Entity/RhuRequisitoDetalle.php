<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_requisito_detalle")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRequisitoDetalleRepository")
 */
class RhuRequisitoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_requisito_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRequisitoDetallePk;                    
    
    /**
     * @ORM\Column(name="codigo_requisito_fk", type="integer", nullable=true)
     */    
    private $codigoRequisitoFk;   
    
    /**
     * @ORM\Column(name="codigo_requisito_concepto_fk", type="integer", nullable=true)
     */    
    private $codigoRequisitoConceptoFk;    
    
    /**     
     * @ORM\Column(name="estado_pendiente", type="boolean")
     */    
    private $estadoPendiente = 1;    

    /**     
     * @ORM\Column(name="estado_entregado", type="boolean")
     */    
    private $estadoEntregado = 0;        

    /**     
     * @ORM\Column(name="estado_no_aplica", type="boolean")
     */    
    private $estadoNoAplica = 0;            
    
    /**
     * @ORM\Column(name="tipo", type="string", length=20, nullable=true)
     */    
    private $tipo;    
    
    /**     
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;     

    /**     
     * @ORM\Column(name="cantidad_pendiente", type="integer")
     */    
    private $cantidadPendiente = 0;         
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuRequisito", inversedBy="requisitosDetallesRequisitoRel")
     * @ORM\JoinColumn(name="codigo_requisito_fk", referencedColumnName="codigo_requisito_pk")
     */
    protected $requisitoRel;

    /**
     * @ORM\ManyToOne(targetEntity="RhuRequisitoConcepto", inversedBy="requisitosDetallesRequisitoConceptoRel")
     * @ORM\JoinColumn(name="codigo_requisito_concepto_fk", referencedColumnName="codigo_requisito_concepto_pk")
     */
    protected $requisitoConceptoRel;    
    

    /**
     * Get codigoRequisitoDetallePk
     *
     * @return integer
     */
    public function getCodigoRequisitoDetallePk()
    {
        return $this->codigoRequisitoDetallePk;
    }

    /**
     * Set codigoRequisitoFk
     *
     * @param integer $codigoRequisitoFk
     *
     * @return RhuRequisitoDetalle
     */
    public function setCodigoRequisitoFk($codigoRequisitoFk)
    {
        $this->codigoRequisitoFk = $codigoRequisitoFk;

        return $this;
    }

    /**
     * Get codigoRequisitoFk
     *
     * @return integer
     */
    public function getCodigoRequisitoFk()
    {
        return $this->codigoRequisitoFk;
    }

    /**
     * Set codigoRequisitoConceptoFk
     *
     * @param integer $codigoRequisitoConceptoFk
     *
     * @return RhuRequisitoDetalle
     */
    public function setCodigoRequisitoConceptoFk($codigoRequisitoConceptoFk)
    {
        $this->codigoRequisitoConceptoFk = $codigoRequisitoConceptoFk;

        return $this;
    }

    /**
     * Get codigoRequisitoConceptoFk
     *
     * @return integer
     */
    public function getCodigoRequisitoConceptoFk()
    {
        return $this->codigoRequisitoConceptoFk;
    }

    /**
     * Set estadoPendiente
     *
     * @param boolean $estadoPendiente
     *
     * @return RhuRequisitoDetalle
     */
    public function setEstadoPendiente($estadoPendiente)
    {
        $this->estadoPendiente = $estadoPendiente;

        return $this;
    }

    /**
     * Get estadoPendiente
     *
     * @return boolean
     */
    public function getEstadoPendiente()
    {
        return $this->estadoPendiente;
    }

    /**
     * Set estadoEntregado
     *
     * @param boolean $estadoEntregado
     *
     * @return RhuRequisitoDetalle
     */
    public function setEstadoEntregado($estadoEntregado)
    {
        $this->estadoEntregado = $estadoEntregado;

        return $this;
    }

    /**
     * Get estadoEntregado
     *
     * @return boolean
     */
    public function getEstadoEntregado()
    {
        return $this->estadoEntregado;
    }

    /**
     * Set estadoNoAplica
     *
     * @param boolean $estadoNoAplica
     *
     * @return RhuRequisitoDetalle
     */
    public function setEstadoNoAplica($estadoNoAplica)
    {
        $this->estadoNoAplica = $estadoNoAplica;

        return $this;
    }

    /**
     * Get estadoNoAplica
     *
     * @return boolean
     */
    public function getEstadoNoAplica()
    {
        return $this->estadoNoAplica;
    }

    /**
     * Set requisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitoRel
     *
     * @return RhuRequisitoDetalle
     */
    public function setRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisito $requisitoRel = null)
    {
        $this->requisitoRel = $requisitoRel;

        return $this;
    }

    /**
     * Get requisitoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuRequisito
     */
    public function getRequisitoRel()
    {
        return $this->requisitoRel;
    }

    /**
     * Set requisitoConceptoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto $requisitoConceptoRel
     *
     * @return RhuRequisitoDetalle
     */
    public function setRequisitoConceptoRel(\Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto $requisitoConceptoRel = null)
    {
        $this->requisitoConceptoRel = $requisitoConceptoRel;

        return $this;
    }

    /**
     * Get requisitoConceptoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuRequisitoConcepto
     */
    public function getRequisitoConceptoRel()
    {
        return $this->requisitoConceptoRel;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return RhuRequisitoDetalle
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return RhuRequisitoDetalle
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set cantidadPendiente
     *
     * @param integer $cantidadPendiente
     *
     * @return RhuRequisitoDetalle
     */
    public function setCantidadPendiente($cantidadPendiente)
    {
        $this->cantidadPendiente = $cantidadPendiente;

        return $this;
    }

    /**
     * Get cantidadPendiente
     *
     * @return integer
     */
    public function getCantidadPendiente()
    {
        return $this->cantidadPendiente;
    }
}
