<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_tarea")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenTareaRepository")
 */
class GenTarea
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_tarea_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoTareaPk;                    
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;             

    /**
     * @ORM\Column(name="fecha_programada", type="datetime", nullable=true)
     */    
    private $fechaProgramada;    
    
    /**
     * @ORM\Column(name="hora", type="time", nullable=false)
     */    
    private $hora;    

    /**
     * @ORM\Column(name="fecha_termina", type="datetime", nullable=true)
     */    
    private $fechaTermina;    
    
    /**
     * @ORM\Column(name="fecha_anula", type="datetime", nullable=true)
     */    
    private $fechaAnula;    
    
    /**
     * @ORM\Column(name="asunto", type="string", length=80, nullable=true)
     */    
    private $asunto;                   
    
    /**
     * @ORM\Column(name="estado_terminado", type="boolean")
     */    
    private $estadoTerminado = false;  
    
    /**
     * @ORM\Column(name="estado_anulado", type="boolean")
     */    
    private $estadoAnulado = false;     
    
    /**
     * @ORM\Column(name="usuario_tarea_fk", type="string", length=50, nullable=true)
     */    
    private $usuarioTareaFk;    
    
    /**
     * @ORM\Column(name="usuario_crea_fk", type="string", length=50, nullable=true)
     */    
    private $usuarioCreaFk;    
    
    /**
     * @ORM\Column(name="usuario_termina_fk", type="string", length=50, nullable=true)
     */    
    private $usuarioTerminaFk;    

    /**
     * @ORM\Column(name="usuario_anula_fk", type="string", length=50, nullable=true)
     */    
    private $usuarioAnulaFk;    
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;           



    /**
     * Get codigoTareaPk
     *
     * @return integer
     */
    public function getCodigoTareaPk()
    {
        return $this->codigoTareaPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return GenTarea
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaProgramada
     *
     * @param \DateTime $fechaProgramada
     *
     * @return GenTarea
     */
    public function setFechaProgramada($fechaProgramada)
    {
        $this->fechaProgramada = $fechaProgramada;

        return $this;
    }

    /**
     * Get fechaProgramada
     *
     * @return \DateTime
     */
    public function getFechaProgramada()
    {
        return $this->fechaProgramada;
    }

    /**
     * Set hora
     *
     * @param \DateTime $hora
     *
     * @return GenTarea
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get hora
     *
     * @return \DateTime
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set fechaTermina
     *
     * @param \DateTime $fechaTermina
     *
     * @return GenTarea
     */
    public function setFechaTermina($fechaTermina)
    {
        $this->fechaTermina = $fechaTermina;

        return $this;
    }

    /**
     * Get fechaTermina
     *
     * @return \DateTime
     */
    public function getFechaTermina()
    {
        return $this->fechaTermina;
    }

    /**
     * Set fechaAnula
     *
     * @param \DateTime $fechaAnula
     *
     * @return GenTarea
     */
    public function setFechaAnula($fechaAnula)
    {
        $this->fechaAnula = $fechaAnula;

        return $this;
    }

    /**
     * Get fechaAnula
     *
     * @return \DateTime
     */
    public function getFechaAnula()
    {
        return $this->fechaAnula;
    }

    /**
     * Set asunto
     *
     * @param string $asunto
     *
     * @return GenTarea
     */
    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;

        return $this;
    }

    /**
     * Get asunto
     *
     * @return string
     */
    public function getAsunto()
    {
        return $this->asunto;
    }

    /**
     * Set estadoTerminado
     *
     * @param boolean $estadoTerminado
     *
     * @return GenTarea
     */
    public function setEstadoTerminado($estadoTerminado)
    {
        $this->estadoTerminado = $estadoTerminado;

        return $this;
    }

    /**
     * Get estadoTerminado
     *
     * @return boolean
     */
    public function getEstadoTerminado()
    {
        return $this->estadoTerminado;
    }

    /**
     * Set estadoAnulado
     *
     * @param boolean $estadoAnulado
     *
     * @return GenTarea
     */
    public function setEstadoAnulado($estadoAnulado)
    {
        $this->estadoAnulado = $estadoAnulado;

        return $this;
    }

    /**
     * Get estadoAnulado
     *
     * @return boolean
     */
    public function getEstadoAnulado()
    {
        return $this->estadoAnulado;
    }

    /**
     * Set usuarioTareaFk
     *
     * @param string $usuarioTareaFk
     *
     * @return GenTarea
     */
    public function setUsuarioTareaFk($usuarioTareaFk)
    {
        $this->usuarioTareaFk = $usuarioTareaFk;

        return $this;
    }

    /**
     * Get usuarioTareaFk
     *
     * @return string
     */
    public function getUsuarioTareaFk()
    {
        return $this->usuarioTareaFk;
    }

    /**
     * Set usuarioCreaFk
     *
     * @param string $usuarioCreaFk
     *
     * @return GenTarea
     */
    public function setUsuarioCreaFk($usuarioCreaFk)
    {
        $this->usuarioCreaFk = $usuarioCreaFk;

        return $this;
    }

    /**
     * Get usuarioCreaFk
     *
     * @return string
     */
    public function getUsuarioCreaFk()
    {
        return $this->usuarioCreaFk;
    }

    /**
     * Set usuarioTerminaFk
     *
     * @param string $usuarioTerminaFk
     *
     * @return GenTarea
     */
    public function setUsuarioTerminaFk($usuarioTerminaFk)
    {
        $this->usuarioTerminaFk = $usuarioTerminaFk;

        return $this;
    }

    /**
     * Get usuarioTerminaFk
     *
     * @return string
     */
    public function getUsuarioTerminaFk()
    {
        return $this->usuarioTerminaFk;
    }

    /**
     * Set usuarioAnulaFk
     *
     * @param string $usuarioAnulaFk
     *
     * @return GenTarea
     */
    public function setUsuarioAnulaFk($usuarioAnulaFk)
    {
        $this->usuarioAnulaFk = $usuarioAnulaFk;

        return $this;
    }

    /**
     * Get usuarioAnulaFk
     *
     * @return string
     */
    public function getUsuarioAnulaFk()
    {
        return $this->usuarioAnulaFk;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return GenTarea
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
}
