<?php

namespace Brasa\TransporteBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tte_recogida")
 * @ORM\Entity(repositoryClass="Brasa\TransporteBundle\Repository\TteRecogidaRepository")
 */
class TteRecogida
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recogida_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecogidaPk;      
    
    /**
     * @ORM\Column(name="fecha_anuncio", type="datetime", nullable=true)
     */    
    private $fechaAnuncio;            

    /**
     * @ORM\Column(name="fecha_recogida", type="datetime", nullable=true)
     */    
    private $fechaRecogida;     
    
    /**
     * @ORM\Column(name="codigo_cliente_fk", type="integer", nullable=true)
     */    
    private $codigoClienteFk;          
    
    /**
     * @ORM\Column(name="codigo_punto_operacion_fk", type="integer", nullable=true)
     */    
    private $codigoPuntoOperacionFk;     

    /**
     * @ORM\Column(name="codigo_programacion_recogida_fk", type="integer", nullable=true)
     */    
    private $codigoProgramacionRecogidaFk;     
    
    /**
     * @ORM\Column(name="unidades", type="integer")
     */
    private $unidades = 0;

    /**
     * @ORM\Column(name="peso_real", type="integer")
     */
    private $pesoReal = 0;    

    /**
     * @ORM\Column(name="peso_volumen", type="integer")
     */
    private $pesoVolumen = 0;    
    
    /**
     * @ORM\Column(name="peso_liquidar", type="integer")
     */
    private $pesoLiquidar = 0;    

    /**
     * @ORM\Column(name="vr_declarado", type="float")
     */
    private $vrDeclarado = 0;               
    
    /**
     * @ORM\Column(name="estado_impreso", type="boolean")
     */    
    private $estadoImpresa = false;             

    /**
     * @ORM\Column(name="estado_anulada", type="boolean")
     */    
    private $estadoAnulada = false;                

    /**
     * @ORM\Column(name="estado_asignada", type="boolean")
     */    
    private $estadoAsignada = false;    
    
    /**
     * @ORM\Column(name="Anunciante", type="string", length=80, nullable=true)
     */    
    private $anunciante;     
    
    /**
     * @ORM\Column(name="direccion", type="string", length=80, nullable=true)
     */    
    private $direccion;    
    
    /**
     * @ORM\Column(name="telefono", type="string", length=25, nullable=true)
     */    
    private $telefono;    
    
    /**
     * @ORM\Column(name="contenido", type="string", length=500, nullable=true)
     */    
    private $contenido;     
    
    /**
     * @ORM\Column(name="comentarios", type="string", length=500, nullable=true)
     */    
    private $comentarios;    
        
    /**
     * @ORM\ManyToOne(targetEntity="TteCliente", inversedBy="recogidasClienteRel")
     * @ORM\JoinColumn(name="codigo_cliente_fk", referencedColumnName="codigo_cliente_pk")
     */
    protected $clienteRel;      
    
    /**
     * @ORM\ManyToOne(targetEntity="TtePuntoOperacion", inversedBy="recogidasPuntoOperacionRel")
     * @ORM\JoinColumn(name="codigo_punto_operacion_fk", referencedColumnName="codigo_punto_operacion_pk")
     */
    protected $puntoOperacionRel;     
     
    /**
     * @ORM\ManyToOne(targetEntity="TteProgramacionRecogida", inversedBy="recogidasProgramacionRecogidaRel")
     * @ORM\JoinColumn(name="codigo_programacion_recogida_fk", referencedColumnName="codigo_programacion_recogida_pk")
     */
    protected $programacionRecogidaRel;     



    /**
     * Get codigoRecogidaPk
     *
     * @return integer
     */
    public function getCodigoRecogidaPk()
    {
        return $this->codigoRecogidaPk;
    }

    /**
     * Set fechaAnuncio
     *
     * @param \DateTime $fechaAnuncio
     *
     * @return TteRecogida
     */
    public function setFechaAnuncio($fechaAnuncio)
    {
        $this->fechaAnuncio = $fechaAnuncio;

        return $this;
    }

    /**
     * Get fechaAnuncio
     *
     * @return \DateTime
     */
    public function getFechaAnuncio()
    {
        return $this->fechaAnuncio;
    }

    /**
     * Set fechaRecogida
     *
     * @param \DateTime $fechaRecogida
     *
     * @return TteRecogida
     */
    public function setFechaRecogida($fechaRecogida)
    {
        $this->fechaRecogida = $fechaRecogida;

        return $this;
    }

    /**
     * Get fechaRecogida
     *
     * @return \DateTime
     */
    public function getFechaRecogida()
    {
        return $this->fechaRecogida;
    }

    /**
     * Set codigoClienteFk
     *
     * @param integer $codigoClienteFk
     *
     * @return TteRecogida
     */
    public function setCodigoClienteFk($codigoClienteFk)
    {
        $this->codigoClienteFk = $codigoClienteFk;

        return $this;
    }

    /**
     * Get codigoClienteFk
     *
     * @return integer
     */
    public function getCodigoClienteFk()
    {
        return $this->codigoClienteFk;
    }

    /**
     * Set codigoPuntoOperacionFk
     *
     * @param integer $codigoPuntoOperacionFk
     *
     * @return TteRecogida
     */
    public function setCodigoPuntoOperacionFk($codigoPuntoOperacionFk)
    {
        $this->codigoPuntoOperacionFk = $codigoPuntoOperacionFk;

        return $this;
    }

    /**
     * Get codigoPuntoOperacionFk
     *
     * @return integer
     */
    public function getCodigoPuntoOperacionFk()
    {
        return $this->codigoPuntoOperacionFk;
    }

    /**
     * Set codigoProgramacionRecogidaFk
     *
     * @param integer $codigoProgramacionRecogidaFk
     *
     * @return TteRecogida
     */
    public function setCodigoProgramacionRecogidaFk($codigoProgramacionRecogidaFk)
    {
        $this->codigoProgramacionRecogidaFk = $codigoProgramacionRecogidaFk;

        return $this;
    }

    /**
     * Get codigoProgramacionRecogidaFk
     *
     * @return integer
     */
    public function getCodigoProgramacionRecogidaFk()
    {
        return $this->codigoProgramacionRecogidaFk;
    }

    /**
     * Set unidades
     *
     * @param integer $unidades
     *
     * @return TteRecogida
     */
    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;

        return $this;
    }

    /**
     * Get unidades
     *
     * @return integer
     */
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * Set pesoReal
     *
     * @param integer $pesoReal
     *
     * @return TteRecogida
     */
    public function setPesoReal($pesoReal)
    {
        $this->pesoReal = $pesoReal;

        return $this;
    }

    /**
     * Get pesoReal
     *
     * @return integer
     */
    public function getPesoReal()
    {
        return $this->pesoReal;
    }

    /**
     * Set pesoVolumen
     *
     * @param integer $pesoVolumen
     *
     * @return TteRecogida
     */
    public function setPesoVolumen($pesoVolumen)
    {
        $this->pesoVolumen = $pesoVolumen;

        return $this;
    }

    /**
     * Get pesoVolumen
     *
     * @return integer
     */
    public function getPesoVolumen()
    {
        return $this->pesoVolumen;
    }

    /**
     * Set pesoLiquidar
     *
     * @param integer $pesoLiquidar
     *
     * @return TteRecogida
     */
    public function setPesoLiquidar($pesoLiquidar)
    {
        $this->pesoLiquidar = $pesoLiquidar;

        return $this;
    }

    /**
     * Get pesoLiquidar
     *
     * @return integer
     */
    public function getPesoLiquidar()
    {
        return $this->pesoLiquidar;
    }

    /**
     * Set vrDeclarado
     *
     * @param float $vrDeclarado
     *
     * @return TteRecogida
     */
    public function setVrDeclarado($vrDeclarado)
    {
        $this->vrDeclarado = $vrDeclarado;

        return $this;
    }

    /**
     * Get vrDeclarado
     *
     * @return float
     */
    public function getVrDeclarado()
    {
        return $this->vrDeclarado;
    }

    /**
     * Set estadoImpresa
     *
     * @param boolean $estadoImpresa
     *
     * @return TteRecogida
     */
    public function setEstadoImpresa($estadoImpresa)
    {
        $this->estadoImpresa = $estadoImpresa;

        return $this;
    }

    /**
     * Get estadoImpresa
     *
     * @return boolean
     */
    public function getEstadoImpresa()
    {
        return $this->estadoImpresa;
    }

    /**
     * Set estadoAnulada
     *
     * @param boolean $estadoAnulada
     *
     * @return TteRecogida
     */
    public function setEstadoAnulada($estadoAnulada)
    {
        $this->estadoAnulada = $estadoAnulada;

        return $this;
    }

    /**
     * Get estadoAnulada
     *
     * @return boolean
     */
    public function getEstadoAnulada()
    {
        return $this->estadoAnulada;
    }

    /**
     * Set estadoAsignada
     *
     * @param boolean $estadoAsignada
     *
     * @return TteRecogida
     */
    public function setEstadoAsignada($estadoAsignada)
    {
        $this->estadoAsignada = $estadoAsignada;

        return $this;
    }

    /**
     * Get estadoAsignada
     *
     * @return boolean
     */
    public function getEstadoAsignada()
    {
        return $this->estadoAsignada;
    }

    /**
     * Set anunciante
     *
     * @param string $anunciante
     *
     * @return TteRecogida
     */
    public function setAnunciante($anunciante)
    {
        $this->anunciante = $anunciante;

        return $this;
    }

    /**
     * Get anunciante
     *
     * @return string
     */
    public function getAnunciante()
    {
        return $this->anunciante;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return TteRecogida
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return TteRecogida
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     *
     * @return TteRecogida
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return TteRecogida
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
     * Set clienteRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteCliente $clienteRel
     *
     * @return TteRecogida
     */
    public function setClienteRel(\Brasa\TransporteBundle\Entity\TteCliente $clienteRel = null)
    {
        $this->clienteRel = $clienteRel;

        return $this;
    }

    /**
     * Get clienteRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteCliente
     */
    public function getClienteRel()
    {
        return $this->clienteRel;
    }

    /**
     * Set puntoOperacionRel
     *
     * @param \Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel
     *
     * @return TteRecogida
     */
    public function setPuntoOperacionRel(\Brasa\TransporteBundle\Entity\TtePuntoOperacion $puntoOperacionRel = null)
    {
        $this->puntoOperacionRel = $puntoOperacionRel;

        return $this;
    }

    /**
     * Get puntoOperacionRel
     *
     * @return \Brasa\TransporteBundle\Entity\TtePuntoOperacion
     */
    public function getPuntoOperacionRel()
    {
        return $this->puntoOperacionRel;
    }

    /**
     * Set programacionRecogidaRel
     *
     * @param \Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionRecogidaRel
     *
     * @return TteRecogida
     */
    public function setProgramacionRecogidaRel(\Brasa\TransporteBundle\Entity\TteProgramacionRecogida $programacionRecogidaRel = null)
    {
        $this->programacionRecogidaRel = $programacionRecogidaRel;

        return $this;
    }

    /**
     * Get programacionRecogidaRel
     *
     * @return \Brasa\TransporteBundle\Entity\TteProgramacionRecogida
     */
    public function getProgramacionRecogidaRel()
    {
        return $this->programacionRecogidaRel;
    }
}
