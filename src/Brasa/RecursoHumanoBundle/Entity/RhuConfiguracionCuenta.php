<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_configuracion_cuenta")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuConfiguracionCuentaRepository")
 */
class RhuConfiguracionCuenta
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_cuenta_pk", type="integer")
     */
    private $codigoConfiguracionCuentaPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=80, nullable=true)
     */    
    private $nombre;     
    
    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaFk;     


    /**
     * Set codigoConfiguracionCuentaPk
     *
     * @param integer $codigoConfiguracionCuentaPk
     *
     * @return RhuConfiguracionCuenta
     */
    public function setCodigoConfiguracionCuentaPk($codigoConfiguracionCuentaPk)
    {
        $this->codigoConfiguracionCuentaPk = $codigoConfiguracionCuentaPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionCuentaPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionCuentaPk()
    {
        return $this->codigoConfiguracionCuentaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuConfiguracionCuenta
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
     * @return RhuConfiguracionCuenta
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
}
