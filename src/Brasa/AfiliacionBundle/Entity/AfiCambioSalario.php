<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_cambio_salario")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiCambioSalarioRepository")
 */
class AfiCambioSalario
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_cambio_salario_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCambioSalarioPk;

    /**
     * @ORM\Column(name="codigo_contrato_fk", type="integer")
     */
    private $codigoContratoFk;

    /**
     * @ORM\Column(name="codigo_empleado_fk", type="integer")
     */
    private $codigoEmpleadoFk;

    /**
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\Column(name="vr_salario_anterior", type="float")
     */
    private $VrSalarioAnterior = 0;

    /**
     * @ORM\Column(name="vr_salario_nuevo", type="float")
     */
    private $VrSalarioNuevo = 0;

    /**
     * @ORM\Column(name="detalle", type="string", length=250, nullable=true)
     */
    private $detalle;

    /**
     * @ORM\Column(name="codigo_usuario", type="string", length=50, nullable=true)
     */
    private $codigoUsuario;

    /**
     * @ORM\ManyToOne(targetEntity="AfiEmpleado", inversedBy="cambiosSalariosEmpleadoRel")
     * @ORM\JoinColumn(name="codigo_empleado_fk", referencedColumnName="codigo_empleado_pk")
     */
    protected $empleadoRel;

    /**
     * @ORM\ManyToOne(targetEntity="AfiContrato", inversedBy="cambiosSalariosContratoRel")
     * @ORM\JoinColumn(name="codigo_contrato_fk", referencedColumnName="codigo_contrato_pk")
     */
    protected $contratoRel;


    /**
     * Get codigoCambioSalarioPk
     *
     * @return integer
     */
    public function getCodigoCambioSalarioPk()
    {
        return $this->codigoCambioSalarioPk;
    }

    /**
     * Set codigoContratoFk
     *
     * @param integer $codigoContratoFk
     *
     * @return AfiCambioSalario
     */
    public function setCodigoContratoFk($codigoContratoFk)
    {
        $this->codigoContratoFk = $codigoContratoFk;

        return $this;
    }

    /**
     * Get codigoContratoFk
     *
     * @return integer
     */
    public function getCodigoContratoFk()
    {
        return $this->codigoContratoFk;
    }

    /**
     * Set codigoEmpleadoFk
     *
     * @param integer $codigoEmpleadoFk
     *
     * @return AfiCambioSalario
     */
    public function setCodigoEmpleadoFk($codigoEmpleadoFk)
    {
        $this->codigoEmpleadoFk = $codigoEmpleadoFk;

        return $this;
    }

    /**
     * Get codigoEmpleadoFk
     *
     * @return integer
     */
    public function getCodigoEmpleadoFk()
    {
        return $this->codigoEmpleadoFk;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AfiCambioSalario
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
     * Set vrSalarioAnterior
     *
     * @param float $vrSalarioAnterior
     *
     * @return AfiCambioSalario
     */
    public function setVrSalarioAnterior($vrSalarioAnterior)
    {
        $this->VrSalarioAnterior = $vrSalarioAnterior;

        return $this;
    }

    /**
     * Get vrSalarioAnterior
     *
     * @return float
     */
    public function getVrSalarioAnterior()
    {
        return $this->VrSalarioAnterior;
    }

    /**
     * Set vrSalarioNuevo
     *
     * @param float $vrSalarioNuevo
     *
     * @return AfiCambioSalario
     */
    public function setVrSalarioNuevo($vrSalarioNuevo)
    {
        $this->VrSalarioNuevo = $vrSalarioNuevo;

        return $this;
    }

    /**
     * Get vrSalarioNuevo
     *
     * @return float
     */
    public function getVrSalarioNuevo()
    {
        return $this->VrSalarioNuevo;
    }

    /**
     * Set detalle
     *
     * @param string $detalle
     *
     * @return AfiCambioSalario
     */
    public function setDetalle($detalle)
    {
        $this->detalle = $detalle;

        return $this;
    }

    /**
     * Get detalle
     *
     * @return string
     */
    public function getDetalle()
    {
        return $this->detalle;
    }

    /**
     * Set codigoUsuario
     *
     * @param string $codigoUsuario
     *
     * @return AfiCambioSalario
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
     * Set empleadoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel
     *
     * @return AfiCambioSalario
     */
    public function setEmpleadoRel(\Brasa\AfiliacionBundle\Entity\AfiEmpleado $empleadoRel = null)
    {
        $this->empleadoRel = $empleadoRel;

        return $this;
    }

    /**
     * Get empleadoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiEmpleado
     */
    public function getEmpleadoRel()
    {
        return $this->empleadoRel;
    }

    /**
     * Set contratoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel
     *
     * @return AfiCambioSalario
     */
    public function setContratoRel(\Brasa\AfiliacionBundle\Entity\AfiContrato $contratoRel = null)
    {
        $this->contratoRel = $contratoRel;

        return $this;
    }

    /**
     * Get contratoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiContrato
     */
    public function getContratoRel()
    {
        return $this->contratoRel;
    }
}
