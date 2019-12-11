<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_recurso_grupo")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurRecursoGrupoRepository")
 */
class TurRecursoGrupo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recurso_grupo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecursoGrupoPk;               
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */    
    private $nombre;             
    
    /**
     * @ORM\Column(name="codigo_turno_fijo_nomina_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFijoNominaFk;     
    
    /**
     * @ORM\Column(name="codigo_turno_fijo_descanso_fk", type="string", length=5, nullable=true)
     */    
    private $codigoTurnoFijoDescansoFk;         

    /**
     * @ORM\Column(name="dias_descanso_fijo", type="integer", nullable=true)
     */    
    private $diasDescansoFijo = 0;    
  
    /**
     * @ORM\Column(name="codigo_centro_costo_fk", type="integer", nullable=true)
     */    
    private $codigoCentroCostoFk = 0;        
    
    /**
     * @ORM\OneToMany(targetEntity="TurRecurso", mappedBy="recursoGrupoRel")
     */
    protected $recursosRecursoGrupoRel;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoPeriodo", mappedBy="recursoGrupoRel")
     */
    protected $soportesPagosPeriodosRecursoGrupoRel;    
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recursosRecursoGrupoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosPeriodosRecursoGrupoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoRecursoGrupoPk
     *
     * @return integer
     */
    public function getCodigoRecursoGrupoPk()
    {
        return $this->codigoRecursoGrupoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurRecursoGrupo
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
     * Set codigoTurnoFijoNominaFk
     *
     * @param string $codigoTurnoFijoNominaFk
     *
     * @return TurRecursoGrupo
     */
    public function setCodigoTurnoFijoNominaFk($codigoTurnoFijoNominaFk)
    {
        $this->codigoTurnoFijoNominaFk = $codigoTurnoFijoNominaFk;

        return $this;
    }

    /**
     * Get codigoTurnoFijoNominaFk
     *
     * @return string
     */
    public function getCodigoTurnoFijoNominaFk()
    {
        return $this->codigoTurnoFijoNominaFk;
    }

    /**
     * Set codigoTurnoFijoDescansoFk
     *
     * @param string $codigoTurnoFijoDescansoFk
     *
     * @return TurRecursoGrupo
     */
    public function setCodigoTurnoFijoDescansoFk($codigoTurnoFijoDescansoFk)
    {
        $this->codigoTurnoFijoDescansoFk = $codigoTurnoFijoDescansoFk;

        return $this;
    }

    /**
     * Get codigoTurnoFijoDescansoFk
     *
     * @return string
     */
    public function getCodigoTurnoFijoDescansoFk()
    {
        return $this->codigoTurnoFijoDescansoFk;
    }

    /**
     * Set diasDescansoFijo
     *
     * @param integer $diasDescansoFijo
     *
     * @return TurRecursoGrupo
     */
    public function setDiasDescansoFijo($diasDescansoFijo)
    {
        $this->diasDescansoFijo = $diasDescansoFijo;

        return $this;
    }

    /**
     * Get diasDescansoFijo
     *
     * @return integer
     */
    public function getDiasDescansoFijo()
    {
        return $this->diasDescansoFijo;
    }

    /**
     * Add recursosRecursoGrupoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursosRecursoGrupoRel
     *
     * @return TurRecursoGrupo
     */
    public function addRecursosRecursoGrupoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursosRecursoGrupoRel)
    {
        $this->recursosRecursoGrupoRel[] = $recursosRecursoGrupoRel;

        return $this;
    }

    /**
     * Remove recursosRecursoGrupoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursosRecursoGrupoRel
     */
    public function removeRecursosRecursoGrupoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursosRecursoGrupoRel)
    {
        $this->recursosRecursoGrupoRel->removeElement($recursosRecursoGrupoRel);
    }

    /**
     * Get recursosRecursoGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecursosRecursoGrupoRel()
    {
        return $this->recursosRecursoGrupoRel;
    }

    /**
     * Add soportesPagosPeriodosRecursoGrupoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosRecursoGrupoRel
     *
     * @return TurRecursoGrupo
     */
    public function addSoportesPagosPeriodosRecursoGrupoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosRecursoGrupoRel)
    {
        $this->soportesPagosPeriodosRecursoGrupoRel[] = $soportesPagosPeriodosRecursoGrupoRel;

        return $this;
    }

    /**
     * Remove soportesPagosPeriodosRecursoGrupoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosRecursoGrupoRel
     */
    public function removeSoportesPagosPeriodosRecursoGrupoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoPeriodo $soportesPagosPeriodosRecursoGrupoRel)
    {
        $this->soportesPagosPeriodosRecursoGrupoRel->removeElement($soportesPagosPeriodosRecursoGrupoRel);
    }

    /**
     * Get soportesPagosPeriodosRecursoGrupoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosPeriodosRecursoGrupoRel()
    {
        return $this->soportesPagosPeriodosRecursoGrupoRel;
    }

    /**
     * Set codigoCentroCostoFk
     *
     * @param integer $codigoCentroCostoFk
     *
     * @return TurRecursoGrupo
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
}
