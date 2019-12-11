<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_requisicion_aspirante")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionRequisicionAspiranteRepository")
 */
class RhuSeleccionRequisicionAspirante
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_requisicion_aspirante_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionRequisicionAspirantePk;
    
    /**
     * @ORM\Column(name="codigo_seleccion_requisito_fk", type="integer", nullable=true)
     */
    private $codigoSeleccionRequisitoFk;
    
    /**
     * @ORM\Column(name="codigo_aspirante_fk", type="integer")
     */
    private $codigoAspiranteFk;
    
    /**     
     * @ORM\Column(name="estado_aprobado", type="boolean")
     */    
    private $estadoAprobado = false;
    
    /**
     * @ORM\Column(name="codigo_motivo_descarte_requisicion_aspirante_fk", type="integer", nullable=true)
     */    
    private $codigoMotivoDescarteRequisicionAspiranteFk;
    
    /**
     * @ORM\Column(name="fechaDescarte", type="datetime", nullable=true)
     */
    private $fechaDescarte;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;

   /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionRequisito", inversedBy="seleccionesRequisicionesAspirantesSeleccionRequisitoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_requisito_fk", referencedColumnName="codigo_seleccion_requisito_pk")
     */
    protected $seleccionRequisitoRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuAspirante", inversedBy="seleccionesRequisicionesAspirantesAspiranteRel")
     * @ORM\JoinColumn(name="codigo_aspirante_fk", referencedColumnName="codigo_aspirante_pk")
     */
    protected $aspiranteRel;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuMotivoDescarteRequisicionAspirante", inversedBy="motivosDescartesseleccionRequisicionAspiranteRel")
     * @ORM\JoinColumn(name="codigo_motivo_descarte_requisicion_aspirante_fk", referencedColumnName="codigo_motivo_descarte_requisicion_aspirante_pk")
     */
    protected $motivoDescarteRequisicionAspiranteRel;
    
    

    


    /**
     * Get codigoSeleccionRequisicionAspirantePk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisicionAspirantePk()
    {
        return $this->codigoSeleccionRequisicionAspirantePk;
    }

    /**
     * Set codigoSeleccionRequisitoFk
     *
     * @param integer $codigoSeleccionRequisitoFk
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setCodigoSeleccionRequisitoFk($codigoSeleccionRequisitoFk)
    {
        $this->codigoSeleccionRequisitoFk = $codigoSeleccionRequisitoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionRequisitoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionRequisitoFk()
    {
        return $this->codigoSeleccionRequisitoFk;
    }

    /**
     * Set codigoAspiranteFk
     *
     * @param integer $codigoAspiranteFk
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setCodigoAspiranteFk($codigoAspiranteFk)
    {
        $this->codigoAspiranteFk = $codigoAspiranteFk;

        return $this;
    }

    /**
     * Get codigoAspiranteFk
     *
     * @return integer
     */
    public function getCodigoAspiranteFk()
    {
        return $this->codigoAspiranteFk;
    }

    /**
     * Set estadoAprobado
     *
     * @param boolean $estadoAprobado
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setEstadoAprobado($estadoAprobado)
    {
        $this->estadoAprobado = $estadoAprobado;

        return $this;
    }

    /**
     * Get estadoAprobado
     *
     * @return boolean
     */
    public function getEstadoAprobado()
    {
        return $this->estadoAprobado;
    }

    /**
     * Set codigoMotivoDescarteRequisicionAspiranteFk
     *
     * @param integer $codigoMotivoDescarteRequisicionAspiranteFk
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setCodigoMotivoDescarteRequisicionAspiranteFk($codigoMotivoDescarteRequisicionAspiranteFk)
    {
        $this->codigoMotivoDescarteRequisicionAspiranteFk = $codigoMotivoDescarteRequisicionAspiranteFk;

        return $this;
    }

    /**
     * Get codigoMotivoDescarteRequisicionAspiranteFk
     *
     * @return integer
     */
    public function getCodigoMotivoDescarteRequisicionAspiranteFk()
    {
        return $this->codigoMotivoDescarteRequisicionAspiranteFk;
    }

    /**
     * Set fechaDescarte
     *
     * @param \DateTime $fechaDescarte
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setFechaDescarte($fechaDescarte)
    {
        $this->fechaDescarte = $fechaDescarte;

        return $this;
    }

    /**
     * Get fechaDescarte
     *
     * @return \DateTime
     */
    public function getFechaDescarte()
    {
        return $this->fechaDescarte;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set seleccionRequisitoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setSeleccionRequisitoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito $seleccionRequisitoRel = null)
    {
        $this->seleccionRequisitoRel = $seleccionRequisitoRel;

        return $this;
    }

    /**
     * Get seleccionRequisitoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionRequisito
     */
    public function getSeleccionRequisitoRel()
    {
        return $this->seleccionRequisitoRel;
    }

    /**
     * Set aspiranteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspiranteRel
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setAspiranteRel(\Brasa\RecursoHumanoBundle\Entity\RhuAspirante $aspiranteRel = null)
    {
        $this->aspiranteRel = $aspiranteRel;

        return $this;
    }

    /**
     * Get aspiranteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuAspirante
     */
    public function getAspiranteRel()
    {
        return $this->aspiranteRel;
    }

    /**
     * Set motivoDescarteRequisicionAspiranteRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuMotivoDescarteRequisicionAspirante $motivoDescarteRequisicionAspiranteRel
     *
     * @return RhuSeleccionRequisicionAspirante
     */
    public function setMotivoDescarteRequisicionAspiranteRel(\Brasa\RecursoHumanoBundle\Entity\RhuMotivoDescarteRequisicionAspirante $motivoDescarteRequisicionAspiranteRel = null)
    {
        $this->motivoDescarteRequisicionAspiranteRel = $motivoDescarteRequisicionAspiranteRel;

        return $this;
    }

    /**
     * Get motivoDescarteRequisicionAspiranteRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuMotivoDescarteRequisicionAspirante
     */
    public function getMotivoDescarteRequisicionAspiranteRel()
    {
        return $this->motivoDescarteRequisicionAspiranteRel;
    }
}
