<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_puesto_dotacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurPuestoDotacionRepository")
 */
class TurPuestoDotacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_puesto_dotacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuestoDotacionPk;        

    /**
     * @ORM\Column(name="codigo_puesto_fk", type="integer", nullable=true)
     */    
    private $codigoPuestoFk;    

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;
    
    /**
     * @ORM\Column(name="codigo_elemento_dotacion_fk", type="integer", nullable=true)
     */    
    private $codigoElementoDotacionFk;    
    
    /**
     * @ORM\Column(name="cantidad", type="integer")
     */    
    private $cantidad = 0;    
    
    /**
     * @ORM\Column(name="costo", type="float")
     */    
    private $costo = 0;    
      
    /**
     * @ORM\Column(name="total", type="float")
     */    
    private $total = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurPuesto", inversedBy="puestosDotacionesPuestoRel")
     * @ORM\JoinColumn(name="codigo_puesto_fk", referencedColumnName="codigo_puesto_pk")
     */
    protected $puestoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="puestosDotacionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurElementoDotacion", inversedBy="puestosDotacionesElementoDotacionRel")
     * @ORM\JoinColumn(name="codigo_elemento_dotacion_fk", referencedColumnName="codigo_elemento_dotacion_pk")
     */
    protected $elementoDotacionRel;

    /**
     * Get codigoPuestoDotacionPk
     *
     * @return integer
     */
    public function getCodigoPuestoDotacionPk()
    {
        return $this->codigoPuestoDotacionPk;
    }

    /**
     * Set codigoPuestoFk
     *
     * @param integer $codigoPuestoFk
     *
     * @return TurPuestoDotacion
     */
    public function setCodigoPuestoFk($codigoPuestoFk)
    {
        $this->codigoPuestoFk = $codigoPuestoFk;

        return $this;
    }

    /**
     * Get codigoPuestoFk
     *
     * @return integer
     */
    public function getCodigoPuestoFk()
    {
        return $this->codigoPuestoFk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurPuestoDotacion
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
     * Set cantidad
     *
     * @param integer $cantidad
     *
     * @return TurPuestoDotacion
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
     * Set puestoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestoRel
     *
     * @return TurPuestoDotacion
     */
    public function setPuestoRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestoRel = null)
    {
        $this->puestoRel = $puestoRel;

        return $this;
    }

    /**
     * Get puestoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurPuesto
     */
    public function getPuestoRel()
    {
        return $this->puestoRel;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurPuestoDotacion
     */
    public function setClienteRel(\Brasa\TurnoBundle\Entity\TurCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set codigoElementoDotacionFk
     *
     * @param integer $codigoElementoDotacionFk
     *
     * @return TurPuestoDotacion
     */
    public function setCodigoElementoDotacionFk($codigoElementoDotacionFk)
    {
        $this->codigoElementoDotacionFk = $codigoElementoDotacionFk;

        return $this;
    }

    /**
     * Get codigoElementoDotacionFk
     *
     * @return integer
     */
    public function getCodigoElementoDotacionFk()
    {
        return $this->codigoElementoDotacionFk;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return TurPuestoDotacion
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
     * Set elementoDotacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurElementoDotacion $elementoDotacionRel
     *
     * @return TurPuestoDotacion
     */
    public function setElementoDotacionRel(\Brasa\TurnoBundle\Entity\TurElementoDotacion $elementoDotacionRel = null)
    {
        $this->elementoDotacionRel = $elementoDotacionRel;

        return $this;
    }

    /**
     * Get elementoDotacionRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurElementoDotacion
     */
    public function getElementoDotacionRel()
    {
        return $this->elementoDotacionRel;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return TurPuestoDotacion
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
}
