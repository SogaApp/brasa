<?php

namespace Brasa\InventarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Brasa\InventarioBundle\Entity\UnidadesMedida
 *
 * @ORM\Table(name="inv_unidad_medida")
 * @ORM\Entity(repositoryClass="Brasa\InventarioBundle\Repository\InvUnidadMedidaRepository")
 */
class InvUnidadMedida
{
    /**
     * @var integer $codigo_unidad_medida_pk
     * @ORM\Id
     * @ORM\Column(name="codigo_unidad_medida_pk", type="string", length=25)
     */
    private $codigoUnidadMedidaPk;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=50)
     */
    private $nombre;



    /**
     * Set codigoUnidadMedidaPk
     *
     * @param string $codigoUnidadMedidaPk
     *
     * @return InvUnidadMedida
     */
    public function setCodigoUnidadMedidaPk($codigoUnidadMedidaPk)
    {
        $this->codigoUnidadMedidaPk = $codigoUnidadMedidaPk;

        return $this;
    }

    /**
     * Get codigoUnidadMedidaPk
     *
     * @return string
     */
    public function getCodigoUnidadMedidaPk()
    {
        return $this->codigoUnidadMedidaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return InvUnidadMedida
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
}
