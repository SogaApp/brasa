<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sso_tipo_cotizante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSsoTipoCotizanteRepository")
 */
class RhuSsoTipoCotizante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tipo_cotizante_pk", type="integer")
     */
    private $codigoTipoCotizantePk;   
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="ssoTipoCotizanteRel")
     */
    protected $empleadosSsoTipoCotizanteRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="ssoTipoCotizanteRel")
     */
    protected $contratosSsoTipoCotizanteRel;          
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiContrato", mappedBy="ssoTipoCotizanteRel")
     */
    protected $afiContratosSsoTipoCotizanteRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosSsoTipoCotizanteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contratosSsoTipoCotizanteRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiContratosSsoTipoCotizanteRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoTipoCotizantePk
     *
     * @param integer $codigoTipoCotizantePk
     *
     * @return RhuSsoTipoCotizante
     */
    public function setCodigoTipoCotizantePk($codigoTipoCotizantePk)
    {
        $this->codigoTipoCotizantePk = $codigoTipoCotizantePk;

        return $this;
    }

    /**
     * Get codigoTipoCotizantePk
     *
     * @return integer
     */
    public function getCodigoTipoCotizantePk()
    {
        return $this->codigoTipoCotizantePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSsoTipoCotizante
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
     * Add empleadosSsoTipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoTipoCotizanteRel
     *
     * @return RhuSsoTipoCotizante
     */
    public function addEmpleadosSsoTipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoTipoCotizanteRel)
    {
        $this->empleadosSsoTipoCotizanteRel[] = $empleadosSsoTipoCotizanteRel;

        return $this;
    }

    /**
     * Remove empleadosSsoTipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoTipoCotizanteRel
     */
    public function removeEmpleadosSsoTipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosSsoTipoCotizanteRel)
    {
        $this->empleadosSsoTipoCotizanteRel->removeElement($empleadosSsoTipoCotizanteRel);
    }

    /**
     * Get empleadosSsoTipoCotizanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosSsoTipoCotizanteRel()
    {
        return $this->empleadosSsoTipoCotizanteRel;
    }

    /**
     * Add contratosSsoTipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoTipoCotizanteRel
     *
     * @return RhuSsoTipoCotizante
     */
    public function addContratosSsoTipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoTipoCotizanteRel)
    {
        $this->contratosSsoTipoCotizanteRel[] = $contratosSsoTipoCotizanteRel;

        return $this;
    }

    /**
     * Remove contratosSsoTipoCotizanteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoTipoCotizanteRel
     */
    public function removeContratosSsoTipoCotizanteRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosSsoTipoCotizanteRel)
    {
        $this->contratosSsoTipoCotizanteRel->removeElement($contratosSsoTipoCotizanteRel);
    }

    /**
     * Get contratosSsoTipoCotizanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSsoTipoCotizanteRel()
    {
        return $this->contratosSsoTipoCotizanteRel;
    }

    /**
     * Add afiContratosSsoTipoCotizanteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoTipoCotizanteRel
     *
     * @return RhuSsoTipoCotizante
     */
    public function addAfiContratosSsoTipoCotizanteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoTipoCotizanteRel)
    {
        $this->afiContratosSsoTipoCotizanteRel[] = $afiContratosSsoTipoCotizanteRel;

        return $this;
    }

    /**
     * Remove afiContratosSsoTipoCotizanteRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoTipoCotizanteRel
     */
    public function removeAfiContratosSsoTipoCotizanteRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $afiContratosSsoTipoCotizanteRel)
    {
        $this->afiContratosSsoTipoCotizanteRel->removeElement($afiContratosSsoTipoCotizanteRel);
    }

    /**
     * Get afiContratosSsoTipoCotizanteRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiContratosSsoTipoCotizanteRel()
    {
        return $this->afiContratosSsoTipoCotizanteRel;
    }
}
