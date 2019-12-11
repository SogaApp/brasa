<?php

namespace Brasa\SeguridadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seg_recuperar_clave")
 * @ORM\Entity(repositoryClass="Brasa\SeguridadBundle\Repository\SegRecuperarClaveRepository")
 */
class SegRecuperarClave
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_recuperar_clave_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoRecuperarClavePk;
    
    /**
     * @ORM\Column(name="codigo", type="string", length=150, nullable=true)
     */    
    private $codigo;   

    /**
     * @ORM\Column(name="id", type="integer")
     */    
    private $id;    

    /**
     * Get codigoRecuperarClavePk
     *
     * @return integer
     */
    public function getCodigoRecuperarClavePk()
    {
        return $this->codigoRecuperarClavePk;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return SegRecuperarClave
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return SegRecuperarClave
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
