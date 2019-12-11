<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_contrato_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuContratoTipoRepository")
 */
class RhuContratoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_contrato_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoContratoTipoPk;        
    
    /**
     * @ORM\Column(name="nombre", type="string", length=200, nullable=true)
     */    
    private $nombre; 
    
    /**
     * @ORM\Column(name="nombre_corto", type="string", length=100, nullable=true)
     */    
    private $nombreCorto;     
    
    /**
     * @ORM\Column(name="codigo_contenido_formato_fk", type="integer", nullable=true)
     */    
    private $codigoContenidoFormatoFk;
    
    /**
     * @ORM\Column(name="codigo_contrato_clase_fk", type="integer", nullable=true)
     */    
    private $codigoContratoClaseFk;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuContrato", mappedBy="contratoTipoRel")
     */
    protected $contratosContratoTipoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="Brasa\GeneralBundle\Entity\GenContenidoFormato", inversedBy="contratosTiposContenidoFormatoRel")
     * @ORM\JoinColumn(name="codigo_contenido_formato_fk", referencedColumnName="codigo_contenido_formato_pk")
     */
    protected $contenidoFormatoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuContratoClase", inversedBy="contratosTiposContratoClaseRel")
     * @ORM\JoinColumn(name="codigo_contrato_clase_fk", referencedColumnName="codigo_contrato_clase_pk")
     */
    protected $contratoClaseRel;

    /**
     * @ORM\OneToMany(targetEntity="RhuCambioTipoContrato", mappedBy="contratoTipoAnteriorRel")
     */
    protected $cambiosTiposContratosAnterioresContratoTipoRel;
    
    /**
     * @ORM\OneToMany(targetEntity="RhuCambioTipoContrato", mappedBy="contratoTipoNuevoRel")
     */
    protected $cambiosTiposContratosNuevosContratoTipoRel;
                   
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratosContratoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosTiposContratosAnterioresContratoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cambiosTiposContratosNuevosContratoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoContratoTipoPk
     *
     * @return integer
     */
    public function getCodigoContratoTipoPk()
    {
        return $this->codigoContratoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuContratoTipo
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
     * Set nombreCorto
     *
     * @param string $nombreCorto
     *
     * @return RhuContratoTipo
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
    }

    /**
     * Set codigoContenidoFormatoFk
     *
     * @param integer $codigoContenidoFormatoFk
     *
     * @return RhuContratoTipo
     */
    public function setCodigoContenidoFormatoFk($codigoContenidoFormatoFk)
    {
        $this->codigoContenidoFormatoFk = $codigoContenidoFormatoFk;

        return $this;
    }

    /**
     * Get codigoContenidoFormatoFk
     *
     * @return integer
     */
    public function getCodigoContenidoFormatoFk()
    {
        return $this->codigoContenidoFormatoFk;
    }

    /**
     * Set codigoContratoClaseFk
     *
     * @param integer $codigoContratoClaseFk
     *
     * @return RhuContratoTipo
     */
    public function setCodigoContratoClaseFk($codigoContratoClaseFk)
    {
        $this->codigoContratoClaseFk = $codigoContratoClaseFk;

        return $this;
    }

    /**
     * Get codigoContratoClaseFk
     *
     * @return integer
     */
    public function getCodigoContratoClaseFk()
    {
        return $this->codigoContratoClaseFk;
    }

    /**
     * Add contratosContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel
     *
     * @return RhuContratoTipo
     */
    public function addContratosContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel)
    {
        $this->contratosContratoTipoRel[] = $contratosContratoTipoRel;

        return $this;
    }

    /**
     * Remove contratosContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel
     */
    public function removeContratosContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuContrato $contratosContratoTipoRel)
    {
        $this->contratosContratoTipoRel->removeElement($contratosContratoTipoRel);
    }

    /**
     * Get contratosContratoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratosContratoTipoRel()
    {
        return $this->contratosContratoTipoRel;
    }

    /**
     * Set contenidoFormatoRel
     *
     * @param \Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel
     *
     * @return RhuContratoTipo
     */
    public function setContenidoFormatoRel(\Brasa\GeneralBundle\Entity\GenContenidoFormato $contenidoFormatoRel = null)
    {
        $this->contenidoFormatoRel = $contenidoFormatoRel;

        return $this;
    }

    /**
     * Get contenidoFormatoRel
     *
     * @return \Brasa\GeneralBundle\Entity\GenContenidoFormato
     */
    public function getContenidoFormatoRel()
    {
        return $this->contenidoFormatoRel;
    }

    /**
     * Set contratoClaseRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuContratoClase $contratoClaseRel
     *
     * @return RhuContratoTipo
     */
    public function setContratoClaseRel(\Brasa\RecursoHumanoBundle\Entity\RhuContratoClase $contratoClaseRel = null)
    {
        $this->contratoClaseRel = $contratoClaseRel;

        return $this;
    }

    /**
     * Get contratoClaseRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuContratoClase
     */
    public function getContratoClaseRel()
    {
        return $this->contratoClaseRel;
    }

    /**
     * Add cambiosTiposContratosAnterioresContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosAnterioresContratoTipoRel
     *
     * @return RhuContratoTipo
     */
    public function addCambiosTiposContratosAnterioresContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosAnterioresContratoTipoRel)
    {
        $this->cambiosTiposContratosAnterioresContratoTipoRel[] = $cambiosTiposContratosAnterioresContratoTipoRel;

        return $this;
    }

    /**
     * Remove cambiosTiposContratosAnterioresContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosAnterioresContratoTipoRel
     */
    public function removeCambiosTiposContratosAnterioresContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosAnterioresContratoTipoRel)
    {
        $this->cambiosTiposContratosAnterioresContratoTipoRel->removeElement($cambiosTiposContratosAnterioresContratoTipoRel);
    }

    /**
     * Get cambiosTiposContratosAnterioresContratoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCambiosTiposContratosAnterioresContratoTipoRel()
    {
        return $this->cambiosTiposContratosAnterioresContratoTipoRel;
    }

    /**
     * Add cambiosTiposContratosNuevosContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosNuevosContratoTipoRel
     *
     * @return RhuContratoTipo
     */
    public function addCambiosTiposContratosNuevosContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosNuevosContratoTipoRel)
    {
        $this->cambiosTiposContratosNuevosContratoTipoRel[] = $cambiosTiposContratosNuevosContratoTipoRel;

        return $this;
    }

    /**
     * Remove cambiosTiposContratosNuevosContratoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosNuevosContratoTipoRel
     */
    public function removeCambiosTiposContratosNuevosContratoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCambioTipoContrato $cambiosTiposContratosNuevosContratoTipoRel)
    {
        $this->cambiosTiposContratosNuevosContratoTipoRel->removeElement($cambiosTiposContratosNuevosContratoTipoRel);
    }

    /**
     * Get cambiosTiposContratosNuevosContratoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCambiosTiposContratosNuevosContratoTipoRel()
    {
        return $this->cambiosTiposContratosNuevosContratoTipoRel;
    }
}
