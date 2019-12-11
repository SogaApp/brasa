<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_pago_curso_detalle")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiPagoCursoDetalleRepository")
 */
class AfiPagoCursoDetalle
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_curso_detalle_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoCursoDetallePk;    
    
    /**
     * @ORM\Column(name="codigo_pago_curso_fk", type="integer")
     */    
    private $codigoPagoCursoFk;            

    /**
     * @ORM\Column(name="codigo_curso_detalle_fk", type="integer")
     */    
    private $codigoCursoDetalleFk; 
    
    /**
     * @ORM\Column(name="costo", type="float")
     */
    private $costo = 0;             
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiPagoCurso", inversedBy="pagosCursosDetallesPagoCursoRel")
     * @ORM\JoinColumn(name="codigo_pago_curso_fk", referencedColumnName="codigo_pago_curso_pk")
     */
    protected $pagoCursoRel;           

    /**
     * @ORM\ManyToOne(targetEntity="AfiCursoDetalle", inversedBy="pagosCursosDetallesCursoDetalleRel")
     * @ORM\JoinColumn(name="codigo_curso_detalle_fk", referencedColumnName="codigo_curso_detalle_pk")
     */
    protected $cursoDetalleRel;     
    


    /**
     * Get codigoPagoCursoDetallePk
     *
     * @return integer
     */
    public function getCodigoPagoCursoDetallePk()
    {
        return $this->codigoPagoCursoDetallePk;
    }

    /**
     * Set codigoPagoCursoFk
     *
     * @param integer $codigoPagoCursoFk
     *
     * @return AfiPagoCursoDetalle
     */
    public function setCodigoPagoCursoFk($codigoPagoCursoFk)
    {
        $this->codigoPagoCursoFk = $codigoPagoCursoFk;

        return $this;
    }

    /**
     * Get codigoPagoCursoFk
     *
     * @return integer
     */
    public function getCodigoPagoCursoFk()
    {
        return $this->codigoPagoCursoFk;
    }

    /**
     * Set codigoCursoDetalleFk
     *
     * @param integer $codigoCursoDetalleFk
     *
     * @return AfiPagoCursoDetalle
     */
    public function setCodigoCursoDetalleFk($codigoCursoDetalleFk)
    {
        $this->codigoCursoDetalleFk = $codigoCursoDetalleFk;

        return $this;
    }

    /**
     * Get codigoCursoDetalleFk
     *
     * @return integer
     */
    public function getCodigoCursoDetalleFk()
    {
        return $this->codigoCursoDetalleFk;
    }

    /**
     * Set costo
     *
     * @param float $costo
     *
     * @return AfiPagoCursoDetalle
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;

        return $this;
    }

    /**
     * Get costo
     *
     * @return float
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiPagoCursoDetalle
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set pagoCursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagoCursoRel
     *
     * @return AfiPagoCursoDetalle
     */
    public function setPagoCursoRel(\Brasa\AfiliacionBundle\Entity\AfiPagoCurso $pagoCursoRel = null)
    {
        $this->pagoCursoRel = $pagoCursoRel;

        return $this;
    }

    /**
     * Get pagoCursoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiPagoCurso
     */
    public function getPagoCursoRel()
    {
        return $this->pagoCursoRel;
    }

    /**
     * Set cursoDetalleRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursoDetalleRel
     *
     * @return AfiPagoCursoDetalle
     */
    public function setCursoDetalleRel(\Brasa\AfiliacionBundle\Entity\AfiCursoDetalle $cursoDetalleRel = null)
    {
        $this->cursoDetalleRel = $cursoDetalleRel;

        return $this;
    }

    /**
     * Get cursoDetalleRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCursoDetalle
     */
    public function getCursoDetalleRel()
    {
        return $this->cursoDetalleRel;
    }
}
