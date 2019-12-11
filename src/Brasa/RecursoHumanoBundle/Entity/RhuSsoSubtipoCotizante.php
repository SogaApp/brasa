<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_subtipo_cotizante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoSubtipoCotizanteRepository")
 */
class RhuSsoSubtipoCotizante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_subtipo_cotizante_pk", type="integer")
     */
    private $codigoSubtipoCotizantePk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="ssoSubtipoCotizanteRel")
     */
    protected $empleadosSsoSubtipoCotizanteRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="ssoSubtipoCotizanteRel")
     */
    protected $contratosSsoSubtipoCotizanteRel;     

    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiContrato", mappedBy="ssoSubtipoCotizanteRel")
     */
    protected $afiContratosSsoSubtipoCotizanteRel;         

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosSsoSubtipoCotizanteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosSsoSubtipoCotizanteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiContratosSsoSubtipoCotizanteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoSubtipoCotizantePk
     *
     * @param integer $codigoSubtipoCotizantePk
     *
     * @return RhuSsoSubtipoCotizante
     */
    public function setCodigoSubtipoCotizantePk($codigoSubtipoCotizantePk)
    {
        $this->codigoSubtipoCotizantePk = $codigoSubtipoCotizantePk;

        return $this;
    }

    /**
     * Get codigoSubtipoCotizantePk
     *
     * @return integer
     */
    public function getCodigoSubtipoCotizantePk()
    {
        return $this->codigoSubtipoCotizantePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSsoSubtipoCotizante
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
     * Add empleadosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel
     *
     * @return RhuSsoSubtipoCotizante
     */
    public function addEmpleadosSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel)
    {
        $this->empleadosSsoSubtipoCotizanteRel[] = $empleadosSsoSubtipoCotizanteRel;

        return $this;
    }

    /**
     * Remove empleadosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel
     */
    public function removeEmpleadosSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoSubtipoCotizanteRel)
    {
        $this->empleadosSsoSubtipoCotizanteRel->removeElement($empleadosSsoSubtipoCotizanteRel);
    }

    /**
     * Get empleadosSsoSubtipoCotizanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosSsoSubtipoCotizanteRel()
    {
        return $this->empleadosSsoSubtipoCotizanteRel;
    }

    /**
     * Add contratosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoSubtipoCotizanteRel
     *
     * @return RhuSsoSubtipoCotizante
     */
    public function addContratosSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoSubtipoCotizanteRel)
    {
        $this->contratosSsoSubtipoCotizanteRel[] = $contratosSsoSubtipoCotizanteRel;

        return $this;
    }

    /**
     * Remove contratosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoSubtipoCotizanteRel
     */
    public function removeContratosSsoSubtipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoSubtipoCotizanteRel)
    {
        $this->contratosSsoSubtipoCotizanteRel->removeElement($contratosSsoSubtipoCotizanteRel);
    }

    /**
     * Get contratosSsoSubtipoCotizanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSsoSubtipoCotizanteRel()
    {
        return $this->contratosSsoSubtipoCotizanteRel;
    }

    /**
     * Add afiContratosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoSubtipoCotizanteRel
     *
     * @return RhuSsoSubtipoCotizante
     */
    public function addAfiContratosSsoSubtipoCotizanteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoSubtipoCotizanteRel)
    {
        $this->afiContratosSsoSubtipoCotizanteRel[] = $afiContratosSsoSubtipoCotizanteRel;

        return $this;
    }

    /**
     * Remove afiContratosSsoSubtipoCotizanteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoSubtipoCotizanteRel
     */
    public function removeAfiContratosSsoSubtipoCotizanteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoSubtipoCotizanteRel)
    {
        $this->afiContratosSsoSubtipoCotizanteRel->removeElement($afiContratosSsoSubtipoCotizanteRel);
    }

    /**
     * Get afiContratosSsoSubtipoCotizanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiContratosSsoSubtipoCotizanteRel()
    {
        return $this->afiContratosSsoSubtipoCotizanteRel;
    }
}
