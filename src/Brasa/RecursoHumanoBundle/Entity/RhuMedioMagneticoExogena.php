<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_medio_magnetico_exogena")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuMedioMagneticoExogenaRepository")
 */
class RhuMedioMagneticoExogena
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_medio_magnetico_exogena_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoMedioMagneticoExogenaPk;                          
    
    /**
     * @ORM\Column(name="codigo_formato", type="integer", nullable=true)
     */    
    private $codigoFormato;    
        
    /**
     * @ORM\Column(name="formato", type="string", length=200, nullable=true)
     */    
    private $formato;
    
    /**
     * @ORM\Column(name="version", type="string", length=3)
     */    
    private $version;
    
    

    /**
     * Get codigoMedioMagneticoExogenaPk
     *
     * @return integer
     */
    public function getCodigoMedioMagneticoExogenaPk()
    {
        return $this->codigoMedioMagneticoExogenaPk;
    }

    /**
     * Set codigoFormato
     *
     * @param integer $codigoFormato
     *
     * @return RhuMedioMagneticoExogena
     */
    public function setCodigoFormato($codigoFormato)
    {
        $this->codigoFormato = $codigoFormato;

        return $this;
    }

    /**
     * Get codigoFormato
     *
     * @return integer
     */
    public function getCodigoFormato()
    {
        return $this->codigoFormato;
    }

    /**
     * Set formato
     *
     * @param string $formato
     *
     * @return RhuMedioMagneticoExogena
     */
    public function setFormato($formato)
    {
        $this->formato = $formato;

        return $this;
    }

    /**
     * Get formato
     *
     * @return string
     */
    public function getFormato()
    {
        return $this->formato;
    }

    /**
     * Set version
     *
     * @param string $version
     *
     * @return RhuMedioMagneticoExogena
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}
