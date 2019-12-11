<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_novedad_tipo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurNovedadTipoRepository")
 */
class TurNovedadTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;                         
    
    /**
     * @ORM\Column(name="codigo_turno_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFk;    
    
    /**
     * @ORM\ManyToOne(targetEntity="TurTurno", inversedBy="novedadesTiposTurnoRel")
     * @ORM\JoinColumn(name="codigo_turno_fk", referencedColumnName="codigo_turno_pk")
     */
    protected $turnoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurNovedad", mappedBy="novedadTipoRel")
     */
    protected $novedadesNovedadTipoRel; 
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->novedadesNovedadTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoNovedadTipoPk
     *
     * @return integer
     */
    public function getCodigoNovedadTipoPk()
    {
        return $this->codigoNovedadTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurNovedadTipo
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
     * Add novedadesNovedadTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurNovedad $novedadesNovedadTipoRel
     *
     * @return TurNovedadTipo
     */
    public function addNovedadesNovedadTipoRel(\Brasa\TurnoBundle\Entity\TurNovedad $novedadesNovedadTipoRel)
    {
        $this->novedadesNovedadTipoRel[] = $novedadesNovedadTipoRel;

        return $this;
    }

    /**
     * Remove novedadesNovedadTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurNovedad $novedadesNovedadTipoRel
     */
    public function removeNovedadesNovedadTipoRel(\Brasa\TurnoBundle\Entity\TurNovedad $novedadesNovedadTipoRel)
    {
        $this->novedadesNovedadTipoRel->removeElement($novedadesNovedadTipoRel);
    }

    /**
     * Get novedadesNovedadTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNovedadesNovedadTipoRel()
    {
        return $this->novedadesNovedadTipoRel;
    }

    /**
     * Set codigoTurnoFk
     *
     * @param string $codigoTurnoFk
     *
     * @return TurNovedadTipo
     */
    public function setCodigoTurnoFk($codigoTurnoFk)
    {
        $this->codigoTurnoFk = $codigoTurnoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFk
     *
     * @return string
     */
    public function getCodigoTurnoFk()
    {
        return $this->codigoTurnoFk;
    }

    /**
     * Set turnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurno $turnoRel
     *
     * @return TurNovedadTipo
     */
    public function setTurnoRel(\Brasa\TurnoBundle\Entity\TurTurno $turnoRel = null)
    {
        $this->turnoRel = $turnoRel;

        return $this;
    }

    /**
     * Get turnoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurTurno
     */
    public function getTurnoRel()
    {
        return $this->turnoRel;
    }
}
