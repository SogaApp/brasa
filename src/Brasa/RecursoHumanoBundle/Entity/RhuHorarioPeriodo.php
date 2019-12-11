<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_horario_periodo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuHorarioPeriodoRepository")
 */
class RhuHorarioPeriodo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_horario_periodo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoHorarioPeriodoPk;
    
    /**
     * @ORM\Column(name="fecha_periodo", type="date")
     */    
    private $fechaPeriodo;
    
    /**     
     * @ORM\Column(name="estado_generado", type="boolean")
     */    
    private $estadoGenerado = FALSE;
    
    /**     
     * @ORM\Column(name="estado_cerrado", type="boolean")
     */    
    private $estadoCerrado = FALSE;

    /**
     * @ORM\OneToMany(targetEntity="RhuHorarioAcceso", mappedBy="horarioPeriodoRel")
     */
    protected $horariosAccesosHorarioPeriodoRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horariosAccesosHorarioPeriodoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoHorarioPeriodoPk
     *
     * @return integer
     */
    public function getCodigoHorarioPeriodoPk()
    {
        return $this->codigoHorarioPeriodoPk;
    }

    /**
     * Set fechaPeriodo
     *
     * @param \DateTime $fechaPeriodo
     *
     * @return RhuHorarioPeriodo
     */
    public function setFechaPeriodo($fechaPeriodo)
    {
        $this->fechaPeriodo = $fechaPeriodo;

        return $this;
    }

    /**
     * Get fechaPeriodo
     *
     * @return \DateTime
     */
    public function getFechaPeriodo()
    {
        return $this->fechaPeriodo;
    }

    /**
     * Set estadoGenerado
     *
     * @param boolean $estadoGenerado
     *
     * @return RhuHorarioPeriodo
     */
    public function setEstadoGenerado($estadoGenerado)
    {
        $this->estadoGenerado = $estadoGenerado;

        return $this;
    }

    /**
     * Get estadoGenerado
     *
     * @return boolean
     */
    public function getEstadoGenerado()
    {
        return $this->estadoGenerado;
    }

    /**
     * Set estadoCerrado
     *
     * @param boolean $estadoCerrado
     *
     * @return RhuHorarioPeriodo
     */
    public function setEstadoCerrado($estadoCerrado)
    {
        $this->estadoCerrado = $estadoCerrado;

        return $this;
    }

    /**
     * Get estadoCerrado
     *
     * @return boolean
     */
    public function getEstadoCerrado()
    {
        return $this->estadoCerrado;
    }

    /**
     * Add horariosAccesosHorarioPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosHorarioPeriodoRel
     *
     * @return RhuHorarioPeriodo
     */
    public function addHorariosAccesosHorarioPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosHorarioPeriodoRel)
    {
        $this->horariosAccesosHorarioPeriodoRel[] = $horariosAccesosHorarioPeriodoRel;

        return $this;
    }

    /**
     * Remove horariosAccesosHorarioPeriodoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosHorarioPeriodoRel
     */
    public function removeHorariosAccesosHorarioPeriodoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosHorarioPeriodoRel)
    {
        $this->horariosAccesosHorarioPeriodoRel->removeElement($horariosAccesosHorarioPeriodoRel);
    }

    /**
     * Get horariosAccesosHorarioPeriodoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorariosAccesosHorarioPeriodoRel()
    {
        return $this->horariosAccesosHorarioPeriodoRel;
    }
}
