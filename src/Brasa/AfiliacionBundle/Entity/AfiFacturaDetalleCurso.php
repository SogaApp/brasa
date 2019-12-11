<?php

namespace Brasa\AfiliacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="afi_factura_detalle_curso")
 * @ORM\Entity(repositoryClass="Brasa\AfiliacionBundle\Repository\AfiFacturaDetalleCursoRepository")
 */
class AfiFacturaDetalleCurso
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_factura_detalle_curso_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFacturaDetalleCursoPk;    
    
    /**
     * @ORM\Column(name="codigo_factura_fk", type="integer")
     */    
    private $codigoFacturaFk;            

    /**
     * @ORM\Column(name="codigo_curso_fk", type="integer")
     */    
    private $codigoCursoFk; 
    
    /**
     * @ORM\Column(name="precio", type="float")
     */
    private $precio = 0;             
    
    /**
     * @ORM\Column(name="total", type="float")
     */
    private $total = 0;     
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiFactura", inversedBy="facturasDetallesCursosFacturaRel")
     * @ORM\JoinColumn(name="codigo_factura_fk", referencedColumnName="codigo_factura_pk")
     */
    protected $facturaRel;    
    
    /**
     * @ORM\ManyToOne(targetEntity="AfiCurso", inversedBy="facturasDetallesCursosCursoRel")
     * @ORM\JoinColumn(name="codigo_curso_fk", referencedColumnName="codigo_curso_pk")
     */
    protected $cursoRel; 
    

    /**
     * Get codigoFacturaDetalleCursoPk
     *
     * @return integer
     */
    public function getCodigoFacturaDetalleCursoPk()
    {
        return $this->codigoFacturaDetalleCursoPk;
    }

    /**
     * Set codigoFacturaFk
     *
     * @param integer $codigoFacturaFk
     *
     * @return AfiFacturaDetalleCurso
     */
    public function setCodigoFacturaFk($codigoFacturaFk)
    {
        $this->codigoFacturaFk = $codigoFacturaFk;

        return $this;
    }

    /**
     * Get codigoFacturaFk
     *
     * @return integer
     */
    public function getCodigoFacturaFk()
    {
        return $this->codigoFacturaFk;
    }

    /**
     * Set codigoCursoFk
     *
     * @param integer $codigoCursoFk
     *
     * @return AfiFacturaDetalleCurso
     */
    public function setCodigoCursoFk($codigoCursoFk)
    {
        $this->codigoCursoFk = $codigoCursoFk;

        return $this;
    }

    /**
     * Get codigoCursoFk
     *
     * @return integer
     */
    public function getCodigoCursoFk()
    {
        return $this->codigoCursoFk;
    }

    /**
     * Set precio
     *
     * @param float $precio
     *
     * @return AfiFacturaDetalleCurso
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return AfiFacturaDetalleCurso
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
     * Set facturaRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiFactura $facturaRel
     *
     * @return AfiFacturaDetalleCurso
     */
    public function setFacturaRel(\Brasa\AfiliacionBundle\Entity\AfiFactura $facturaRel = null)
    {
        $this->facturaRel = $facturaRel;

        return $this;
    }

    /**
     * Get facturaRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiFactura
     */
    public function getFacturaRel()
    {
        return $this->facturaRel;
    }

    /**
     * Set cursoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCurso $cursoRel
     *
     * @return AfiFacturaDetalleCurso
     */
    public function setCursoRel(\Brasa\AfiliacionBundle\Entity\AfiCurso $cursoRel = null)
    {
        $this->cursoRel = $cursoRel;

        return $this;
    }

    /**
     * Get cursoRel
     *
     * @return \Brasa\AfiliacionBundle\Entity\AfiCurso
     */
    public function getCursoRel()
    {
        return $this->cursoRel;
    }
}
