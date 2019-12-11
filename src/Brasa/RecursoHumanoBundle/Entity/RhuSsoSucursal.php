<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_sucursal")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoSucursalRepository")
 */
class RhuSsoSucursal
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sucursal_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSucursalPk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre; 

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */    
    private $codigoInterface;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoDetalle", mappedBy="ssoSucursalRel")
     */
    protected $ssoPeriodosDetallesSsoSucursalRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuSsoPeriodoEmpleado", mappedBy="ssoSucursalRel")
     */
    protected $ssoPeriodosEmpleadosSsoSucursalRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="ssoSucursalRel")
     */
    protected $ssoAportesSsoSucursalRel;      
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCentroCosto", mappedBy="sucursalRel")
     */
    protected $centrosCostosSucursalRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ssoPeriodosDetallesSsoSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ssoPeriodosEmpleadosSsoSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->centrosCostosSucursalRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSucursalPk
     *
     * @return integer
     */
    public function getCodigoSucursalPk()
    {
        return $this->codigoSucursalPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSsoSucursal
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuSsoSucursal
     */
    public function setCodigoInterface($codigoInterface)
    {
        $this->codigoInterface = $codigoInterface;

        return $this;
    }

    /**
     * Get codigoInterface
     *
     * @return string
     */
    public function getCodigoInterface()
    {
        return $this->codigoInterface;
    }

    /**
     * Add ssoPeriodosDetallesSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel
     *
     * @return RhuSsoSucursal
     */
    public function addSsoPeriodosDetallesSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel)
    {
        $this->ssoPeriodosDetallesSsoSucursalRel[] = $ssoPeriodosDetallesSsoSucursalRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosDetallesSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel
     */
    public function removeSsoPeriodosDetallesSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoDetalle $ssoPeriodosDetallesSsoSucursalRel)
    {
        $this->ssoPeriodosDetallesSsoSucursalRel->removeElement($ssoPeriodosDetallesSsoSucursalRel);
    }

    /**
     * Get ssoPeriodosDetallesSsoSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosDetallesSsoSucursalRel()
    {
        return $this->ssoPeriodosDetallesSsoSucursalRel;
    }

    /**
     * Add ssoPeriodosEmpleadosSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoSucursalRel
     *
     * @return RhuSsoSucursal
     */
    public function addSsoPeriodosEmpleadosSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoSucursalRel)
    {
        $this->ssoPeriodosEmpleadosSsoSucursalRel[] = $ssoPeriodosEmpleadosSsoSucursalRel;

        return $this;
    }

    /**
     * Remove ssoPeriodosEmpleadosSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoSucursalRel
     */
    public function removeSsoPeriodosEmpleadosSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoPeriodoEmpleado $ssoPeriodosEmpleadosSsoSucursalRel)
    {
        $this->ssoPeriodosEmpleadosSsoSucursalRel->removeElement($ssoPeriodosEmpleadosSsoSucursalRel);
    }

    /**
     * Get ssoPeriodosEmpleadosSsoSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoPeriodosEmpleadosSsoSucursalRel()
    {
        return $this->ssoPeriodosEmpleadosSsoSucursalRel;
    }

    /**
     * Add centrosCostosSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosSucursalRel
     *
     * @return RhuSsoSucursal
     */
    public function addCentrosCostosSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosSucursalRel)
    {
        $this->centrosCostosSucursalRel[] = $centrosCostosSucursalRel;

        return $this;
    }

    /**
     * Remove centrosCostosSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosSucursalRel
     */
    public function removeCentrosCostosSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centrosCostosSucursalRel)
    {
        $this->centrosCostosSucursalRel->removeElement($centrosCostosSucursalRel);
    }

    /**
     * Get centrosCostosSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentrosCostosSucursalRel()
    {
        return $this->centrosCostosSucursalRel;
    }

    /**
     * Add ssoAportesSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoSucursalRel
     *
     * @return RhuSsoSucursal
     */
    public function addSsoAportesSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoSucursalRel)
    {
        $this->ssoAportesSsoSucursalRel[] = $ssoAportesSsoSucursalRel;

        return $this;
    }

    /**
     * Remove ssoAportesSsoSucursalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoSucursalRel
     */
    public function removeSsoAportesSsoSucursalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesSsoSucursalRel)
    {
        $this->ssoAportesSsoSucursalRel->removeElement($ssoAportesSsoSucursalRel);
    }

    /**
     * Get ssoAportesSsoSucursalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesSsoSucursalRel()
    {
        return $this->ssoAportesSsoSucursalRel;
    }
}
