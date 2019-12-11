<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_banco")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuBancoRepository")
 */
class RhuBanco
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_banco_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoBancoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="convenio_nomina", type="boolean")
     */    
    private $convenioNomina = 0;
    
    /**
     * @ORM\Column(name="numero_digitos", type="integer", nullable=true)
     */    
    private $numeroDigitos;
    
    /**
     * @ORM\Column(name="codigo_general", type="string", length=20, nullable=true)
     */    
    private $codigoGeneral;
    
    /**
     * @ORM\Column(name="nit", type="string", length=10, nullable=true)
     */    
    private $nit;    
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=15, nullable=true)
     */    
    private $telefono;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="bancoRel")
     */
    protected $empleadosBancoRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoBancoDetalle", mappedBy="bancoRel")
     */
    protected $pagosBancosDetallesBancoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosBancoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoBancoPk
     *
     * @return integer
     */
    public function getCodigoBancoPk()
    {
        return $this->codigoBancoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuBanco
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
     * Set convenioNomina
     *
     * @param boolean $convenioNomina
     *
     * @return RhuBanco
     */
    public function setConvenioNomina($convenioNomina)
    {
        $this->convenioNomina = $convenioNomina;

        return $this;
    }

    /**
     * Get convenioNomina
     *
     * @return boolean
     */
    public function getConvenioNomina()
    {
        return $this->convenioNomina;
    }

    /**
     * Set numeroDigitos
     *
     * @param integer $numeroDigitos
     *
     * @return RhuBanco
     */
    public function setNumeroDigitos($numeroDigitos)
    {
        $this->numeroDigitos = $numeroDigitos;

        return $this;
    }

    /**
     * Get numeroDigitos
     *
     * @return integer
     */
    public function getNumeroDigitos()
    {
        return $this->numeroDigitos;
    }

    /**
     * Set codigoGeneral
     *
     * @param string $codigoGeneral
     *
     * @return RhuBanco
     */
    public function setCodigoGeneral($codigoGeneral)
    {
        $this->codigoGeneral = $codigoGeneral;

        return $this;
    }

    /**
     * Get codigoGeneral
     *
     * @return string
     */
    public function getCodigoGeneral()
    {
        return $this->codigoGeneral;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuBanco
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
     * Set direccion
     *
     * @param string $direccion
     *
     * @return RhuBanco
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return RhuBanco
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Add empleadosBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel
     *
     * @return RhuBanco
     */
    public function addEmpleadosBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel)
    {
        $this->empleadosBancoRel[] = $empleadosBancoRel;

        return $this;
    }

    /**
     * Remove empleadosBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel
     */
    public function removeEmpleadosBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosBancoRel)
    {
        $this->empleadosBancoRel->removeElement($empleadosBancoRel);
    }

    /**
     * Get empleadosBancoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosBancoRel()
    {
        return $this->empleadosBancoRel;
    }

    /**
     * Add pagosBancosDetallesBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesBancoRel
     *
     * @return RhuBanco
     */
    public function addPagosBancosDetallesBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesBancoRel)
    {
        $this->pagosBancosDetallesBancoRel[] = $pagosBancosDetallesBancoRel;

        return $this;
    }

    /**
     * Remove pagosBancosDetallesBancoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesBancoRel
     */
    public function removePagosBancosDetallesBancoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBancoDetalle $pagosBancosDetallesBancoRel)
    {
        $this->pagosBancosDetallesBancoRel->removeElement($pagosBancosDetallesBancoRel);
    }

    /**
     * Get pagosBancosDetallesBancoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosBancosDetallesBancoRel()
    {
        return $this->pagosBancosDetallesBancoRel;
    }
}
