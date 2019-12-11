<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_turno")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuTurnoRepository")
 * @DoctrineAssert\UniqueEntity(fields={"codigoTurnoPk"},message="Ya existe este código para turno")
 */
class RhuTurno
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_turno_pk", type="string", length=5)
     */
    private $codigoTurnoPk;       
    
    /**
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="hora_desde", type="time", nullable=true)
     */    
    private $horaDesde;    

    /**
     * @ORM\Column(name="hora_hasta", type="time", nullable=true)
     */    
    private $horaHasta;    
    
    /**
     * @ORM\Column(name="horas", type="float")
     */    
    private $horas = 0;    

    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_pausa", type="float")
     */    
    private $horasPausa = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;            
    
    /**
     * @ORM\Column(name="novedad", type="boolean", nullable=true)
     */    
    private $novedad = false;     

    /**
     * @ORM\Column(name="descanso", type="boolean", nullable=true)
     */    
    private $descanso = false;           

    /**
     * @ORM\Column(name="incapacidad", type="boolean", nullable=true)
     */    
    private $incapacidad = false;    
    
    /**
     * @ORM\Column(name="licencia", type="boolean", nullable=true)
     */    
    private $licencia = false;    
    
    /**
     * @ORM\Column(name="vacacion", type="boolean", nullable=true)
     */    
    private $vacacion = false;    
    
    /**
     * @ORM\Column(name="salida_dia_siguiente", type="boolean")
     */    
    private $salidaDiaSiguiente = false;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;       

    /**
     * @ORM\OneToMany(targetEntity="RhuHorarioAcceso", mappedBy="turnoRel")
     */
    protected $horariosAccesosTurnoRel;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->horariosAccesosTurnoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoTurnoPk
     *
     * @param string $codigoTurnoPk
     *
     * @return RhuTurno
     */
    public function setCodigoTurnoPk($codigoTurnoPk)
    {
        $this->codigoTurnoPk = $codigoTurnoPk;

        return $this;
    }

    /**
     * Get codigoTurnoPk
     *
     * @return string
     */
    public function getCodigoTurnoPk()
    {
        return $this->codigoTurnoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuTurno
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
     * Set horaDesde
     *
     * @param \DateTime $horaDesde
     *
     * @return RhuTurno
     */
    public function setHoraDesde($horaDesde)
    {
        $this->horaDesde = $horaDesde;

        return $this;
    }

    /**
     * Get horaDesde
     *
     * @return \DateTime
     */
    public function getHoraDesde()
    {
        return $this->horaDesde;
    }

    /**
     * Set horaHasta
     *
     * @param \DateTime $horaHasta
     *
     * @return RhuTurno
     */
    public function setHoraHasta($horaHasta)
    {
        $this->horaHasta = $horaHasta;

        return $this;
    }

    /**
     * Get horaHasta
     *
     * @return \DateTime
     */
    public function getHoraHasta()
    {
        return $this->horaHasta;
    }

    /**
     * Set horas
     *
     * @param float $horas
     *
     * @return RhuTurno
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return float
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param float $horasDiurnas
     *
     * @return RhuTurno
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return float
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param float $horasNocturnas
     *
     * @return RhuTurno
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return float
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set novedad
     *
     * @param boolean $novedad
     *
     * @return RhuTurno
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;

        return $this;
    }

    /**
     * Get novedad
     *
     * @return boolean
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set descanso
     *
     * @param boolean $descanso
     *
     * @return RhuTurno
     */
    public function setDescanso($descanso)
    {
        $this->descanso = $descanso;

        return $this;
    }

    /**
     * Get descanso
     *
     * @return boolean
     */
    public function getDescanso()
    {
        return $this->descanso;
    }

    /**
     * Set salidaDiaSiguiente
     *
     * @param boolean $salidaDiaSiguiente
     *
     * @return RhuTurno
     */
    public function setSalidaDiaSiguiente($salidaDiaSiguiente)
    {
        $this->salidaDiaSiguiente = $salidaDiaSiguiente;

        return $this;
    }

    /**
     * Get salidaDiaSiguiente
     *
     * @return boolean
     */
    public function getSalidaDiaSiguiente()
    {
        return $this->salidaDiaSiguiente;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuTurno
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
     * Add horariosAccesosTurnoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosTurnoRel
     *
     * @return RhuTurno
     */
    public function addHorariosAccesosTurnoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosTurnoRel)
    {
        $this->horariosAccesosTurnoRel[] = $horariosAccesosTurnoRel;

        return $this;
    }

    /**
     * Remove horariosAccesosTurnoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosTurnoRel
     */
    public function removeHorariosAccesosTurnoRel(\Brasa\RecursoHumanoBundle\Entity\RhuHorarioAcceso $horariosAccesosTurnoRel)
    {
        $this->horariosAccesosTurnoRel->removeElement($horariosAccesosTurnoRel);
    }

    /**
     * Get horariosAccesosTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorariosAccesosTurnoRel()
    {
        return $this->horariosAccesosTurnoRel;
    }

    /**
     * Set incapacidad
     *
     * @param boolean $incapacidad
     *
     * @return RhuTurno
     */
    public function setIncapacidad($incapacidad)
    {
        $this->incapacidad = $incapacidad;

        return $this;
    }

    /**
     * Get incapacidad
     *
     * @return boolean
     */
    public function getIncapacidad()
    {
        return $this->incapacidad;
    }

    /**
     * Set licencia
     *
     * @param boolean $licencia
     *
     * @return RhuTurno
     */
    public function setLicencia($licencia)
    {
        $this->licencia = $licencia;

        return $this;
    }

    /**
     * Get licencia
     *
     * @return boolean
     */
    public function getLicencia()
    {
        return $this->licencia;
    }

    /**
     * Set vacacion
     *
     * @param boolean $vacacion
     *
     * @return RhuTurno
     */
    public function setVacacion($vacacion)
    {
        $this->vacacion = $vacacion;

        return $this;
    }

    /**
     * Get vacacion
     *
     * @return boolean
     */
    public function getVacacion()
    {
        return $this->vacacion;
    }

    /**
     * Set horasPausa
     *
     * @param float $horasPausa
     *
     * @return RhuTurno
     */
    public function setHorasPausa($horasPausa)
    {
        $this->horasPausa = $horasPausa;

        return $this;
    }

    /**
     * Get horasPausa
     *
     * @return float
     */
    public function getHorasPausa()
    {
        return $this->horasPausa;
    }
}
