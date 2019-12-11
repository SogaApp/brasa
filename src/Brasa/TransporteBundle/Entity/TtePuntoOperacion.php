<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_punto_operacion")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TtePuntoOperacionRepository")
 */
class TtePuntoOperacion
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_punto_operacion_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPuntoOperacionPk;  
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;    

    /**
     * @ORM\Column(name="codigo_ciudad_origen_fk", type="integer", nullable=true)
     */    
    private $codigoCiudadOrigenFk;     
    
        /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenCiudad", inversedBy="puntosOperacionCiudadOrigenRel")
     * @ORM\JoinColumn(name="codigo_ciudad_origen_fk", referencedColumnName="codigo_ciudad_pk")
     */
    protected $ciudadOrigenRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TteProgramacionRecogida", mappedBy="puntoOperacionRel")
     */
    protected $programacionesRecogidasPuntoOperacionRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TteUsuarioConfiguracion", mappedBy="puntoOperacionRel")
     */
    protected $usuariosConfiguracionRel; 
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="puntoOperacionIngresoRel")
     */
    protected $guiasPuntoOperacionIngresoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteRecogida", mappedBy="puntoOperacionRel")
     */
    protected $recogidasPuntoOperacionRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteRecogidaProgramada", mappedBy="puntoOperacionRel")
     */
    protected $recogidasProgramadasRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TteGuia", mappedBy="puntoOperacionActualRel")
     */
    protected $guiasPuntoOperacionActualRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TteDespacho", mappedBy="puntoOperacionRel")
     */
    protected $despachosPuntoOperacionRel;     


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuariosConfiguracionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasPuntoOperacionIngresoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recogidasPuntoOperacionRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->recogidasProgramadasRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->guiasPuntoOperacionActualRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->despachosPuntoOperacionRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPuntoOperacionPk
     *
     * @return integer
     */
    public function getCodigoPuntoOperacionPk()
    {
        return $this->codigoPuntoOperacionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TtePuntoOperacion
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
     * Set codigoCiudadOrigenFk
     *
     * @param integer $codigoCiudadOrigenFk
     *
     * @return TtePuntoOperacion
     */
    public function setCodigoCiudadOrigenFk($codigoCiudadOrigenFk)
    {
        $this->codigoCiudadOrigenFk = $codigoCiudadOrigenFk;

        return $this;
    }

    /**
     * Get codigoCiudadOrigenFk
     *
     * @return integer
     */
    public function getCodigoCiudadOrigenFk()
    {
        return $this->codigoCiudadOrigenFk;
    }

    /**
     * Set ciudadOrigenRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenCiudad $ciudadOrigenRel
     *
     * @return TtePuntoOperacion
     */
    public function setCiudadOrigenRel(\Brasa\GeneralBundle\Entity\GenCiudad $ciudadOrigenRel = null)
    {
        $this->ciudadOrigenRel = $ciudadOrigenRel;

        return $this;
    }

    /**
     * Get ciudadOrigenRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenCiudad
     */
    public function getCiudadOrigenRel()
    {
        return $this->ciudadOrigenRel;
    }

    /**
     * Add usuariosConfiguracionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel
     *
     * @return TtePuntoOperacion
     */
    public function addUsuariosConfiguracionRel(\Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel)
    {
        $this->usuariosConfiguracionRel[] = $usuariosConfiguracionRel;

        return $this;
    }

    /**
     * Remove usuariosConfiguracionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel
     */
    public function removeUsuariosConfiguracionRel(\Brasa\TransporteBundle\Entity\TteUsuarioConfiguracion $usuariosConfiguracionRel)
    {
        $this->usuariosConfiguracionRel->removeElement($usuariosConfiguracionRel);
    }

    /**
     * Get usuariosConfiguracionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuariosConfiguracionRel()
    {
        return $this->usuariosConfiguracionRel;
    }

    /**
     * Add guiasPuntoOperacionIngresoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionIngresoRel
     *
     * @return TtePuntoOperacion
     */
    public function addGuiasPuntoOperacionIngresoRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionIngresoRel)
    {
        $this->guiasPuntoOperacionIngresoRel[] = $guiasPuntoOperacionIngresoRel;

        return $this;
    }

    /**
     * Remove guiasPuntoOperacionIngresoRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionIngresoRel
     */
    public function removeGuiasPuntoOperacionIngresoRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionIngresoRel)
    {
        $this->guiasPuntoOperacionIngresoRel->removeElement($guiasPuntoOperacionIngresoRel);
    }

    /**
     * Get guiasPuntoOperacionIngresoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuiasPuntoOperacionIngresoRel()
    {
        return $this->guiasPuntoOperacionIngresoRel;
    }

    /**
     * Add recogidasPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogida $recogidasPuntoOperacionRel
     *
     * @return TtePuntoOperacion
     */
    public function addRecogidasPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteRecogida $recogidasPuntoOperacionRel)
    {
        $this->recogidasPuntoOperacionRel[] = $recogidasPuntoOperacionRel;

        return $this;
    }

    /**
     * Remove recogidasPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogida $recogidasPuntoOperacionRel
     */
    public function removeRecogidasPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteRecogida $recogidasPuntoOperacionRel)
    {
        $this->recogidasPuntoOperacionRel->removeElement($recogidasPuntoOperacionRel);
    }

    /**
     * Get recogidasPuntoOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecogidasPuntoOperacionRel()
    {
        return $this->recogidasPuntoOperacionRel;
    }

    /**
     * Add recogidasProgramadasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidaProgramada $recogidasProgramadasRel
     *
     * @return TtePuntoOperacion
     */
    public function addRecogidasProgramadasRel(\Brasa\TransporteBundle\Entity\TteRecogidaProgramada $recogidasProgramadasRel)
    {
        $this->recogidasProgramadasRel[] = $recogidasProgramadasRel;

        return $this;
    }

    /**
     * Remove recogidasProgramadasRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteRecogidaProgramada $recogidasProgramadasRel
     */
    public function removeRecogidasProgramadasRel(\Brasa\TransporteBundle\Entity\TteRecogidaProgramada $recogidasProgramadasRel)
    {
        $this->recogidasProgramadasRel->removeElement($recogidasProgramadasRel);
    }

    /**
     * Get recogidasProgramadasRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecogidasProgramadasRel()
    {
        return $this->recogidasProgramadasRel;
    }

    /**
     * Add guiasPuntoOperacionActualRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionActualRel
     *
     * @return TtePuntoOperacion
     */
    public function addGuiasPuntoOperacionActualRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionActualRel)
    {
        $this->guiasPuntoOperacionActualRel[] = $guiasPuntoOperacionActualRel;

        return $this;
    }

    /**
     * Remove guiasPuntoOperacionActualRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionActualRel
     */
    public function removeGuiasPuntoOperacionActualRel(\Brasa\TransporteBundle\Entity\TteGuia $guiasPuntoOperacionActualRel)
    {
        $this->guiasPuntoOperacionActualRel->removeElement($guiasPuntoOperacionActualRel);
    }

    /**
     * Get guiasPuntoOperacionActualRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGuiasPuntoOperacionActualRel()
    {
        return $this->guiasPuntoOperacionActualRel;
    }

    /**
     * Add despachosPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosPuntoOperacionRel
     *
     * @return TtePuntoOperacion
     */
    public function addDespachosPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosPuntoOperacionRel)
    {
        $this->despachosPuntoOperacionRel[] = $despachosPuntoOperacionRel;

        return $this;
    }

    /**
     * Remove despachosPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteDespacho $despachosPuntoOperacionRel
     */
    public function removeDespachosPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteDespacho $despachosPuntoOperacionRel)
    {
        $this->despachosPuntoOperacionRel->removeElement($despachosPuntoOperacionRel);
    }

    /**
     * Get despachosPuntoOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDespachosPuntoOperacionRel()
    {
        return $this->despachosPuntoOperacionRel;
    }

    /**
     * Add programacionesRecogidasPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasPuntoOperacionRel
     *
     * @return TtePuntoOperacion
     */
    public function addProgramacionesRecogidasPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasPuntoOperacionRel)
    {
        $this->programacionesRecogidasPuntoOperacionRel[] = $programacionesRecogidasPuntoOperacionRel;

        return $this;
    }

    /**
     * Remove programacionesRecogidasPuntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasPuntoOperacionRel
     */
    public function removeProgramacionesRecogidasPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionesRecogidasPuntoOperacionRel)
    {
        $this->programacionesRecogidasPuntoOperacionRel->removeElement($programacionesRecogidasPuntoOperacionRel);
    }

    /**
     * Get programacionesRecogidasPuntoOperacionRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesRecogidasPuntoOperacionRel()
    {
        return $this->programacionesRecogidasPuntoOperacionRel;
    }
}
