<?php

namespace Brasa\TurnoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tur_servicio_detalle_recurso")
 * @ORM\Entity(repositoryClass="Brasa\TurnoBundle\Repository\TurServicioDetalleRecursoRepository")
 */
class TurServicioDetalleRecurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_servicio_detalle_recurso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoServicioDetalleRecursoPk;  
    
    /**
     * @ORM\Column(name="codigo_servicio_detalle_fk", type="integer")
     */    
    private $codigoServicioDetalleFk;

    /**
     * @ORM\Column(name="codigo_recurso_fk", type="integer")
     */    
    private $codigoRecursoFk;    
    
    /**
     * @ORM\Column(name="posicion", type="integer")
     */    
    private $posicion = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="TurServicioDetalle", inversedBy="serviciosDetallesRecursosServicioDetalleRel")
     * @ORM\JoinColumn(name="codigo_servicio_detalle_fk", referencedColumnName="codigo_servicio_detalle_pk")
     */
    protected $servicioDetalleRel;       

    /**
     * @ORM\ManyToOne(targetEntity="TurRecurso", inversedBy="serviciosDetallesRecursosRecursoRel")
     * @ORM\JoinColumn(name="codigo_recurso_fk", referencedColumnName="codigo_recurso_pk")
     */
    protected $recursoRel;           


    /**
     * Get codigoServicioDetalleRecursoPk
     *
     * @return integer
     */
    public function getCodigoServicioDetalleRecursoPk()
    {
        return $this->codigoServicioDetalleRecursoPk;
    }

    /**
     * Set codigoServicioDetalleFk
     *
     * @param integer $codigoServicioDetalleFk
     *
     * @return TurServicioDetalleRecurso
     */
    public function setCodigoServicioDetalleFk($codigoServicioDetalleFk)
    {
        $this->codigoServicioDetalleFk = $codigoServicioDetalleFk;

        return $this;
    }

    /**
     * Get codigoServicioDetalleFk
     *
     * @return integer
     */
    public function getCodigoServicioDetalleFk()
    {
        return $this->codigoServicioDetalleFk;
    }

    /**
     * Set codigoRecursoFk
     *
     * @param integer $codigoRecursoFk
     *
     * @return TurServicioDetalleRecurso
     */
    public function setCodigoRecursoFk($codigoRecursoFk)
    {
        $this->codigoRecursoFk = $codigoRecursoFk;

        return $this;
    }

    /**
     * Get codigoRecursoFk
     *
     * @return integer
     */
    public function getCodigoRecursoFk()
    {
        return $this->codigoRecursoFk;
    }

    /**
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return TurServicioDetalleRecurso
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return integer
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set servicioDetalleRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurServicioDetalle $servicioDetalleRel
     *
     * @return TurServicioDetalleRecurso
     */
    public function setServicioDetalleRel(\Brasa\TurnoBundle\Entity\TurServicioDetalle $servicioDetalleRel = null)
    {
        $this->servicioDetalleRel = $servicioDetalleRel;

        return $this;
    }

    /**
     * Get servicioDetalleRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurServicioDetalle
     */
    public function getServicioDetalleRel()
    {
        return $this->servicioDetalleRel;
    }

    /**
     * Set recursoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurRecurso $recursoRel
     *
     * @return TurServicioDetalleRecurso
     */
    public function setRecursoRel(\Brasa\TurnoBundle\Entity\TurRecurso $recursoRel = null)
    {
        $this->recursoRel = $recursoRel;

        return $this;
    }

    /**
     * Get recursoRel
     *
     * @return \Brasa\TurnoBundle\Entity\TurRecurso
     */
    public function getRecursoRel()
    {
        return $this->recursoRel;
    }
}
