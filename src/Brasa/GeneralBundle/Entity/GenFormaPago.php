<?php

namespace Brasa\GeneralBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="gen_forma_pago")
 * @ORM\Entity(repositoryClass="Brasa\GeneralBundle\Repository\GenFormaPagoRepository")
 */
class GenFormaPago
{
    /**
     * @ORM\Id
     * @ORM\Column(name="codigo_forma_pago_pk", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $codigoFormaPagoPk;

    /**
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotNull()(message="Debe escribir un nombre de la forma de pago")
     */
    private $nombre;      
    
    /**
     * @ORM\OneToMany(targetEntity="Brasa\TurnoBundle\Entity\TurCliente", mappedBy="formaPagoRel")
     */
    protected $turClientesFormaPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\CarteraBundle\Entity\CarCliente", mappedBy="formaPagoRel")
     */
    protected $carClientesFormaPagoRel;

    /**
     * @ORM\OneToMany(targetEntity="Brasa\RecursoHumanoBundle\Entity\RhuCliente", mappedBy="formaPagoRel")
     */
    protected $rhuClientesFormaPagoRel;    

    /**
     * @ORM\OneToMany(targetEntity="Brasa\AfiliacionBundle\Entity\AfiCliente", mappedBy="formaPagoRel")
     */
    protected $afiClientesFormaPagoRel;    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->turClientesFormaPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carClientesFormaPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhuClientesFormaPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->afiClientesFormaPagoRel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get codigoFormaPagoPk
     *
     * @return integer
     */
    public function getCodigoFormaPagoPk()
    {
        return $this->codigoFormaPagoPk;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return GenFormaPago
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
     * Add turClientesFormaPagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel
     *
     * @return GenFormaPago
     */
    public function addTurClientesFormaPagoRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel)
    {
        $this->turClientesFormaPagoRel[] = $turClientesFormaPagoRel;

        return $this;
    }

    /**
     * Remove turClientesFormaPagoRel
     *
     * @param \Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel
     */
    public function removeTurClientesFormaPagoRel(\Brasa\TurnoBundle\Entity\TurCliente $turClientesFormaPagoRel)
    {
        $this->turClientesFormaPagoRel->removeElement($turClientesFormaPagoRel);
    }

    /**
     * Get turClientesFormaPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTurClientesFormaPagoRel()
    {
        return $this->turClientesFormaPagoRel;
    }

    /**
     * Add carClientesFormaPagoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel
     *
     * @return GenFormaPago
     */
    public function addCarClientesFormaPagoRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel)
    {
        $this->carClientesFormaPagoRel[] = $carClientesFormaPagoRel;

        return $this;
    }

    /**
     * Remove carClientesFormaPagoRel
     *
     * @param \Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel
     */
    public function removeCarClientesFormaPagoRel(\Brasa\CarteraBundle\Entity\CarCliente $carClientesFormaPagoRel)
    {
        $this->carClientesFormaPagoRel->removeElement($carClientesFormaPagoRel);
    }

    /**
     * Get carClientesFormaPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarClientesFormaPagoRel()
    {
        return $this->carClientesFormaPagoRel;
    }

    /**
     * Add rhuClientesFormaPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesFormaPagoRel
     *
     * @return GenFormaPago
     */
    public function addRhuClientesFormaPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesFormaPagoRel)
    {
        $this->rhuClientesFormaPagoRel[] = $rhuClientesFormaPagoRel;

        return $this;
    }

    /**
     * Remove rhuClientesFormaPagoRel
     *
     * @param \Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesFormaPagoRel
     */
    public function removeRhuClientesFormaPagoRel(\Brasa\RecursoHumanoBundle\Entity\RhuCliente $rhuClientesFormaPagoRel)
    {
        $this->rhuClientesFormaPagoRel->removeElement($rhuClientesFormaPagoRel);
    }

    /**
     * Get rhuClientesFormaPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhuClientesFormaPagoRel()
    {
        return $this->rhuClientesFormaPagoRel;
    }

    /**
     * Add afiClientesFormaPagoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesFormaPagoRel
     *
     * @return GenFormaPago
     */
    public function addAfiClientesFormaPagoRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesFormaPagoRel)
    {
        $this->afiClientesFormaPagoRel[] = $afiClientesFormaPagoRel;

        return $this;
    }

    /**
     * Remove afiClientesFormaPagoRel
     *
     * @param \Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesFormaPagoRel
     */
    public function removeAfiClientesFormaPagoRel(\Brasa\AfiliacionBundle\Entity\AfiCliente $afiClientesFormaPagoRel)
    {
        $this->afiClientesFormaPagoRel->removeElement($afiClientesFormaPagoRel);
    }

    /**
     * Get afiClientesFormaPagoRel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAfiClientesFormaPagoRel()
    {
        return $this->afiClientesFormaPagoRel;
    }
}
