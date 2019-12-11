<?php

namespace Brasa\GeneralBundle\ExtensionesTwig;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\Form\FormView;

class BrasaTwigExtension extends \Twig_Extension
{
    /**
     *
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getFilters()
    {
        return array();
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("validarRecibo", array($this, "getValidarRecibo")),
        );
    }

    public function getValidarRecibo($codigoPerido){

        $arrRespuesta = array("NO","");
        $arFacturaDetalle = $this->em->getRepository('BrasaAfiliacionBundle:AfiFacturaDetalle')->findOneBy(array('codigoPeriodoFk' => $codigoPerido));
        if($arFacturaDetalle){
            $arCuentaCobrar = $this->em->getRepository('BrasaCarteraBundle:CarCuentaCobrar')->findOneBy(array("codigoFactura" => $arFacturaDetalle->getCodigoFacturaFk()));
            if($arCuentaCobrar){
                $arReciboDetalle = $this->em->getRepository('BrasaCarteraBundle:CarReciboDetalle')->findOneBy(array('codigoCuentaCobrarFk' => $arCuentaCobrar->getCodigoCuentaCobrarPk()));
                if($arReciboDetalle){
                    $arrRespuesta = array("SI",$arReciboDetalle->getReciboRel()->getFecha()->format("Y/m/d"));
                }
            }
        }

        return $arrRespuesta;
    }
}
