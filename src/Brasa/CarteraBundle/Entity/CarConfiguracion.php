<?php

namespace Brasa\CarteraBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="car_configuracion")
 * @ORM\Entity(repositoryClass="Brasa\CarteraBundle\Repository\CarConfiguracionRepository")
 */
class CarConfiguracion
{
     /**
     * @ORM\Id
     * @ORM\Column(name="codigo_configuracion_pk", type="integer")
     */
    private $codigoConfiguracionPk;
    
    /**
     * @ORM\Column(name="codigo_formato_resumen_recibo", type="integer")
     */    
    private $codigoFormatoResumenRecibo = 0;
    
    /**
     * @ORM\Column(name="codigo_formato_resumen_anticipo", type="integer")
     */    
    private $codigoFormatoResumenAnticipo = 0;
        
    

    

    /**
     * Set codigoConfiguracionPk
     *
     * @param integer $codigoConfiguracionPk
     *
     * @return CarConfiguracion
     */
    public function setCodigoConfiguracionPk($codigoConfiguracionPk)
    {
        $this->codigoConfiguracionPk = $codigoConfiguracionPk;

        return $this;
    }

    /**
     * Get codigoConfiguracionPk
     *
     * @return integer
     */
    public function getCodigoConfiguracionPk()
    {
        return $this->codigoConfiguracionPk;
    }

    /**
     * Set codigoFormatoResumenRecibo
     *
     * @param integer $codigoFormatoResumenRecibo
     *
     * @return CarConfiguracion
     */
    public function setCodigoFormatoResumenRecibo($codigoFormatoResumenRecibo)
    {
        $this->codigoFormatoResumenRecibo = $codigoFormatoResumenRecibo;

        return $this;
    }

    /**
     * Get codigoFormatoResumenRecibo
     *
     * @return integer
     */
    public function getCodigoFormatoResumenRecibo()
    {
        return $this->codigoFormatoResumenRecibo;
    }

    /**
     * Set codigoFormatoResumenAnticipo
     *
     * @param integer $codigoFormatoResumenAnticipo
     *
     * @return CarConfiguracion
     */
    public function setCodigoFormatoResumenAnticipo($codigoFormatoResumenAnticipo)
    {
        $this->codigoFormatoResumenAnticipo = $codigoFormatoResumenAnticipo;

        return $this;
    }

    /**
     * Get codigoFormatoResumenAnticipo
     *
     * @return integer
     */
    public function getCodigoFormatoResumenAnticipo()
    {
        return $this->codigoFormatoResumenAnticipo;
    }
}
