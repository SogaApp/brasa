<?php

namespace Brasa\RecursoHumanoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="rhu_pago_banco_tipo")
 * @ORM\Entity(repositoryClass="Brasa\RecursoHumanoBundle\Repository\RhuPagoBancoTipoRepository")
 */
class RhuPagoBancoTipo
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_pago_banco_tipo_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoPagoBancoTipoPk;
    
    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     */         
    private $nombre;

    /**
     * @ORM\Column(name="codigo_cuenta_fk", type="string", length=20, nullable=true)
     */     
    private $codigoCuentaFk;    
    
    /**
     * @ORM\OneToMany(targetEntity="RhuPagoBanco", mappedBy="pagoBancoTipoRel")
     */
    protected $pagosBancosPagoBancoTipoRel;     
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosBancosPagoBancoTipoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoPagoBancoTipoPk
     *
     * @return integer
     */
    public function getCodigoPagoBancoTipoPk()
    {
        return $this->codigoPagoBancoTipoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return RhuPagoBancoTipo
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
     * Add pagosBancosPagoBancoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $pagosBancosPagoBancoTipoRel
     *
     * @return RhuPagoBancoTipo
     */
    public function addPagosBancosPagoBancoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $pagosBancosPagoBancoTipoRel)
    {
        $this->pagosBancosPagoBancoTipoRel[] = $pagosBancosPagoBancoTipoRel;

        return $this;
    }

    /**
     * Remove pagosBancosPagoBancoTipoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $pagosBancosPagoBancoTipoRel
     */
    public function removePagosBancosPagoBancoTipoRel(\Brasa\RecursoHumanoBundle\Entity\RhuPagoBanco $pagosBancosPagoBancoTipoRel)
    {
        $this->pagosBancosPagoBancoTipoRel->removeElement($pagosBancosPagoBancoTipoRel);
    }

    /**
     * Get pagosBancosPagoBancoTipoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosBancosPagoBancoTipoRel()
    {
        return $this->pagosBancosPagoBancoTipoRel;
    }

    /**
     * Set codigoCuentaFk
     *
     * @param string $codigoCuentaFk
     *
     * @return RhuPagoBancoTipo
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
