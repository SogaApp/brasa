<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_caja")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadCajaRepository")
 */
class RhuEntidadCaja
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_caja_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadCajaPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
    
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
     * @ORM\Column(name="codigo_interface", type="string", length=20, nullable=true)
     */    
    private $codigoInterface;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadCajaRel")
     */
    protected $empleadosEntidadCajaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadCajaRel")
     */
    protected $contratosEntidadCajaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoFamilia", mappedBy="entidadCajaRel")
     */
    protected $empleadosFamiliasEntidadCajaRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiContrato", mappedBy="entidadCajaRel")
     */
    protected $afiContratosEntidadCajaRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="entidadCajaRel")
     */
    protected $ssoAportesEntidadCajaRel;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEntidadCajaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosEntidadCajaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosFamiliasEntidadCajaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiContratosEntidadCajaRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadCajaPk
     *
     * @return integer
     */
    public function getCodigoEntidadCajaPk()
    {
        return $this->codigoEntidadCajaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadCaja
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
     * Set nit
     *
     * @param string $nit
     *
     * @return RhuEntidadCaja
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
     * @return RhuEntidadCaja
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
     * @return RhuEntidadCaja
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
     * Set codigoInterface
     *
     * @param string $codigoInterface
     *
     * @return RhuEntidadCaja
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
     * Add empleadosEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel
     *
     * @return RhuEntidadCaja
     */
    public function addEmpleadosEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel)
    {
        $this->empleadosEntidadCajaRel[] = $empleadosEntidadCajaRel;

        return $this;
    }

    /**
     * Remove empleadosEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel
     */
    public function removeEmpleadosEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCajaRel)
    {
        $this->empleadosEntidadCajaRel->removeElement($empleadosEntidadCajaRel);
    }

    /**
     * Get empleadosEntidadCajaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEntidadCajaRel()
    {
        return $this->empleadosEntidadCajaRel;
    }

    /**
     * Add contratosEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCajaRel
     *
     * @return RhuEntidadCaja
     */
    public function addContratosEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCajaRel)
    {
        $this->contratosEntidadCajaRel[] = $contratosEntidadCajaRel;

        return $this;
    }

    /**
     * Remove contratosEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCajaRel
     */
    public function removeContratosEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCajaRel)
    {
        $this->contratosEntidadCajaRel->removeElement($contratosEntidadCajaRel);
    }

    /**
     * Get contratosEntidadCajaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosEntidadCajaRel()
    {
        return $this->contratosEntidadCajaRel;
    }

    /**
     * Add empleadosFamiliasEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadCajaRel
     *
     * @return RhuEntidadCaja
     */
    public function addEmpleadosFamiliasEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadCajaRel)
    {
        $this->empleadosFamiliasEntidadCajaRel[] = $empleadosFamiliasEntidadCajaRel;

        return $this;
    }

    /**
     * Remove empleadosFamiliasEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadCajaRel
     */
    public function removeEmpleadosFamiliasEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadCajaRel)
    {
        $this->empleadosFamiliasEntidadCajaRel->removeElement($empleadosFamiliasEntidadCajaRel);
    }

    /**
     * Get empleadosFamiliasEntidadCajaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosFamiliasEntidadCajaRel()
    {
        return $this->empleadosFamiliasEntidadCajaRel;
    }

    /**
     * Add afiContratosEntidadCajaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadCajaRel
     *
     * @return RhuEntidadCaja
     */
    public function addAfiContratosEntidadCajaRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadCajaRel)
    {
        $this->afiContratosEntidadCajaRel[] = $afiContratosEntidadCajaRel;

        return $this;
    }

    /**
     * Remove afiContratosEntidadCajaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadCajaRel
     */
    public function removeAfiContratosEntidadCajaRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadCajaRel)
    {
        $this->afiContratosEntidadCajaRel->removeElement($afiContratosEntidadCajaRel);
    }

    /**
     * Get afiContratosEntidadCajaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiContratosEntidadCajaRel()
    {
        return $this->afiContratosEntidadCajaRel;
    }

    /**
     * Add ssoAportesEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadCajaRel
     *
     * @return RhuEntidadCaja
     */
    public function addSsoAportesEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadCajaRel)
    {
        $this->ssoAportesEntidadCajaRel[] = $ssoAportesEntidadCajaRel;

        return $this;
    }

    /**
     * Remove ssoAportesEntidadCajaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadCajaRel
     */
    public function removeSsoAportesEntidadCajaRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadCajaRel)
    {
        $this->ssoAportesEntidadCajaRel->removeElement($ssoAportesEntidadCajaRel);
    }

    /**
     * Get ssoAportesEntidadCajaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesEntidadCajaRel()
    {
        return $this->ssoAportesEntidadCajaRel;
    }
}
