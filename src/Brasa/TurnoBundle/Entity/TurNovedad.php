<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_novedad")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurNovedadRepository")
 */
class TurNovedad
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_novedad_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoNovedadPk;             

    /**
     * @ORM\Column(name="codigo_novedad_tipo_fk", type="integer", nullable=true)
     */    
    private $codigoNovedadTipoFk;
    
    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoFk;    

    /**
     * @ORM\Column(name="codigo_recurso_reemplazo_fk", type="integer", nullable=true)
     */    
    private $codigoRecursoReemplazoFk;
    
    /**
     * @ORM\Column(name="fecha_desde", type="date", nullable=true)
     */    
    private $fechaDesde;    

    /**
     * @ORM\Column(name="fecha_hasta", type="date", nullable=true)
     */    
    private $fechaHasta;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**     
     * @ORM\Column(name="estado_aplicada", type="boolean")
     */    
    private $estadoAplicada = false;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="novedadesRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;

    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="novedadesRecursoReemplazoRel")
     * @ORM\JoinColumn(name="codigo_recurso_reemplazo_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoReemplazoRel;    

    /**
     * @ORM\ManyToOne(targetEntity="TurNovedadTipo", inversedBy="novedadesNovedadTipoRel")
     * @ORM\JoinColumn(name="codigo_novedad_tipo_fk", referencedColumnName="codigo_novedad_tipo_pk")
     */
    protected $novedadTipoRel;    


    /**
     * Get codigoNovedadPk
     *
     * @return integer
     */
    public function getCodigoNovedadPk()
    {
        return $this->codigoNovedadPk;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurNovedad
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set codigoRecursoReemplazoFk
     *
     * @param integer $codigoRecursoReemplazoFk
     *
     * @return TurNovedad
     */
    public function setCodigoRecursoReemplazoFk($codigoRecursoReemplazoFk)
    {
        $this->codigoRecursoReemplazoFk = $codigoRecursoReemplazoFk;

        return $this;
    }

    /**
     * Get codigoRecursoReemplazoFk
     *
     * @return integer
     */
    public function getCodigoRecursoReemplazoFk()
    {
        return $this->codigoRecursoReemplazoFk;
    }

    /**
     * Set fechaDesde
     *
     * @param \DateTime $fechaDesde
     *
     * @return TurNovedad
     */
    public function setFechaDesde($fechaDesde)
    {
        $this->fechaDesde = $fechaDesde;

        return $this;
    }

    /**
     * Get fechaDesde
     *
     * @return \DateTime
     */
    public function getFechaDesde()
    {
        return $this->fechaDesde;
    }

    /**
     * Set fechaHasta
     *
     * @param \DateTime $fechaHasta
     *
     * @return TurNovedad
     */
    public function setFechaHasta($fechaHasta)
    {
        $this->fechaHasta = $fechaHasta;

        return $this;
    }

    /**
     * Get fechaHasta
     *
     * @return \DateTime
     */
    public function getFechaHasta()
    {
        return $this->fechaHasta;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurNovedad
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurNovedad
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
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurNovedad
     */
    public function setRecursoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoRel = null)
    {
        $this->recursoRel = $recursoRel;

        return $this;
    }

    /**
     * Get recursoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }

    /**
     * Set recursoReemplazoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoReemplazoRel
     *
     * @return TurNovedad
     */
    public function setRecursoReemplazoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoReemplazoRel = null)
    {
        $this->recursoReemplazoRel = $recursoReemplazoRel;

        return $this;
    }

    /**
     * Get recursoReemplazoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoReemplazoRel()
    {
        return $this->recursoReemplazoRel;
    }

    /**
     * Set codigoNovedadTipoFk
     *
     * @param integer $codigoNovedadTipoFk
     *
     * @return TurNovedad
     */
    public function setCodigoNovedadTipoFk($codigoNovedadTipoFk)
    {
        $this->codigoNovedadTipoFk = $codigoNovedadTipoFk;

        return $this;
    }

    /**
     * Get codigoNovedadTipoFk
     *
     * @return integer
     */
    public function getCodigoNovedadTipoFk()
    {
        return $this->codigoNovedadTipoFk;
    }

    /**
     * Set novedadTipoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurNovedadTipo $novedadTipoRel
     *
     * @return TurNovedad
     */
    public function setNovedadTipoRel(\Brasa\TurnoBundle\Entity\TurNovedadTipo $novedadTipoRel = null)
    {
        $this->novedadTipoRel = $novedadTipoRel;

        return $this;
    }

    /**
     * Get novedadTipoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurNovedadTipo
     */
    public function getNovedadTipoRel()
    {
        return $this->novedadTipoRel;
    }

    /**
     * Set estadoAplicada
     *
     * @param boolean $estadoAplicada
     *
     * @return TurNovedad
     */
    public function setEstadoAplicada($estadoAplicada)
    {
        $this->estadoAplicada = $estadoAplicada;

        return $this;
    }

    /**
     * Get estadoAplicada
     *
     * @return boolean
     */
    public function getEstadoAplicada()
    {
        return $this->estadoAplicada;
    }
}
