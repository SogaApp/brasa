<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_servicio")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiServicioRepository")
 */
class AfiServicio
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioPk;                 

    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;            
    
    /**
     * @ORM\Column(name="curso", type="float")
     */
    private $curso = 0;             
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;    
    
    /**
     * @ORM\Column(name="pendiente", type="float")
     */
    private $pendiente = 0;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCliente", inversedBy="serviciosClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;    



    /**
     * Get codigoServicioPk
     *
     * @return integer
     */
    public function getCodigoServicioPk()
    {
        return $this->codigoServicioPk;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return AfiServicio
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
     * Set curso
     *
     * @param float $curso
     *
     * @return AfiServicio
     */
    public function setCurso($curso)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return float
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiServicio
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
     * Set pendiente
     *
     * @param float $pendiente
     *
     * @return AfiServicio
     */
    public function setPendiente($pendiente)
    {
        $this->pendiente = $pendiente;

        return $this;
    }

    /**
     * Get pendiente
     *
     * @return float
     */
    public function getPendiente()
    {
        return $this->pendiente;
    }

    /**
     * Set clienteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel
     *
     * @return AfiServicio
     */
    public function setClienteRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }
}
