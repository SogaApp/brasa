<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_sede")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSedeRepository")
 */
class RhuSede
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_sede_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSedePk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;  
    
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk; 
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCentroCosto", inversedBy="sedesCentroCostoRel")
     * @ORM\JoinColumn(name="codigo_centro_costo_fk", referencedColumnName="codigo_centro_costo_pk")
     */
    protected $centroCostoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuProgramacionPagoDetalleSede", mappedBy="sedeRel")
     */
    protected $programacionesPagosDetallesSedeRel;       

    /**
     * @ORM\OneToMany(targetEntity="RhuPagoDetalleSede", mappedBy="sedeRel")
     */
    protected $pagosDetallesSedesSedeRel;     

    /**
     * @ORM\OneToMany(targetEntity="RhuContratoSede", mappedBy="sedeRel")
     */
    protected $contratosSedesSedeRel; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->programacionesPagosDetallesSedeRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pagosDetallesSedesSedeRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoSedePk
     *
     * @return integer
     */
    public function getCodigoSedePk()
    {
        return $this->codigoSedePk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuSede
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
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return RhuSede
     */
    public function setCodigoCentroCostoFk($codigoCentroCostoFk)
    {
        $this->codigoCentroCostoFk = $codigoCentroCostoFk;

        return $this;
    }

    /**
     * Get codigoCentroCostoFk
     *
     * @return integer
     */
    public function getCodigoCentroCostoFk()
    {
        return $this->codigoCentroCostoFk;
    }

    /**
     * Set centroCostoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel
     *
     * @return RhuSede
     */
    public function setCentroCostoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto $centroCostoRel = null)
    {
        $this->centroCostoRel = $centroCostoRel;

        return $this;
    }

    /**
     * Get centroCostoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCentroCosto
     */
    public function getCentroCostoRel()
    {
        return $this->centroCostoRel;
    }

    /**
     * Add programacionesPagosDetallesSedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedeRel
     *
     * @return RhuSede
     */
    public function addProgramacionesPagosDetallesSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedeRel)
    {
        $this->programacionesPagosDetallesSedeRel[] = $programacionesPagosDetallesSedeRel;

        return $this;
    }

    /**
     * Remove programacionesPagosDetallesSedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedeRel
     */
    public function removeProgramacionesPagosDetallesSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPagoDetalleSede $programacionesPagosDetallesSedeRel)
    {
        $this->programacionesPagosDetallesSedeRel->removeElement($programacionesPagosDetallesSedeRel);
    }

    /**
     * Get programacionesPagosDetallesSedeRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProgramacionesPagosDetallesSedeRel()
    {
        return $this->programacionesPagosDetallesSedeRel;
    }

    /**
     * Add pagosDetallesSedesSedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesSedeRel
     *
     * @return RhuSede
     */
    public function addPagosDetallesSedesSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesSedeRel)
    {
        $this->pagosDetallesSedesSedeRel[] = $pagosDetallesSedesSedeRel;

        return $this;
    }

    /**
     * Remove pagosDetallesSedesSedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesSedeRel
     */
    public function removePagosDetallesSedesSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalleSede $pagosDetallesSedesSedeRel)
    {
        $this->pagosDetallesSedesSedeRel->removeElement($pagosDetallesSedesSedeRel);
    }

    /**
     * Get pagosDetallesSedesSedeRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosDetallesSedesSedeRel()
    {
        return $this->pagosDetallesSedesSedeRel;
    }

    /**
     * Add contratosSedesSedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesSedeRel
     *
     * @return RhuSede
     */
    public function addContratosSedesSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesSedeRel)
    {
        $this->contratosSedesSedeRel[] = $contratosSedesSedeRel;

        return $this;
    }

    /**
     * Remove contratosSedesSedeRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesSedeRel
     */
    public function removeContratosSedesSedeRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoSede $contratosSedesSedeRel)
    {
        $this->contratosSedesSedeRel->removeElement($contratosSedesSedeRel);
    }

    /**
     * Get contratosSedesSedeRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosSedesSedeRel()
    {
        return $this->contratosSedesSedeRel;
    }
}
