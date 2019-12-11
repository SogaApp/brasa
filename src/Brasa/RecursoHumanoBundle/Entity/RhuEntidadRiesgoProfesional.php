<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_entidad_riesgo_profesional")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuEntidadRiesgoProfesionalRepository")
 */
class RhuEntidadRiesgoProfesional
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_riesgo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadRiesgoPk;

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
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */
    private $codigoInterface;

    /**
     * @ORM\OneToMany(targetEntity="RhuConfiguracion", mappedBy="entidadRiesgoProfesionalRel")
     */
    protected $configuracionEntidadRiesgoProfesionalRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuAccidenteTrabajo", mappedBy="entidadRiesgoProfesionalRel")
     */
    protected $accidentesTrabajoEntidadRiesgoRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuSsoAporte", mappedBy="entidadRiesgoProfesionalRel")
     */
    protected $ssoAportesEntidadRiesgoProfesionalRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiCliente" , mappedBy="entidadRiesgoRel")
     */
    protected $afiClientesEntidadRiesgoRel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configuracionEntidadRiesgoProfesionalRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accidentesTrabajoEntidadRiesgoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoEntidadRiesgoPk
     *
     * @return integer
     */
    public function getCodigoEntidadRiesgoPk()
    {
        return $this->codigoEntidadRiesgoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuEntidadRiesgoProfesional
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
     * @return RhuEntidadRiesgoProfesional
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
     * @return RhuEntidadRiesgoProfesional
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
     * @return RhuEntidadRiesgoProfesional
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
     * @return RhuEntidadRiesgoProfesional
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
     * Add configuracionEntidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel
     *
     * @return RhuEntidadRiesgoProfesional
     */
    public function addConfiguracionEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel)
    {
        $this->configuracionEntidadRiesgoProfesionalRel[] = $configuracionEntidadRiesgoProfesionalRel;

        return $this;
    }

    /**
     * Remove configuracionEntidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel
     */
    public function removeConfiguracionEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion $configuracionEntidadRiesgoProfesionalRel)
    {
        $this->configuracionEntidadRiesgoProfesionalRel->removeElement($configuracionEntidadRiesgoProfesionalRel);
    }

    /**
     * Get configuracionEntidadRiesgoProfesionalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfiguracionEntidadRiesgoProfesionalRel()
    {
        return $this->configuracionEntidadRiesgoProfesionalRel;
    }

    /**
     * Add accidentesTrabajoEntidadRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEntidadRiesgoRel
     *
     * @return RhuEntidadRiesgoProfesional
     */
    public function addAccidentesTrabajoEntidadRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEntidadRiesgoRel)
    {
        $this->accidentesTrabajoEntidadRiesgoRel[] = $accidentesTrabajoEntidadRiesgoRel;

        return $this;
    }

    /**
     * Remove accidentesTrabajoEntidadRiesgoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEntidadRiesgoRel
     */
    public function removeAccidentesTrabajoEntidadRiesgoRel(\Brasa\RecursoHumanoBundle\Entity\RhuAccidenteTrabajo $accidentesTrabajoEntidadRiesgoRel)
    {
        $this->accidentesTrabajoEntidadRiesgoRel->removeElement($accidentesTrabajoEntidadRiesgoRel);
    }

    /**
     * Get accidentesTrabajoEntidadRiesgoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccidentesTrabajoEntidadRiesgoRel()
    {
        return $this->accidentesTrabajoEntidadRiesgoRel;
    }

    /**
     * Add ssoAportesEntidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadRiesgoProfesionalRel
     *
     * @return RhuEntidadRiesgoProfesional
     */
    public function addSsoAportesEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadRiesgoProfesionalRel)
    {
        $this->ssoAportesEntidadRiesgoProfesionalRel[] = $ssoAportesEntidadRiesgoProfesionalRel;

        return $this;
    }

    /**
     * Remove ssoAportesEntidadRiesgoProfesionalRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadRiesgoProfesionalRel
     */
    public function removeSsoAportesEntidadRiesgoProfesionalRel(\Brasa\RecursoHumanoBundle\Entity\RhuSsoAporte $ssoAportesEntidadRiesgoProfesionalRel)
    {
        $this->ssoAportesEntidadRiesgoProfesionalRel->removeElement($ssoAportesEntidadRiesgoProfesionalRel);
    }

    /**
     * Get ssoAportesEntidadRiesgoProfesionalRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSsoAportesEntidadRiesgoProfesionalRel()
    {
        return $this->ssoAportesEntidadRiesgoProfesionalRel;
    }

    /**
     * Add afiClientesEntidadRiesgoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesEntidadRiesgoRel
     *
     * @return RhuEntidadRiesgoProfesional
     */
    public function addAfiClientesEntidadRiesgoRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesEntidadRiesgoRel)
    {
        $this->afiClientesEntidadRiesgoRel[] = $afiClientesEntidadRiesgoRel;

        return $this;
    }

    /**
     * Remove afiClientesEntidadRiesgoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesEntidadRiesgoRel
     */
    public function removeAfiClientesEntidadRiesgoRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesEntidadRiesgoRel)
    {
        $this->afiClientesEntidadRiesgoRel->removeElement($afiClientesEntidadRiesgoRel);
    }

    /**
     * Get afiClientesEntidadRiesgoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiClientesEntidadRiesgoRel()
    {
        return $this->afiClientesEntidadRiesgoRel;
    }
}
