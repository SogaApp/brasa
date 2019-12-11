<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_registro_visita")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuRegistroVisitaRepository")
 */
class RhuRegistroVisita
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_registro_visita_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRegistroVisitaPk;                    
         
    /**
     * @ORM\Column(name="numero_identificacion", type="string", length=25, nullable=true)
     */    
    private $numeroIdentificacion;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=150, nullable=true)
     */    
    private $nombre;
    
    /**
     * @ORM\Column(name="codigo_departamento_empresa_fk", type="integer", nullable=true)
     */    
    private $codigoDepartamentoEmpresaFk;
    
    /**
     * @ORM\Column(name="fecha_entrada", type="datetime", nullable=true)
     */    
    private $fechaEntrada;
    
    /**
     * @ORM\Column(name="fecha_salida", type="datetime", nullable=true)
     */    
    private $fechaSalida;
    
    /**
     * @ORM\Column(name="motivo", type="string", length=250, nullable=true)
     */    
    private $motivo;
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=250, nullable=true)
     */    
    private $comentarios;
    
    /**
     * @ORM\Column(name="duracion_registro", type="string", length=15, nullable=true)
     */    
    private $duracionRegistro;
    
    /**
     * @ORM\Column(name="codigo_escarapela", type="string", length=15, nullable=true)
     */    
    private $codigoEscarapela;
    
    /**     
     * @ORM\Column(name="estado", type="boolean")
     */    
    private $estado = 0;
   
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuDepartamentoEmpresa", inversedBy="registroVisitaDepartamentoEmpresaRel")
     * @ORM\JoinColumn(name="codigo_departamento_empresa_fk", referencedColumnName="codigo_departamento_empresa_pk")
     */
    protected $departamentoEmpresaRel;

    

    /**
     * Get codigoRegistroVisitaPk
     *
     * @return integer
     */
    public function getCodigoRegistroVisitaPk()
    {
        return $this->codigoRegistroVisitaPk;
    }

    /**
     * Set numeroIdentificacion
     *
     * @param string $numeroIdentificacion
     *
     * @return RhuRegistroVisita
     */
    public function setNumeroIdentificacion($numeroIdentificacion)
    {
        $this->numeroIdentificacion = $numeroIdentificacion;

        return $this;
    }

    /**
     * Get numeroIdentificacion
     *
     * @return string
     */
    public function getNumeroIdentificacion()
    {
        return $this->numeroIdentificacion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuRegistroVisita
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
     * Set codigoDepartamentoEmpresaFk
     *
     * @param integer $codigoDepartamentoEmpresaFk
     *
     * @return RhuRegistroVisita
     */
    public function setCodigoDepartamentoEmpresaFk($codigoDepartamentoEmpresaFk)
    {
        $this->codigoDepartamentoEmpresaFk = $codigoDepartamentoEmpresaFk;

        return $this;
    }

    /**
     * Get codigoDepartamentoEmpresaFk
     *
     * @return integer
     */
    public function getCodigoDepartamentoEmpresaFk()
    {
        return $this->codigoDepartamentoEmpresaFk;
    }

    /**
     * Set fechaEntrada
     *
     * @param \DateTime $fechaEntrada
     *
     * @return RhuRegistroVisita
     */
    public function setFechaEntrada($fechaEntrada)
    {
        $this->fechaEntrada = $fechaEntrada;

        return $this;
    }

    /**
     * Get fechaEntrada
     *
     * @return \DateTime
     */
    public function getFechaEntrada()
    {
        return $this->fechaEntrada;
    }

    /**
     * Set fechaSalida
     *
     * @param \DateTime $fechaSalida
     *
     * @return RhuRegistroVisita
     */
    public function setFechaSalida($fechaSalida)
    {
        $this->fechaSalida = $fechaSalida;

        return $this;
    }

    /**
     * Get fechaSalida
     *
     * @return \DateTime
     */
    public function getFechaSalida()
    {
        return $this->fechaSalida;
    }

    /**
     * Set motivo
     *
     * @param string $motivo
     *
     * @return RhuRegistroVisita
     */
    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo
     *
     * @return string
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return RhuRegistroVisita
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
     * Set duracionRegistro
     *
     * @param string $duracionRegistro
     *
     * @return RhuRegistroVisita
     */
    public function setDuracionRegistro($duracionRegistro)
    {
        $this->duracionRegistro = $duracionRegistro;

        return $this;
    }

    /**
     * Get duracionRegistro
     *
     * @return string
     */
    public function getDuracionRegistro()
    {
        return $this->duracionRegistro;
    }

    /**
     * Set codigoEscarapela
     *
     * @param string $codigoEscarapela
     *
     * @return RhuRegistroVisita
     */
    public function setCodigoEscarapela($codigoEscarapela)
    {
        $this->codigoEscarapela = $codigoEscarapela;

        return $this;
    }

    /**
     * Get codigoEscarapela
     *
     * @return string
     */
    public function getCodigoEscarapela()
    {
        return $this->codigoEscarapela;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     *
     * @return RhuRegistroVisita
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set departamentoEmpresaRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $departamentoEmpresaRel
     *
     * @return RhuRegistroVisita
     */
    public function setDepartamentoEmpresaRel(\Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa $departamentoEmpresaRel = null)
    {
        $this->departamentoEmpresaRel = $departamentoEmpresaRel;

        return $this;
    }

    /**
     * Get departamentoEmpresaRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuDepartamentoEmpresa
     */
    public function getDepartamentoEmpresaRel()
    {
        return $this->departamentoEmpresaRel;
    }
}
