<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion_provision")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionProvisionRepository")
 */
class RhuConfiguracionProvision
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_provision_pk", type="integer")
     */
    private $codigoConfiguracionProvisionPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaFk;     

    /**
     * @ORM\Column(name="tipo_cuenta", type="bigint")
     */     
    private $tipoCuenta = 1;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_operacion_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaOperacionFk;          
    
    /**
     * @ORM\Column(name="codigo_cuenta_comercial_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaComercialFk;    
    

    /**
     * Set codigoConfiguracionProvisionPk
     *
     * @param integer $codigoConfiguracionProvisionPk
     *
     * @return RhuConfiguracionProvision
     */
    public function setCodigoConfiguracionProvisionPk($codigoConfiguracionProvisionPk)
    {
        $this->codigoConfiguracionProvisionPk = $codigoConfiguracionProvisionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionProvisionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionProvisionPk()
    {
        return $this->codigoConfiguracionProvisionPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuConfiguracionProvision
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
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return RhuConfiguracionProvision
     */
    public function setCodigoCuentaFk($codigoCuentaFk)
    {
        $this->codigoCuentaFk = $codigoCuentaFk;

        return $this;
    }

    /**
     * Get codigoCuentaFk
     *
     * @return string
     */
    public function getCodigoCuentaFk()
    {
        return $this->codigoCuentaFk;
    }

    /**
     * Set tipoCuenta
     *
     * @param integer $tipoCuenta
     *
     * @return RhuConfiguracionProvision
     */
    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;

        return $this;
    }

    /**
     * Get tipoCuenta
     *
     * @return integer
     */
    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    /**
     * Set codigoCuentaOperacionFk
     *
     * @param string $codigoCuentaOperacionFk
     *
     * @return RhuConfiguracionProvision
     */
    public function setCodigoCuentaOperacionFk($codigoCuentaOperacionFk)
    {
        $this->codigoCuentaOperacionFk = $codigoCuentaOperacionFk;

        return $this;
    }

    /**
     * Get codigoCuentaOperacionFk
     *
     * @return string
     */
    public function getCodigoCuentaOperacionFk()
    {
        return $this->codigoCuentaOperacionFk;
    }

    /**
     * Set codigoCuentaComercialFk
     *
     * @param string $codigoCuentaComercialFk
     *
     * @return RhuConfiguracionProvision
     */
    public function setCodigoCuentaComercialFk($codigoCuentaComercialFk)
    {
        $this->codigoCuentaComercialFk = $codigoCuentaComercialFk;

        return $this;
    }

    /**
     * Get codigoCuentaComercialFk
     *
     * @return string
     */
    public function getCodigoCuentaComercialFk()
    {
        return $this->codigoCuentaComercialFk;
    }
}
