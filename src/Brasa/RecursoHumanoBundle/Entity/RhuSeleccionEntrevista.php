<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_seleccion_entrevista")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuSeleccionEntrevistaRepository")
 */
class RhuSeleccionEntrevista
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_seleccion_entrevista_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoSeleccionEntrevistaPk;            
    
    /**
     * @ORM\Column(name="codigo_seleccion_fk", type="integer")
     */    
    private $codigoSeleccionFk;
    
    /**
     * @ORM\Column(name="codigo_seleccion_entrevista_tipo_fk", type="integer")
     */    
    private $codigoSeleccionEntrevistaTipoFk;
    
    /**
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */    
    private $fecha;
    
    /**
     * @ORM\Column(name="resultado", type="string", length=50, nullable=true)
     */    
    private $resultado;        
    
    /**
     * @ORM\Column(name="resultado_cuantitativo", type="integer", nullable=true)
     */    
    private $resultadoCuantitativo;
    
    /**
     * @ORM\Column(name="nombre_quien_entrevista", type="string", length=100, nullable=true)
     */    
    private $nombreQuienEntrevista;
       
    /**
     * @ORM\Column(name="comentarios", type="string", length=200, nullable=true)
     */    
    private $comentarios;  
    
    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */    
    private $codigoUsuario;
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccion", inversedBy="seleccionesEntrevistasSeleccionRel")
     * @ORM\JoinColumn(name="codigo_seleccion_fk", referencedColumnName="codigo_seleccion_pk")
     */
    protected $seleccionRel; 
    
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuSeleccionEntrevistaTipo", inversedBy="seleccionesEntrevistasSelecionEntrevistaTipoRel")
     * @ORM\JoinColumn(name="codigo_seleccion_entrevista_tipo_fk", referencedColumnName="codigo_seleccion_entrevista_tipo_pk")
     */
    protected $seleccionEntrevistaTipoRel;
    



    

    /**
     * Get codigoSeleccionEntrevistaPk
     *
     * @return integer
     */
    public function getCodigoSeleccionEntrevistaPk()
    {
        return $this->codigoSeleccionEntrevistaPk;
    }

    /**
     * Set codigoSeleccionFk
     *
     * @param integer $codigoSeleccionFk
     *
     * @return RhuSeleccionEntrevista
     */
    public function setCodigoSeleccionFk($codigoSeleccionFk)
    {
        $this->codigoSeleccionFk = $codigoSeleccionFk;

        return $this;
    }

    /**
     * Get codigoSeleccionFk
     *
     * @return integer
     */
    public function getCodigoSeleccionFk()
    {
        return $this->codigoSeleccionFk;
    }

    /**
     * Set codigoSeleccionEntrevistaTipoFk
     *
     * @param integer $codigoSeleccionEntrevistaTipoFk
     *
     * @return RhuSeleccionEntrevista
     */
    public function setCodigoSeleccionEntrevistaTipoFk($codigoSeleccionEntrevistaTipoFk)
    {
        $this->codigoSeleccionEntrevistaTipoFk = $codigoSeleccionEntrevistaTipoFk;

        return $this;
    }

    /**
     * Get codigoSeleccionEntrevistaTipoFk
     *
     * @return integer
     */
    public function getCodigoSeleccionEntrevistaTipoFk()
    {
        return $this->codigoSeleccionEntrevistaTipoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return RhuSeleccionEntrevista
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
     * Set resultado
     *
     * @param string $resultado
     *
     * @return RhuSeleccionEntrevista
     */
    public function setResultado($resultado)
    {
        $this->resultado = $resultado;

        return $this;
    }

    /**
     * Get resultado
     *
     * @return string
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * Set resultadoCuantitativo
     *
     * @param integer $resultadoCuantitativo
     *
     * @return RhuSeleccionEntrevista
     */
    public function setResultadoCuantitativo($resultadoCuantitativo)
    {
        $this->resultadoCuantitativo = $resultadoCuantitativo;

        return $this;
    }

    /**
     * Get resultadoCuantitativo
     *
     * @return integer
     */
    public function getResultadoCuantitativo()
    {
        return $this->resultadoCuantitativo;
    }

    /**
     * Set nombreQuienEntrevista
     *
     * @param string $nombreQuienEntrevista
     *
     * @return RhuSeleccionEntrevista
     */
    public function setNombreQuienEntrevista($nombreQuienEntrevista)
    {
        $this->nombreQuienEntrevista = $nombreQuienEntrevista;

        return $this;
    }

    /**
     * Get nombreQuienEntrevista
     *
     * @return string
     */
    public function getNombreQuienEntrevista()
    {
        return $this->nombreQuienEntrevista;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuSeleccionEntrevista
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
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return RhuSeleccionEntrevista
     */
    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;

        return $this;
    }

    /**
     * Get codigoUsuario
     *
     * @return string
     */
    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    /**
     * Set seleccionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel
     *
     * @return RhuSeleccionEntrevista
     */
    public function setSeleccionRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccion $seleccionRel = null)
    {
        $this->seleccionRel = $seleccionRel;

        return $this;
    }

    /**
     * Get seleccionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccion
     */
    public function getSeleccionRel()
    {
        return $this->seleccionRel;
    }

    /**
     * Set seleccionEntrevistaTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevistaTipo $seleccionEntrevistaTipoRel
     *
     * @return RhuSeleccionEntrevista
     */
    public function setSeleccionEntrevistaTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevistaTipo $seleccionEntrevistaTipoRel = null)
    {
        $this->seleccionEntrevistaTipoRel = $seleccionEntrevistaTipoRel;

        return $this;
    }

    /**
     * Get seleccionEntrevistaTipoRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuSeleccionEntrevistaTipo
     */
    public function getSeleccionEntrevistaTipoRel()
    {
        return $this->seleccionEntrevistaTipoRel;
    }
}
