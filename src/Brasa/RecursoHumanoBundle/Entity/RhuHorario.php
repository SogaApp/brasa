<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_horario")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuHorarioRepository")
 */
class RhuHorario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_horario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoHorarioPk; 
    
    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="hora_entrada", type="time", nullable=true)
     */    
    private $horaEntrada;    

    /**
     * @ORM\Column(name="hora_salida", type="time", nullable=true)
     */    
    private $horaSalida;    
    
    /**
     * @ORM\Column(name="genera_hora_extra", type="boolean")
     */    
    private $generaHoraExtra = false;           
    
    /**
     * @ORM\Column(name="control_horario", type="boolean")
     */    
    private $controlHorario = false;     
    
    /**
     * @ORM\Column(name="lunes", type="string", length=5)
     */
    private $lunes;     

    /**
     * @ORM\Column(name="martes", type="string", length=5)
     */
    private $martes;    
    
    /**
     * @ORM\Column(name="miercoles", type="string", length=5)
     */
    private $miercoles;    
    
    /**
     * @ORM\Column(name="jueves", type="string", length=5)
     */
    private $jueves;    
    
    /**
     * @ORM\Column(name="viernes", type="string", length=5)
     */
    private $viernes;    
    
    /**
     * @ORM\Column(name="sabado", type="string", length=5)
     */
    private $sabado;    
    
    /**
     * @ORM\Column(name="domingo", type="string", length=5)
     */
    private $domingo;    
    
    /**
     * @ORM\Column(name="festivo", type="string", length=5)
     */
    private $festivo;     
    
    /**
     * @ORM\OneToMany(targetEntity="RhuEmpleado", mappedBy="horarioRel")
     */
    protected $empleadosHorarioRel;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->empleadosHorarioRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoHorarioPk
     *
     * @return integer
     */
    public function getCodigoHorarioPk()
    {
        return $this->codigoHorarioPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuHorario
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
     * Set horaEntrada
     *
     * @param \DateTime $horaEntrada
     *
     * @return RhuHorario
     */
    public function setHoraEntrada($horaEntrada)
    {
        $this->horaEntrada = $horaEntrada;

        return $this;
    }

    /**
     * Get horaEntrada
     *
     * @return \DateTime
     */
    public function getHoraEntrada()
    {
        return $this->horaEntrada;
    }

    /**
     * Set horaSalida
     *
     * @param \DateTime $horaSalida
     *
     * @return RhuHorario
     */
    public function setHoraSalida($horaSalida)
    {
        $this->horaSalida = $horaSalida;

        return $this;
    }

    /**
     * Get horaSalida
     *
     * @return \DateTime
     */
    public function getHoraSalida()
    {
        return $this->horaSalida;
    }

    /**
     * Set generaHoraExtra
     *
     * @param boolean $generaHoraExtra
     *
     * @return RhuHorario
     */
    public function setGeneraHoraExtra($generaHoraExtra)
    {
        $this->generaHoraExtra = $generaHoraExtra;

        return $this;
    }

    /**
     * Get generaHoraExtra
     *
     * @return boolean
     */
    public function getGeneraHoraExtra()
    {
        return $this->generaHoraExtra;
    }

    /**
     * Set lunes
     *
     * @param string $lunes
     *
     * @return RhuHorario
     */
    public function setLunes($lunes)
    {
        $this->lunes = $lunes;

        return $this;
    }

    /**
     * Get lunes
     *
     * @return string
     */
    public function getLunes()
    {
        return $this->lunes;
    }

    /**
     * Set martes
     *
     * @param string $martes
     *
     * @return RhuHorario
     */
    public function setMartes($martes)
    {
        $this->martes = $martes;

        return $this;
    }

    /**
     * Get martes
     *
     * @return string
     */
    public function getMartes()
    {
        return $this->martes;
    }

    /**
     * Set miercoles
     *
     * @param string $miercoles
     *
     * @return RhuHorario
     */
    public function setMiercoles($miercoles)
    {
        $this->miercoles = $miercoles;

        return $this;
    }

    /**
     * Get miercoles
     *
     * @return string
     */
    public function getMiercoles()
    {
        return $this->miercoles;
    }

    /**
     * Set jueves
     *
     * @param string $jueves
     *
     * @return RhuHorario
     */
    public function setJueves($jueves)
    {
        $this->jueves = $jueves;

        return $this;
    }

    /**
     * Get jueves
     *
     * @return string
     */
    public function getJueves()
    {
        return $this->jueves;
    }

    /**
     * Set viernes
     *
     * @param string $viernes
     *
     * @return RhuHorario
     */
    public function setViernes($viernes)
    {
        $this->viernes = $viernes;

        return $this;
    }

    /**
     * Get viernes
     *
     * @return string
     */
    public function getViernes()
    {
        return $this->viernes;
    }

    /**
     * Set sabado
     *
     * @param string $sabado
     *
     * @return RhuHorario
     */
    public function setSabado($sabado)
    {
        $this->sabado = $sabado;

        return $this;
    }

    /**
     * Get sabado
     *
     * @return string
     */
    public function getSabado()
    {
        return $this->sabado;
    }

    /**
     * Set domingo
     *
     * @param string $domingo
     *
     * @return RhuHorario
     */
    public function setDomingo($domingo)
    {
        $this->domingo = $domingo;

        return $this;
    }

    /**
     * Get domingo
     *
     * @return string
     */
    public function getDomingo()
    {
        return $this->domingo;
    }

    /**
     * Set festivo
     *
     * @param string $festivo
     *
     * @return RhuHorario
     */
    public function setFestivo($festivo)
    {
        $this->festivo = $festivo;

        return $this;
    }

    /**
     * Get festivo
     *
     * @return string
     */
    public function getFestivo()
    {
        return $this->festivo;
    }

    /**
     * Add empleadosHorarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosHorarioRel
     *
     * @return RhuHorario
     */
    public function addEmpleadosHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosHorarioRel)
    {
        $this->empleadosHorarioRel[] = $empleadosHorarioRel;

        return $this;
    }

    /**
     * Remove empleadosHorarioRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosHorarioRel
     */
    public function removeEmpleadosHorarioRel(\Brasa\RecursoHumanoBundle\Entity\RhuEmpleado $empleadosHorarioRel)
    {
        $this->empleadosHorarioRel->removeElement($empleadosHorarioRel);
    }

    /**
     * Get empleadosHorarioRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmpleadosHorarioRel()
    {
        return $this->empleadosHorarioRel;
    }

    /**
     * Set controlHorario
     *
     * @param boolean $controlHorario
     *
     * @return RhuHorario
     */
    public function setControlHorario($controlHorario)
    {
        $this->controlHorario = $controlHorario;

        return $this;
    }

    /**
     * Get controlHorario
     *
     * @return boolean
     */
    public function getControlHorario()
    {
        return $this->controlHorario;
    }
}
