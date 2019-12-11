<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_cesantia")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadCesantiaRepository")
 */
class RhuEntidadCesantia
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_cesantia_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadCesantiaPk;
    
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
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadCesantiaRel")
     */
    protected $empleadosEntidadCesantiaRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadCesantiaRel")
     */
    protected $contratosEntidadCesantiaRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoPension", mappedBy="entidadPensionAnteriorRel")
     */
    protected $trasladosPensionesEntidadPensionAnteriorRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoPension", mappedBy="entidadPensionNuevaRel")
     */
    protected $trasladosPensionesEntidadPensionNuevaRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiContrato", mappedBy="entidadPensionRel")
     */
    protected $afiContratosEntidadPensionRel;    

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEntidadCesantiaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosEntidadCesantiaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosPensionesEntidadPensionAnteriorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosPensionesEntidadPensionNuevaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiContratosEntidadPensionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadCesantiaPk
     *
     * @return integer
     */
    public function getCodigoEntidadCesantiaPk()
    {
        return $this->codigoEntidadCesantiaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadCesantia
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
     * @return RhuEntidadCesantia
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
     * @return RhuEntidadCesantia
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
     * @return RhuEntidadCesantia
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
     * @return RhuEntidadCesantia
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
     * Add empleadosEntidadCesantiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCesantiaRel
     *
     * @return RhuEntidadCesantia
     */
    public function addEmpleadosEntidadCesantiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCesantiaRel)
    {
        $this->empleadosEntidadCesantiaRel[] = $empleadosEntidadCesantiaRel;

        return $this;
    }

    /**
     * Remove empleadosEntidadCesantiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCesantiaRel
     */
    public function removeEmpleadosEntidadCesantiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadCesantiaRel)
    {
        $this->empleadosEntidadCesantiaRel->removeElement($empleadosEntidadCesantiaRel);
    }

    /**
     * Get empleadosEntidadCesantiaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEntidadCesantiaRel()
    {
        return $this->empleadosEntidadCesantiaRel;
    }

    /**
     * Add contratosEntidadCesantiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCesantiaRel
     *
     * @return RhuEntidadCesantia
     */
    public function addContratosEntidadCesantiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCesantiaRel)
    {
        $this->contratosEntidadCesantiaRel[] = $contratosEntidadCesantiaRel;

        return $this;
    }

    /**
     * Remove contratosEntidadCesantiaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCesantiaRel
     */
    public function removeContratosEntidadCesantiaRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadCesantiaRel)
    {
        $this->contratosEntidadCesantiaRel->removeElement($contratosEntidadCesantiaRel);
    }

    /**
     * Get contratosEntidadCesantiaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosEntidadCesantiaRel()
    {
        return $this->contratosEntidadCesantiaRel;
    }

    /**
     * Add trasladosPensionesEntidadPensionAnteriorRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionAnteriorRel
     *
     * @return RhuEntidadCesantia
     */
    public function addTrasladosPensionesEntidadPensionAnteriorRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionAnteriorRel)
    {
        $this->trasladosPensionesEntidadPensionAnteriorRel[] = $trasladosPensionesEntidadPensionAnteriorRel;

        return $this;
    }

    /**
     * Remove trasladosPensionesEntidadPensionAnteriorRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionAnteriorRel
     */
    public function removeTrasladosPensionesEntidadPensionAnteriorRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionAnteriorRel)
    {
        $this->trasladosPensionesEntidadPensionAnteriorRel->removeElement($trasladosPensionesEntidadPensionAnteriorRel);
    }

    /**
     * Get trasladosPensionesEntidadPensionAnteriorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosPensionesEntidadPensionAnteriorRel()
    {
        return $this->trasladosPensionesEntidadPensionAnteriorRel;
    }

    /**
     * Add trasladosPensionesEntidadPensionNuevaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionNuevaRel
     *
     * @return RhuEntidadCesantia
     */
    public function addTrasladosPensionesEntidadPensionNuevaRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionNuevaRel)
    {
        $this->trasladosPensionesEntidadPensionNuevaRel[] = $trasladosPensionesEntidadPensionNuevaRel;

        return $this;
    }

    /**
     * Remove trasladosPensionesEntidadPensionNuevaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionNuevaRel
     */
    public function removeTrasladosPensionesEntidadPensionNuevaRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoPension $trasladosPensionesEntidadPensionNuevaRel)
    {
        $this->trasladosPensionesEntidadPensionNuevaRel->removeElement($trasladosPensionesEntidadPensionNuevaRel);
    }

    /**
     * Get trasladosPensionesEntidadPensionNuevaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosPensionesEntidadPensionNuevaRel()
    {
        return $this->trasladosPensionesEntidadPensionNuevaRel;
    }

    /**
     * Add afiContratosEntidadPensionRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadPensionRel
     *
     * @return RhuEntidadCesantia
     */
    public function addAfiContratosEntidadPensionRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadPensionRel)
    {
        $this->afiContratosEntidadPensionRel[] = $afiContratosEntidadPensionRel;

        return $this;
    }

    /**
     * Remove afiContratosEntidadPensionRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadPensionRel
     */
    public function removeAfiContratosEntidadPensionRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadPensionRel)
    {
        $this->afiContratosEntidadPensionRel->removeElement($afiContratosEntidadPensionRel);
    }

    /**
     * Get afiContratosEntidadPensionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiContratosEntidadPensionRel()
    {
        return $this->afiContratosEntidadPensionRel;
    }
}
