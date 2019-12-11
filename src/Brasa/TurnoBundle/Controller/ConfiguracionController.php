<?php

namespace Brasa\TurnoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;

/**
 * TurConfiguracion controller.
 *
 */
class ConfiguracionController extends Controller
{
    
    /**
     * @Route("/tur/configuracion/{codigoConfiguracionPk}", name="brs_tur_configuracion")
     */     
    public function configuracionAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 90)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }
        $arConfiguracion = new \Brasa\TurnoBundle\Entity\TurConfiguracion();
        $arConfiguracion = $em->getRepository('BrasaTurnoBundle:TurConfiguracion')->find(1);
        $arConsecutivo = new \Brasa\TurnoBundle\Entity\TurConsecutivo();
        $arConsecutivo = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->findAll();

        $formConfiguracion = $this->createFormBuilder()             
            ->add('informacionLegalFactura', TextareaType::class, array('data' => $arConfiguracion->getInformacionLegalFactura(), 'required' => false)) 
            ->add('informacionPagoFactura', TextareaType::class, array('data' => $arConfiguracion->getInformacionPagoFactura(), 'required' => false)) 
            ->add('informacionContactoFactura', TextareaType::class, array('data' => $arConfiguracion->getInformacionContactoFactura(), 'required' => false)) 
            ->add('informacionResolucionDianFactura', TextareaType::class, array('data' => $arConfiguracion->getInformacionResolucionDianFactura(), 'required' => false)) 
            ->add('informacionResolucionSupervigilanciaFactura', TextareaType::class, array('data' => $arConfiguracion->getInformacionResolucionSupervigilanciaFactura(), 'required' => false)) 
            ->add('codigoConceptoHorasDescansoFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasDescansoFk(), 'required' => false)) 
            ->add('codigoConceptoHorasDiurnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasNocturnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasNocturnasFk(), 'required' => false))                 
            ->add('codigoConceptoHorasFestivasDiurnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasFestivasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasFestivasNocturnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasFestivasNocturnasFk(), 'required' => false))                                 
            ->add('codigoConceptoHorasExtrasOrdinariasDiurnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasOrdinariasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasExtrasOrdinariasNocturnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasOrdinariasNocturnasFk(), 'required' => false))                                                 
            ->add('codigoConceptoHorasExtrasFestivasDiurnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasFestivasDiurnasFk(), 'required' => false)) 
            ->add('codigoConceptoHorasExtrasFestivasNocturnasFk', NumberType::class, array('data' => $arConfiguracion->getCodigoConceptoHorasExtrasFestivasNocturnasFk(), 'required' => false))                                                                 
            ->add('guardar', SubmitType::class, array('label' => 'Actualizar'))
            ->getForm();
        $formConfiguracion->handleRequest($request);
        if ($formConfiguracion->isValid()) {
            $controles = $request->request->get('form');
            $strInformacionLegalFactura = $controles['informacionLegalFactura'];
            $strInformacionPagoFactura = $controles['informacionPagoFactura'];
            $strInformacionContactoFactura = $controles['informacionContactoFactura'];
            $strInformacionResolucionDianFactura = $controles['informacionResolucionDianFactura'];
            $strInformacionResolucionSupervigilanciaFactura = $controles['informacionResolucionSupervigilanciaFactura'];
            $arConfiguracion->setInformacionLegalFactura($strInformacionLegalFactura);
            $arConfiguracion->setInformacionPagoFactura($strInformacionPagoFactura);
            $arConfiguracion->setInformacionContactoFactura($strInformacionContactoFactura);
            $arConfiguracion->setInformacionResolucionDianFactura($strInformacionResolucionDianFactura);
            $arConfiguracion->setInformacionResolucionSupervigilanciaFactura($strInformacionResolucionSupervigilanciaFactura);
            $arConfiguracion->setCodigoConceptoHorasDescansoFk($controles['codigoConceptoHorasDescansoFk']);
            $arConfiguracion->setCodigoConceptoHorasDiurnasFk($controles['codigoConceptoHorasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasNocturnasFk($controles['codigoConceptoHorasNocturnasFk']);
            $arConfiguracion->setCodigoConceptoHorasFestivasDiurnasFk($controles['codigoConceptoHorasFestivasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasFestivasNocturnasFk($controles['codigoConceptoHorasFestivasNocturnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasOrdinariasDiurnasFk($controles['codigoConceptoHorasExtrasOrdinariasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasOrdinariasNocturnasFk($controles['codigoConceptoHorasExtrasOrdinariasNocturnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasFestivasDiurnasFk($controles['codigoConceptoHorasExtrasFestivasDiurnasFk']);
            $arConfiguracion->setCodigoConceptoHorasExtrasFestivasNocturnasFk($controles['codigoConceptoHorasExtrasFestivasNocturnasFk']);
            $arrControles = $request->request->All();
            $intIndiceConsecutivo = 0;
            foreach ($arrControles['LblCodigo'] as $intCodigo) {
                $arConsecutivo = new \Brasa\TurnoBundle\Entity\TurConsecutivo();
                $arConsecutivo = $em->getRepository('BrasaTurnoBundle:TurConsecutivo')->find($intCodigo);
                if(count($arConsecutivo) > 0) {                                            
                    $intConsecutivo = $arrControles['TxtConsecutivo'.$intCodigo];
                    $arConsecutivo->setConsecutivo($intConsecutivo);
                    $em->persist($arConsecutivo);
                }
                $intIndiceConsecutivo++;
            }
                    
            $em->persist($arConfiguracion);
            $em->flush();
            return $this->redirect($this->generateUrl('brs_tur_configuracion', array('codigoConfiguracionPk' => 1)));
        }
        return $this->render('BrasaTurnoBundle:Configuracion:Configuracion.html.twig', array(
            'formConfiguracion' => $formConfiguracion->createView(),
            'arConsecutivo' => $arConsecutivo
        ));
    }
    
}
