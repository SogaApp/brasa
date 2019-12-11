<?php

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_evento")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenEventoRepository")
 */
class GenEvento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_evento_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoEventoPk;                    
    
    /**
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */    
    private $fecha;             

    /**
     * @ORM\Column(name="hora", type="time", nullable=false)
     */    
    private $hora;    

    /**
     * @ORM\Column(name="asunto", type="string", length=80, nullable=true)
     */    
    private $asunto;               
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;           


    /**
     * Get codigoEventoPk
     *
     * @return integer
     */
    public function getCodigoEventoPk()
    {
        return $this->codigoEventoPk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return GenEvento
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
     * Set hora
     *
     * @param \DateTime $hora
     *
     * @return GenEvento
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
     * Set asunto
     *
     * @param string $asunto
     *
     * @return GenEvento
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
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return GenEvento
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
