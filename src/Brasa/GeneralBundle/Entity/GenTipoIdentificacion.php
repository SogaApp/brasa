<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_tipo_identificacion")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTipoIdentificacionRepository")
 */
class GenTipoIdentificacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_identificacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTipoIdentificacionPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;      

    /**
     * @ORM\Column(name="codigo_interface", type="string", length=10, nullable=true)
     */    
    private $codigoInterface;    
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuEmpleado", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuEmpleadosTipoIdentificacionRel;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuSeleccion", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuSeleccionesTipoIdentificacionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\ContabilidadBundle\Entity\CtbTercero", mappedBy="tipoIdentificacionRel")
     */
    protected $cbtTercerosTipoIdentificacionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiEmpleado", mappedBy="tipoIdentificacionRel")
     */
    protected $afiEmpleadosTipoIdentificacionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuAspirante", mappedBy="tipoIdentificacionRel")
     */
    protected $rhuAspirantesTipoIdentificacionRel;
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="tipoIdentificacionRel")
     */
    protected $turClientesTipoIdentificacionRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rhuEmpleadosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuSeleccionesTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cbtTercerosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiEmpleadosTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuAspirantesTipoIdentificacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoTipoIdentificacionPk
     *
     * @return integer
     */
    public function getCodigoTipoIdentificacionPk()
    {
        return $this->codigoTipoIdentificacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenTipoIdentificacion
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
     * @return GenTipoIdentificacion
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
     * Add rhuEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel)
    {
        $this->rhuEmpleadosTipoIdentificacionRel[] = $rhuEmpleadosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel
     */
    public function removeRhuEmpleadosTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $rhuEmpleadosTipoIdentificacionRel)
    {
        $this->rhuEmpleadosTipoIdentificacionRel->removeElement($rhuEmpleadosTipoIdentificacionRel);
    }

    /**
     * Get rhuEmpleadosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuEmpleadosTipoIdentificacionRel()
    {
        return $this->rhuEmpleadosTipoIdentificacionRel;
    }

    /**
     * Add rhuSeleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel)
    {
        $this->rhuSeleccionesTipoIdentificacionRel[] = $rhuSeleccionesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuSeleccionesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel
     */
    public function removeRhuSeleccionesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $rhuSeleccionesTipoIdentificacionRel)
    {
        $this->rhuSeleccionesTipoIdentificacionRel->removeElement($rhuSeleccionesTipoIdentificacionRel);
    }

    /**
     * Get rhuSeleccionesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuSeleccionesTipoIdentificacionRel()
    {
        return $this->rhuSeleccionesTipoIdentificacionRel;
    }

    /**
     * Add cbtTercerosTipoIdentificacionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addCbtTercerosTipoIdentificacionRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel)
    {
        $this->cbtTercerosTipoIdentificacionRel[] = $cbtTercerosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove cbtTercerosTipoIdentificacionRel
     *
     * @param \Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel
     */
    public function removeCbtTercerosTipoIdentificacionRel(\Brasa\ContabilidadBundle\Entity\CtbTercero $cbtTercerosTipoIdentificacionRel)
    {
        $this->cbtTercerosTipoIdentificacionRel->removeElement($cbtTercerosTipoIdentificacionRel);
    }

    /**
     * Get cbtTercerosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCbtTercerosTipoIdentificacionRel()
    {
        return $this->cbtTercerosTipoIdentificacionRel;
    }

    /**
     * Add afiEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addAfiEmpleadosTipoIdentificacionRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosTipoIdentificacionRel)
    {
        $this->afiEmpleadosTipoIdentificacionRel[] = $afiEmpleadosTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove afiEmpleadosTipoIdentificacionRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosTipoIdentificacionRel
     */
    public function removeAfiEmpleadosTipoIdentificacionRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $afiEmpleadosTipoIdentificacionRel)
    {
        $this->afiEmpleadosTipoIdentificacionRel->removeElement($afiEmpleadosTipoIdentificacionRel);
    }

    /**
     * Get afiEmpleadosTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiEmpleadosTipoIdentificacionRel()
    {
        return $this->afiEmpleadosTipoIdentificacionRel;
    }

    /**
     * Add rhuAspirantesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addRhuAspirantesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel)
    {
        $this->rhuAspirantesTipoIdentificacionRel[] = $rhuAspirantesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove rhuAspirantesTipoIdentificacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel
     */
    public function removeRhuAspirantesTipoIdentificacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $rhuAspirantesTipoIdentificacionRel)
    {
        $this->rhuAspirantesTipoIdentificacionRel->removeElement($rhuAspirantesTipoIdentificacionRel);
    }

    /**
     * Get rhuAspirantesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuAspirantesTipoIdentificacionRel()
    {
        return $this->rhuAspirantesTipoIdentificacionRel;
    }

    /**
     * Add turClientesTipoIdentificacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel
     *
     * @return GenTipoIdentificacion
     */
    public function addTurClientesTipoIdentificacionRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel)
    {
        $this->turClientesTipoIdentificacionRel[] = $turClientesTipoIdentificacionRel;

        return $this;
    }

    /**
     * Remove turClientesTipoIdentificacionRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel
     */
    public function removeTurClientesTipoIdentificacionRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesTipoIdentificacionRel)
    {
        $this->turClientesTipoIdentificacionRel->removeElement($turClientesTipoIdentificacionRel);
    }

    /**
     * Get turClientesTipoIdentificacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesTipoIdentificacionRel()
    {
        return $this->turClientesTipoIdentificacionRel;
    }
}
