<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_categoria")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurCategoriaRepository")
 */
class TurCategoria
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_categoria_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCategoriaPk;    
    
    /**
     * @ORM\Column(name="nombre", type="string", length=120, nullable=true)
     */    
    private $nombre;    
    
    /**
     * @ORM\Column(name="vr_salario", type="float")
     */
    private $vrSalario = 0;    

    /**
     * Get codigoCategoriaPk
     *
     * @return integer
     */
    public function getCodigoCategoriaPk()
    {
        return $this->codigoCategoriaPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return TurCategoria
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
     * Set vrSalario
     *
     * @param float $vrSalario
     *
     * @return TurCategoria
     */
    public function setVrSalario($vrSalario)
    {
        $this->vrSalario = $vrSalario;

        return $this;
    }

    /**
     * Get vrSalario
     *
     * @return float
     */
    public function getVrSalario()
    {
        return $this->vrSalario;
    }
}
