<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_operacion")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurOperacionRepository")
 */
class TurOperacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_operacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoOperacionPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                       
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;            
    
    /**
     * @ORM\Column(name="codigo_proyecto_fk", type="integer", nullable=true)
     */    
    private $codigoProyectoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurCliente", inversedBy="operacionesClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;                  
    
    /**
     * @ORM\ManyToOne(targetEntity="TurProyecto", inversedBy="operacionesProyectoRel")
     * @ORM\JoinColumn(name="codigo_proyecto_fk", referencedColumnName="codigo_proyecto_pk")
     */
    protected $proyectoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurPuesto", mappedBy="operacionRel")
     */
    protected $puestosOperacionRel;    

    /**
     * Get codigoOperacionPk
     *
     * @return integer
     */
    public function getCodigoOperacionPk()
    {
        return $this->codigoOperacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurOperacion
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
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TurOperacion
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
     * Set clienteRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $clienteRel
     *
     * @return TurOperacion
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
     * Constructor
     */
    public function __construct()
    {
        $this->puestosOperacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoProyectoFk
     *
     * @param integer $codigoProyectoFk
     *
     * @return TurOperacion
     */
    public function setCodigoProyectoFk($codigoProyectoFk)
    {
        $this->codigoProyectoFk = $codigoProyectoFk;

        return $this;
    }

    /**
     * Get codigoProyectoFk
     *
     * @return integer
     */
    public function getCodigoProyectoFk()
    {
        return $this->codigoProyectoFk;
    }

    /**
     * Set proyectoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurProyecto $proyectoRel
     *
     * @return TurOperacion
     */
    public function setProyectoRel(\Brasa\TurnoBundle\Entity\TurProyecto $proyectoRel = null)
    {
        $this->proyectoRel = $proyectoRel;

        return $this;
    }

    /**
     * Get proyectoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurProyecto
     */
    public function getProyectoRel()
    {
        return $this->proyectoRel;
    }

    /**
     * Add puestosOperacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosOperacionRel
     *
     * @return TurOperacion
     */
    public function addPuestosOperacionRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosOperacionRel)
    {
        $this->puestosOperacionRel[] = $puestosOperacionRel;

        return $this;
    }

    /**
     * Remove puestosOperacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurPuesto $puestosOperacionRel
     */
    public function removePuestosOperacionRel(\Brasa\TurnoBundle\Entity\TurPuesto $puestosOperacionRel)
    {
        $this->puestosOperacionRel->removeElement($puestosOperacionRel);
    }

    /**
     * Get puestosOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPuestosOperacionRel()
    {
        return $this->puestosOperacionRel;
    }
}
