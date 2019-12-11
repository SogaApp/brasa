<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_salud")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadSaludRepository")
 */
class RhuEntidadSalud
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_salud_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadSaludPk;
    
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
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="entidadSaludRel")
     */
    protected $empleadosEntidadSaludRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="entidadSaludRel")
     */
    protected $contratosEntidadSaludRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleadoFamilia", mappedBy="entidadSaludRel")
     */
    protected $empleadosFamiliasEntidadSaludRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidadPago", mappedBy="entidadSaludRel")
     */
    protected $incapacidadesPagosEntidadSaludRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuIncapacidad", mappedBy="entidadSaludRel")
     */
    protected $incapacidadesEntidadSaludRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoSalud", mappedBy="entidadSaludAnteriorRel")
     */
    protected $trasladosSaludEntidadSaludAnteriorRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuTrasladoSalud", mappedBy="entidadSaludNuevaRel")
     */
    protected $trasladosSaludEntidadSaludNuevaRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiContrato", mappedBy="entidadSaludRel")
     */
    protected $afiContratosEntidadSaludRel;    

    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="entidadSaludRel")
     */
    protected $ssoAportesEntidadSaludRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->empleadosFamiliasEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesPagosEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incapacidadesEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosSaludEntidadSaludAnteriorRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trasladosSaludEntidadSaludNuevaRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiContratosEntidadSaludRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadSaludPk
     *
     * @return integer
     */
    public function getCodigoEntidadSaludPk()
    {
        return $this->codigoEntidadSaludPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadSalud
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
     * @return RhuEntidadSalud
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
     * @return RhuEntidadSalud
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
     * @return RhuEntidadSalud
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
     * @return RhuEntidadSalud
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
     * Add empleadosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addEmpleadosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel)
    {
        $this->empleadosEntidadSaludRel[] = $empleadosEntidadSaludRel;

        return $this;
    }

    /**
     * Remove empleadosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel
     */
    public function removeEmpleadosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosEntidadSaludRel)
    {
        $this->empleadosEntidadSaludRel->removeElement($empleadosEntidadSaludRel);
    }

    /**
     * Get empleadosEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosEntidadSaludRel()
    {
        return $this->empleadosEntidadSaludRel;
    }

    /**
     * Add contratosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addContratosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadSaludRel)
    {
        $this->contratosEntidadSaludRel[] = $contratosEntidadSaludRel;

        return $this;
    }

    /**
     * Remove contratosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadSaludRel
     */
    public function removeContratosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosEntidadSaludRel)
    {
        $this->contratosEntidadSaludRel->removeElement($contratosEntidadSaludRel);
    }

    /**
     * Get contratosEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosEntidadSaludRel()
    {
        return $this->contratosEntidadSaludRel;
    }

    /**
     * Add empleadosFamiliasEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addEmpleadosFamiliasEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel)
    {
        $this->empleadosFamiliasEntidadSaludRel[] = $empleadosFamiliasEntidadSaludRel;

        return $this;
    }

    /**
     * Remove empleadosFamiliasEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel
     */
    public function removeEmpleadosFamiliasEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleadoFamilia $empleadosFamiliasEntidadSaludRel)
    {
        $this->empleadosFamiliasEntidadSaludRel->removeElement($empleadosFamiliasEntidadSaludRel);
    }

    /**
     * Get empleadosFamiliasEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosFamiliasEntidadSaludRel()
    {
        return $this->empleadosFamiliasEntidadSaludRel;
    }

    /**
     * Add incapacidadesPagosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addIncapacidadesPagosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel)
    {
        $this->incapacidadesPagosEntidadSaludRel[] = $incapacidadesPagosEntidadSaludRel;

        return $this;
    }

    /**
     * Remove incapacidadesPagosEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel
     */
    public function removeIncapacidadesPagosEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidadPago $incapacidadesPagosEntidadSaludRel)
    {
        $this->incapacidadesPagosEntidadSaludRel->removeElement($incapacidadesPagosEntidadSaludRel);
    }

    /**
     * Get incapacidadesPagosEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesPagosEntidadSaludRel()
    {
        return $this->incapacidadesPagosEntidadSaludRel;
    }

    /**
     * Add incapacidadesEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addIncapacidadesEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel)
    {
        $this->incapacidadesEntidadSaludRel[] = $incapacidadesEntidadSaludRel;

        return $this;
    }

    /**
     * Remove incapacidadesEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel
     */
    public function removeIncapacidadesEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuIncapacidad $incapacidadesEntidadSaludRel)
    {
        $this->incapacidadesEntidadSaludRel->removeElement($incapacidadesEntidadSaludRel);
    }

    /**
     * Get incapacidadesEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncapacidadesEntidadSaludRel()
    {
        return $this->incapacidadesEntidadSaludRel;
    }

    /**
     * Add trasladosSaludEntidadSaludAnteriorRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludAnteriorRel
     *
     * @return RhuEntidadSalud
     */
    public function addTrasladosSaludEntidadSaludAnteriorRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludAnteriorRel)
    {
        $this->trasladosSaludEntidadSaludAnteriorRel[] = $trasladosSaludEntidadSaludAnteriorRel;

        return $this;
    }

    /**
     * Remove trasladosSaludEntidadSaludAnteriorRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludAnteriorRel
     */
    public function removeTrasladosSaludEntidadSaludAnteriorRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludAnteriorRel)
    {
        $this->trasladosSaludEntidadSaludAnteriorRel->removeElement($trasladosSaludEntidadSaludAnteriorRel);
    }

    /**
     * Get trasladosSaludEntidadSaludAnteriorRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosSaludEntidadSaludAnteriorRel()
    {
        return $this->trasladosSaludEntidadSaludAnteriorRel;
    }

    /**
     * Add trasladosSaludEntidadSaludNuevaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludNuevaRel
     *
     * @return RhuEntidadSalud
     */
    public function addTrasladosSaludEntidadSaludNuevaRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludNuevaRel)
    {
        $this->trasladosSaludEntidadSaludNuevaRel[] = $trasladosSaludEntidadSaludNuevaRel;

        return $this;
    }

    /**
     * Remove trasladosSaludEntidadSaludNuevaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludNuevaRel
     */
    public function removeTrasladosSaludEntidadSaludNuevaRel(\Brasa\RecursoHumanoBundle\Entity\RhuTrasladoSalud $trasladosSaludEntidadSaludNuevaRel)
    {
        $this->trasladosSaludEntidadSaludNuevaRel->removeElement($trasladosSaludEntidadSaludNuevaRel);
    }

    /**
     * Get trasladosSaludEntidadSaludNuevaRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrasladosSaludEntidadSaludNuevaRel()
    {
        return $this->trasladosSaludEntidadSaludNuevaRel;
    }

    /**
     * Add afiContratosEntidadSaludRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addAfiContratosEntidadSaludRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadSaludRel)
    {
        $this->afiContratosEntidadSaludRel[] = $afiContratosEntidadSaludRel;

        return $this;
    }

    /**
     * Remove afiContratosEntidadSaludRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadSaludRel
     */
    public function removeAfiContratosEntidadSaludRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosEntidadSaludRel)
    {
        $this->afiContratosEntidadSaludRel->removeElement($afiContratosEntidadSaludRel);
    }

    /**
     * Get afiContratosEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiContratosEntidadSaludRel()
    {
        return $this->afiContratosEntidadSaludRel;
    }

    /**
     * Add ssoAportesEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadSaludRel
     *
     * @return RhuEntidadSalud
     */
    public function addSsoAportesEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadSaludRel)
    {
        $this->ssoAportesEntidadSaludRel[] = $ssoAportesEntidadSaludRel;

        return $this;
    }

    /**
     * Remove ssoAportesEntidadSaludRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadSaludRel
     */
    public function removeSsoAportesEntidadSaludRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadSaludRel)
    {
        $this->ssoAportesEntidadSaludRel->removeElement($ssoAportesEntidadSaludRel);
    }

    /**
     * Get ssoAportesEntidadSaludRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesEntidadSaludRel()
    {
        return $this->ssoAportesEntidadSaludRel;
    }
}
