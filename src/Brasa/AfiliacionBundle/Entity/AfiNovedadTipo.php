<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_novedad_tipo")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiNovedadTipoRepository")
 */
class AfiNovedadTipo
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
     * @ORM\OneToMany(targetEntity="AfiNovedad", mappedBy="novedadTipoRel")
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
     * @return AfiNovedadTipo
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
     * @param \Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesNovedadTipoRel
     *
     * @return AfiNovedadTipo
     */
    public function addNovedadesNovedadTipoRel(\Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesNovedadTipoRel)
    {
        $this->novedadesNovedadTipoRel[] = $novedadesNovedadTipoRel;

        return $this;
    }

    /**
     * Remove novedadesNovedadTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesNovedadTipoRel
     */
    public function removeNovedadesNovedadTipoRel(\Brasa\AfiliacionBundle\Entity\AfiNovedad $novedadesNovedadTipoRel)
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
}
