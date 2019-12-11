<?php
/**
 * Created by PhpStorm.
 * User: jako
 * Date: 9/03/18
 * Time: 10:51 AM
 */

namespace Brasa\GeneralBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_log_extendido")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenLogExtendidoRepository")
 */
class GenLogExtendido
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_log_extendido_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoLogExtendidoPk;

    /**
     * @ORM\Column(name="codigo_registro_pk", type="string", nullable=true)
     */
    private $codigoRegistroPk;

    /**
     * @ORM\Column(name="accion", type="string", length=20, nullable=true)
     */
    private $accion;

    /**
     * @ORM\Column(name="codigo_usuario_fk", type="integer")
     */
    private $codigoUsuarioFk;

    /**
     * @ORM\Column(name="campos_seguimiento", type="text", nullable=true)
     */
    private $camposSeguimiento;

    /**
     * @ORM\Column(name="campos_seguimiento_mostrar", type="text", nullable=true)
     */
    private $camposSeguimientoMostrar;

    /**
     * @ORM\Column(name="nombre_entidad", type="string", length=500, nullable=true)
     */
    private $nombreEntidad;

    /**
     * @ORM\Column(name="namespace_entidad", type="string", length=500, nullable=true)
     */
    private $namespaceEntidad;

    /**
     * @ORM\Column(name="modulo", type="string", length=200, nullable=true)
     */
    private $modulo;

    /**
     * @ORM\Column(name="ruta", type="text", nullable=true)
     */
    private $ruta;

    /**
     * @ORM\Column(name="codigo_padre", type="integer", nullable=true)
     */
    private $codigoPadre;

    /**
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity="Brasa\SeguridadBundle\Entity\User", inversedBy="logsUsuarioRel")
     * @ORM\JoinColumn(name="codigo_usuario_fk", referencedColumnName="id")
     */
    protected $usuarioRel;

    /**
     * Get codigoLogExtendidoPk.
     *
     * @return int
     */
    public function getCodigoLogExtendidoPk()
    {
        return $this->codigoLogExtendidoPk;
    }

    /**
     * Set codigoRegistroPk.
     *
     * @param string|null $codigoRegistroPk
     *
     * @return GenLogExtendido
     */
    public function setCodigoRegistroPk($codigoRegistroPk = null)
    {
        $this->codigoRegistroPk = $codigoRegistroPk;

        return $this;
    }

    /**
     * Get codigoRegistroPk.
     *
     * @return string|null
     */
    public function getCodigoRegistroPk()
    {
        return $this->codigoRegistroPk;
    }

    /**
     * Set accion.
     *
     * @param string|null $accion
     *
     * @return GenLogExtendido
     */
    public function setAccion($accion = null)
    {
        $this->accion = $accion;

        return $this;
    }

    /**
     * Get accion.
     *
     * @return string|null
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * Set codigoUsuarioFk.
     *
     * @param int $codigoUsuarioFk
     *
     * @return GenLogExtendido
     */
    public function setCodigoUsuarioFk($codigoUsuarioFk)
    {
        $this->codigoUsuarioFk = $codigoUsuarioFk;

        return $this;
    }

    /**
     * Get codigoUsuarioFk.
     *
     * @return int
     */
    public function getCodigoUsuarioFk()
    {
        return $this->codigoUsuarioFk;
    }

    /**
     * Set camposSeguimiento.
     *
     * @param string|null $camposSeguimiento
     *
     * @return GenLogExtendido
     */
    public function setCamposSeguimiento($camposSeguimiento = null)
    {
        $this->camposSeguimiento = $camposSeguimiento;

        return $this;
    }

    /**
     * Get camposSeguimiento.
     *
     * @return string|null
     */
    public function getCamposSeguimiento()
    {
        return $this->camposSeguimiento;
    }

    /**
     * Set camposSeguimientoMostrar.
     *
     * @param string|null $camposSeguimientoMostrar
     *
     * @return GenLogExtendido
     */
    public function setCamposSeguimientoMostrar($camposSeguimientoMostrar = null)
    {
        $this->camposSeguimientoMostrar = $camposSeguimientoMostrar;

        return $this;
    }

    /**
     * Get camposSeguimientoMostrar.
     *
     * @return string|null
     */
    public function getCamposSeguimientoMostrar()
    {
        return $this->camposSeguimientoMostrar;
    }

    /**
     * Set nombreEntidad.
     *
     * @param string|null $nombreEntidad
     *
     * @return GenLogExtendido
     */
    public function setNombreEntidad($nombreEntidad = null)
    {
        $this->nombreEntidad = $nombreEntidad;

        return $this;
    }

    /**
     * Get nombreEntidad.
     *
     * @return string|null
     */
    public function getNombreEntidad()
    {
        return $this->nombreEntidad;
    }

    /**
     * Set namespaceEntidad.
     *
     * @param string|null $namespaceEntidad
     *
     * @return GenLogExtendido
     */
    public function setNamespaceEntidad($namespaceEntidad = null)
    {
        $this->namespaceEntidad = $namespaceEntidad;

        return $this;
    }

    /**
     * Get namespaceEntidad.
     *
     * @return string|null
     */
    public function getNamespaceEntidad()
    {
        return $this->namespaceEntidad;
    }

    /**
     * Set modulo.
     *
     * @param string|null $modulo
     *
     * @return GenLogExtendido
     */
    public function setModulo($modulo = null)
    {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get modulo.
     *
     * @return string|null
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * Set ruta.
     *
     * @param string|null $ruta
     *
     * @return GenLogExtendido
     */
    public function setRuta($ruta = null)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get ruta.
     *
     * @return string|null
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set codigoPadre.
     *
     * @param int|null $codigoPadre
     *
     * @return GenLogExtendido
     */
    public function setCodigoPadre($codigoPadre = null)
    {
        $this->codigoPadre = $codigoPadre;

        return $this;
    }

    /**
     * Get codigoPadre.
     *
     * @return int|null
     */
    public function getCodigoPadre()
    {
        return $this->codigoPadre;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return GenLogExtendido
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuarioRel.
     *
     * @param \Brasa\SeguridadBundle\Entity\User|null $usuarioRel
     *
     * @return GenLogExtendido
     */
    public function setUsuarioRel(\Brasa\SeguridadBundle\Entity\User $usuarioRel = null)
    {
        $this->usuarioRel = $usuarioRel;

        return $this;
    }

    /**
     * Get usuarioRel.
     *
     * @return \Brasa\SeguridadBundle\Entity\User|null
     */
    public function getUsuarioRel()
    {
        return $this->usuarioRel;
    }
}
