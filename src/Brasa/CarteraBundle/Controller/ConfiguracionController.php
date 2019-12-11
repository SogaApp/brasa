<?php

namespace Brasa\CarteraBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityRepository;

/**
 * CarConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller
{
    
    /**
     * @Route("/car/configuracion/{codigoConfiguracionPk}", name="brs_car_configuracion")
     */
    public function configuracionAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 91)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $arConsecutivo = new \Brasa\CarteraBundle\Entity\CarConsecutivo();
        $arConsecutivo = $em->getRepository('BrasaCarteraBundle:CarConsecutivo')->findAll();

        $formConfiguracion = $this->createFormBuilder()             
            //->add('informacionLegalFactura', 'textarea', array('data' => $arConfiguracion->getInformacionLegalFactura(), 'required' => false)) 
            //->add('informacionPagoFactura', 'textarea', array('data' => $arConfiguracion->getInformacionPagoFactura(), 'required' => false)) 
            //->add('informacionContactoFactura', 'textarea', array('data' => $arConfiguracion->getInformacionContactoFactura(), 'required' => false)) 
            //->add('informacionResolucionDianFactura', 'textarea', array('data' => $arConfiguracion->getInformacionResolucionDianFactura(), 'required' => false)) 
            //->add('informacionResolucionSupervigilanciaFactura', 'textarea', array('data' => $arConfiguracion->getInformacionResolucionSupervigilanciaFactura(), 'required' => false)) 
            ->add('guardar', 'submit', array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
            $controles = $request->request->get('form');
            /*$strInformacionLegalFactura = $controles['informacionLegalFactura'];
            $strInformacionPagoFactura = $controles['informacionPagoFactura'];
            $strInformacionContactoFactura = $controles['informacionContactoFactura'];
            $strInformacionResolucionDianFactura = $controles['informacionResolucionDianFactura'];
            $strInformacionResolucionSupervigilanciaFactura = $controles['informacionResolucionSupervigilanciaFactura'];
            $arConfiguracion->setInformacionLegalFactura($strInformacionLegalFactura);
            $arConfiguracion->setInformacionPagoFactura($strInformacionPagoFactura);
            $arConfiguracion->setInformacionContactoFactura($strInformacionContactoFactura);
            $arConfiguracion->setInformacionResolucionDianFactura($strInformacionResolucionDianFactura);
            $arConfiguracion->setInformacionResolucionSupervigilanciaFactura($strInformacionResolucionSupervigilanciaFactura);*/
            $arrControles = $request->request->All();
            $intIndiceConsecutivo = 0;
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arConsecutivo = new \Brasa\CarteraBundle\Entity\CarConsecutivo();
                $arConsecutivo = $em->getRepository('BrasaCarteraBundle:CarConsecutivo')->find($intCodigo);
                if(count($arConsecutivo) > 0) {                                            
                    $intConsecutivo = $arrControles['TxtConsecutivo'.$intCodigo];
                    $arConsecutivo->setConsecutivo($intConsecutivo);
                    $em->persist($arConsecutivo);
                }
                $intIndiceConsecutivo++;
            }
            //$em->persist($arConfiguracion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_car_configuracion', array('codigoConfiguracionPk' => 1)));
        }
        return $this->render('BrasaCarteraBundle:Configuracion:Configuracion.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
            'arConsecutivo' => $arConsecutivo
        ));
    }
}
