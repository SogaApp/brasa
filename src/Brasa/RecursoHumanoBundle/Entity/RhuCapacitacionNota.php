<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_capacitacion_nota")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuCapacitacionNotaRepository")
 */
class RhuCapacitacionNota
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_capacitacion_nota_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoCapacitacionNotaPk;                    
    
    /**
     * @ORM\Column(name="codigo_capacitacion_fk", type="integer", nullable=true)
     */    
    private $codigoCapacitacionFk;   
    
    /**
     * @ORM\Column(name="nota", type="string", length=500, nullable=true)
     */    
    private $nota;            
    
    /**
     * @ORM\ManyToOne(targetEntity="RhuCapacitacion", inversedBy="capacitacionesNotasCapacitacionRel")
     * @ORM\JoinColumn(name="codigo_capacitacion_fk", referencedColumnName="codigo_capacitacion_pk")
     */
    protected $capacitacionRel;



    /**
     * Get codigoCapacitacionNotaPk
     *
     * @return integer
     */
    public function getCodigoCapacitacionNotaPk()
    {
        return $this->codigoCapacitacionNotaPk;
    }

    /**
     * Set codigoCapacitacionFk
     *
     * @param integer $codigoCapacitacionFk
     *
     * @return RhuCapacitacionNota
     */
    public function setCodigoCapacitacionFk($codigoCapacitacionFk)
    {
        $this->codigoCapacitacionFk = $codigoCapacitacionFk;

        return $this;
    }

    /**
     * Get codigoCapacitacionFk
     *
     * @return integer
     */
    public function getCodigoCapacitacionFk()
    {
        return $this->codigoCapacitacionFk;
    }

    /**
     * Set nota
     *
     * @param string $nota
     *
     * @return RhuCapacitacionNota
     */
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get nota
     *
     * @return string
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set capacitacionRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionRel
     *
     * @return RhuCapacitacionNota
     */
    public function setCapacitacionRel(\Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion $capacitacionRel = null)
    {
        $this->capacitacionRel = $capacitacionRel;

        return $this;
    }

    /**
     * Get capacitacionRel
     *
     * @return \Brasa\RecursoHumanoBundle\Entity\RhuCapacitacion
     */
    public function getCapacitacionRel()
    {
        return $this->capacitacionRel;
    }
}
