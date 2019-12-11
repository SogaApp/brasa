<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoTipoRepository")
 */
class RhuPagoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */         
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="RhuPago", mappedBy="pagoTipoRel")
     */
    protected $pagosPagoTipoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPago", mappedBy="pagoTipoRel")
     */
    protected $programacionesPagosPagoTipoRel;    


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosPagoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->programacionesPagosPagoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoTipoPk
     *
     * @return integer
     */
    public function getCodigoPagoTipoPk()
    {
        return $this->codigoPagoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPagoTipo
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
     * Add pagosPagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosPagoTipoRel
     *
     * @return RhuPagoTipo
     */
    public function addPagosPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosPagoTipoRel)
    {
        $this->pagosPagoTipoRel[] = $pagosPagoTipoRel;

        return $this;
    }

    /**
     * Remove pagosPagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosPagoTipoRel
     */
    public function removePagosPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPago $pagosPagoTipoRel)
    {
        $this->pagosPagoTipoRel->removeElement($pagosPagoTipoRel);
    }

    /**
     * Get pagosPagoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosPagoTipoRel()
    {
        return $this->pagosPagoTipoRel;
    }

    /**
     * Add programacionesPagosPagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosPagoTipoRel
     *
     * @return RhuPagoTipo
     */
    public function addProgramacionesPagosPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosPagoTipoRel)
    {
        $this->programacionesPagosPagoTipoRel[] = $programacionesPagosPagoTipoRel;

        return $this;
    }

    /**
     * Remove programacionesPagosPagoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosPagoTipoRel
     */
    public function removeProgramacionesPagosPagoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago $programacionesPagosPagoTipoRel)
    {
        $this->programacionesPagosPagoTipoRel->removeElement($programacionesPagosPagoTipoRel);
    }

    /**
     * Get programacionesPagosPagoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosPagoTipoRel()
    {
        return $this->programacionesPagosPagoTipoRel;
    }
}
