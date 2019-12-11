<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_entidad_entrenamiento_costo")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiEntidadEntrenamientoCostoRepository")
 */
class AfiEntidadEntrenamientoCosto
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_entidad_entrenamiento_costo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEntidadEntrenamientoCostoPk;        

    /**
     * @ORM\Column(name="codigo_entidad_entrenamiento_fk", type="integer", nullable=true)
     */    
    private $codigoEntidadEntrenamientoFk;
    
    /**
     * @ORM\Column(name="codigo_curso_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoCursoTipoFk;    

    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;        

    /**
     * @ORM\ManyToOne(targetEntity="AfiEntidadEntrenamiento", inversedBy="entidadesEntrenamientosCostosEntidadEntrenamientoRel")
     * @ORM\JoinColumn(name="codigo_entidad_entrenamiento_fk", referencedColumnName="codigo_entidad_entrenamiento_pk")
     */
    protected $entidadEntrenamientoRel;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCursoTipo", inversedBy="entidadesEntrenamientoCostosCursoTipoRel")
     * @ORM\JoinColumn(name="codigo_curso_tipo_fk", referencedColumnName="codigo_curso_tipo_pk")
     */
    protected $cursoTipoRel;     


    /**
     * Get codigoEntidadEntrenamientoCostoPk
     *
     * @return integer
     */
    public function getCodigoEntidadEntrenamientoCostoPk()
    {
        return $this->codigoEntidadEntrenamientoCostoPk;
    }

    /**
     * Set codigoEntidadEntrenamientoFk
     *
     * @param integer $codigoEntidadEntrenamientoFk
     *
     * @return AfiEntidadEntrenamientoCosto
     */
    public function setCodigoEntidadEntrenamientoFk($codigoEntidadEntrenamientoFk)
    {
        $this->codigoEntidadEntrenamientoFk = $codigoEntidadEntrenamientoFk;

        return $this;
    }

    /**
     * Get codigoEntidadEntrenamientoFk
     *
     * @return integer
     */
    public function getCodigoEntidadEntrenamientoFk()
    {
        return $this->codigoEntidadEntrenamientoFk;
    }

    /**
     * Set codigoCursoTipoFk
     *
     * @param integer $codigoCursoTipoFk
     *
     * @return AfiEntidadEntrenamientoCosto
     */
    public function setCodigoCursoTipoFk($codigoCursoTipoFk)
    {
        $this->codigoCursoTipoFk = $codigoCursoTipoFk;

        return $this;
    }

    /**
     * Get codigoCursoTipoFk
     *
     * @return integer
     */
    public function getCodigoCursoTipoFk()
    {
        return $this->codigoCursoTipoFk;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiEntidadEntrenamientoCosto
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set entidadEntrenamientoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento $entidadEntrenamientoRel
     *
     * @return AfiEntidadEntrenamientoCosto
     */
    public function setEntidadEntrenamientoRel(\Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento $entidadEntrenamientoRel = null)
    {
        $this->entidadEntrenamientoRel = $entidadEntrenamientoRel;

        return $this;
    }

    /**
     * Get entidadEntrenamientoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiEntidadEntrenamiento
     */
    public function getEntidadEntrenamientoRel()
    {
        return $this->entidadEntrenamientoRel;
    }

    /**
     * Set cursoTipoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursoTipoRel
     *
     * @return AfiEntidadEntrenamientoCosto
     */
    public function setCursoTipoRel(\Brasa\AfiliacionBundle\Entity\AfiCursoTipo $cursoTipoRel = null)
    {
        $this->cursoTipoRel = $cursoTipoRel;

        return $this;
    }

    /**
     * Get cursoTipoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCursoTipo
     */
    public function getCursoTipoRel()
    {
        return $this->cursoTipoRel;
    }
}
