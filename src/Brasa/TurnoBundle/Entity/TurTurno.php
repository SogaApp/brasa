<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_turno")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurTurnoRepository")
 */
class TurTurno
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
     * @ORM\Column(name="horas_nomina", type="float")
     */    
    private $horasNomina = 0;    
    
    /**
     * @ORM\Column(name="horas_diurnas", type="float")
     */    
    private $horasDiurnas = 0;     
    
    /**
     * @ORM\Column(name="horas_nocturnas", type="float")
     */    
    private $horasNocturnas = 0;            
    
    /**
     * @ORM\Column(name="novedad", type="boolean")
     */    
    private $novedad = false;     

    /**
     * @ORM\Column(name="descanso", type="boolean")
     */    
    private $descanso = false;           
    
    /**
     * @ORM\Column(name="incapacidad", type="boolean")
     */    
    private $incapacidad = false;    
    
    /**
     * @ORM\Column(name="licencia", type="boolean")
     */    
    private $licencia = false;    

    /**
     * @ORM\Column(name="licencia_no_remunerada", type="boolean")
     */    
    private $licenciaNoRemunerada = false;
    
    /**
     * @ORM\Column(name="vacacion", type="boolean")
     */    
    private $vacacion = false;    

    /**
     * @ORM\Column(name="ingreso", type="boolean")
     */    
    private $ingreso = false;
    
    /**
     * @ORM\Column(name="retiro", type="boolean")
     */    
    private $retiro = false;    
    
    /**
     * @ORM\Column(name="induccion", type="boolean")
     */    
    private $induccion = false;    
    
    /**
     * @ORM\Column(name="usuario", type="string", length=50, nullable=true)
     */    
    private $usuario;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;       

    /**
     * @ORM\Column(name="complementario", type="boolean")
     */    
    private $complementario = false;     
    
    /**
     * @ORM\OneToMany(targetEntity="TurTurnoDetalle", mappedBy="turnoRel", cascade={"persist", "remove"})
     */
    protected $turnosDetallesTurnoRel;            
    
    /**
     * @ORM\OneToMany(targetEntity="TurSoportePagoDetalle", mappedBy="turnoRel")
     */
    protected $soportesPagosDetallesTurnoRel;    
    
    /**
     * @ORM\OneToMany(targetEntity="TurNovedadTipo", mappedBy="turnoRel")
     */
    protected $novedadesTiposTurnoRel;     
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turnosDetallesTurnoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soportesPagosDetallesTurnoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set codigoTurnoPk
     *
     * @param string $codigoTurnoPk
     *
     * @return TurTurno
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
     * @return TurTurno
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
     * @return TurTurno
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
     * @return TurTurno
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
     * @param integer $horas
     *
     * @return TurTurno
     */
    public function setHoras($horas)
    {
        $this->horas = $horas;

        return $this;
    }

    /**
     * Get horas
     *
     * @return integer
     */
    public function getHoras()
    {
        return $this->horas;
    }

    /**
     * Set horasDiurnas
     *
     * @param integer $horasDiurnas
     *
     * @return TurTurno
     */
    public function setHorasDiurnas($horasDiurnas)
    {
        $this->horasDiurnas = $horasDiurnas;

        return $this;
    }

    /**
     * Get horasDiurnas
     *
     * @return integer
     */
    public function getHorasDiurnas()
    {
        return $this->horasDiurnas;
    }

    /**
     * Set horasNocturnas
     *
     * @param integer $horasNocturnas
     *
     * @return TurTurno
     */
    public function setHorasNocturnas($horasNocturnas)
    {
        $this->horasNocturnas = $horasNocturnas;

        return $this;
    }

    /**
     * Get horasNocturnas
     *
     * @return integer
     */
    public function getHorasNocturnas()
    {
        return $this->horasNocturnas;
    }

    /**
     * Set servicio
     *
     * @param boolean $servicio
     *
     * @return TurTurno
     */
    public function setServicio($servicio)
    {
        $this->servicio = $servicio;

        return $this;
    }

    /**
     * Get servicio
     *
     * @return boolean
     */
    public function getServicio()
    {
        return $this->servicio;
    }

    /**
     * Set programacion
     *
     * @param boolean $programacion
     *
     * @return TurTurno
     */
    public function setProgramacion($programacion)
    {
        $this->programacion = $programacion;

        return $this;
    }

    /**
     * Get programacion
     *
     * @return boolean
     */
    public function getProgramacion()
    {
        return $this->programacion;
    }

    /**
     * Set novedad
     *
     * @param boolean $novedad
     *
     * @return TurTurno
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
     * @return TurTurno
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TurTurno
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
     * Add turnosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel
     *
     * @return TurTurno
     */
    public function addTurnosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel)
    {
        $this->turnosDetallesTurnoRel[] = $turnosDetallesTurnoRel;

        return $this;
    }

    /**
     * Remove turnosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel
     */
    public function removeTurnosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurTurnoDetalle $turnosDetallesTurnoRel)
    {
        $this->turnosDetallesTurnoRel->removeElement($turnosDetallesTurnoRel);
    }

    /**
     * Get turnosDetallesTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurnosDetallesTurnoRel()
    {
        return $this->turnosDetallesTurnoRel;
    }

    /**
     * Add soportesPagosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel
     *
     * @return TurTurno
     */
    public function addSoportesPagosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel)
    {
        $this->soportesPagosDetallesTurnoRel[] = $soportesPagosDetallesTurnoRel;

        return $this;
    }

    /**
     * Remove soportesPagosDetallesTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel
     */
    public function removeSoportesPagosDetallesTurnoRel(\Brasa\TurnoBundle\Entity\TurSoportePagoDetalle $soportesPagosDetallesTurnoRel)
    {
        $this->soportesPagosDetallesTurnoRel->removeElement($soportesPagosDetallesTurnoRel);
    }

    /**
     * Get soportesPagosDetallesTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSoportesPagosDetallesTurnoRel()
    {
        return $this->soportesPagosDetallesTurnoRel;
    }

    /**
     * Set horasNomina
     *
     * @param float $horasNomina
     *
     * @return TurTurno
     */
    public function setHorasNomina($horasNomina)
    {
        $this->horasNomina = $horasNomina;

        return $this;
    }

    /**
     * Get horasNomina
     *
     * @return float
     */
    public function getHorasNomina()
    {
        return $this->horasNomina;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     *
     * @return TurTurno
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
     * Set incapacidad
     *
     * @param boolean $incapacidad
     *
     * @return TurTurno
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
     * @return TurTurno
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
     * @return TurTurno
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
     * Add novedadesTiposTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurNovedadTipo $novedadesTiposTurnoRel
     *
     * @return TurTurno
     */
    public function addNovedadesTiposTurnoRel(\Brasa\TurnoBundle\Entity\TurNovedadTipo $novedadesTiposTurnoRel)
    {
        $this->novedadesTiposTurnoRel[] = $novedadesTiposTurnoRel;

        return $this;
    }

    /**
     * Remove novedadesTiposTurnoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurNovedadTipo $novedadesTiposTurnoRel
     */
    public function removeNovedadesTiposTurnoRel(\Brasa\TurnoBundle\Entity\TurNovedadTipo $novedadesTiposTurnoRel)
    {
        $this->novedadesTiposTurnoRel->removeElement($novedadesTiposTurnoRel);
    }

    /**
     * Get novedadesTiposTurnoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNovedadesTiposTurnoRel()
    {
        return $this->novedadesTiposTurnoRel;
    }

    /**
     * Set ingreso
     *
     * @param boolean $ingreso
     *
     * @return TurTurno
     */
    public function setIngreso($ingreso)
    {
        $this->ingreso = $ingreso;

        return $this;
    }

    /**
     * Get ingreso
     *
     * @return boolean
     */
    public function getIngreso()
    {
        return $this->ingreso;
    }

    /**
     * Set retiro
     *
     * @param boolean $retiro
     *
     * @return TurTurno
     */
    public function setRetiro($retiro)
    {
        $this->retiro = $retiro;

        return $this;
    }

    /**
     * Get retiro
     *
     * @return boolean
     */
    public function getRetiro()
    {
        return $this->retiro;
    }

    /**
     * Set licenciaNoRemunerada
     *
     * @param boolean $licenciaNoRemunerada
     *
     * @return TurTurno
     */
    public function setLicenciaNoRemunerada($licenciaNoRemunerada)
    {
        $this->licenciaNoRemunerada = $licenciaNoRemunerada;

        return $this;
    }

    /**
     * Get licenciaNoRemunerada
     *
     * @return boolean
     */
    public function getLicenciaNoRemunerada()
    {
        return $this->licenciaNoRemunerada;
    }

    /**
     * Set complementario
     *
     * @param boolean $complementario
     *
     * @return TurTurno
     */
    public function setComplementario($complementario)
    {
        $this->complementario = $complementario;

        return $this;
    }

    /**
     * Get complementario
     *
     * @return boolean
     */
    public function getComplementario()
    {
        return $this->complementario;
    }

    /**
     * Set induccion
     *
     * @param boolean $induccion
     *
     * @return TurTurno
     */
    public function setInduccion($induccion)
    {
        $this->induccion = $induccion;

        return $this;
    }

    /**
     * Get induccion
     *
     * @return boolean
     */
    public function getInduccion()
    {
        return $this->induccion;
    }
}
