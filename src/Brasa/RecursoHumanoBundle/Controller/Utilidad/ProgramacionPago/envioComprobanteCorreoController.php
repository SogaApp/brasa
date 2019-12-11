<?php

namespace Brasa\RecursoHumanoBundle\Controller\Utilidad\ProgramacionPago;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityRepository;
use ZipArchive;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;

class envioComprobanteCorreoController extends Controller
{
    var $strDqlLista = "";
    
    /**
     * @Route("/rhu/utilidades/programacion/pago/comprobante/correo/{codigoProgramacionPago}/{codigoPago}", name="brs_rhu_utilidades_programacion_pago_comprobante_correo")
     */         
    public function listaAction($codigoProgramacionPago = "", $codigoPago = "") {
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $objMensaje = new \Brasa\GeneralBundle\MisClases\Mensajes();
        /*if(!$em->getRepository('BrasaSeguridadBundle:SegUsuarioPermisoEspecial')->permisoEspecial($this->getUser(), 75)) {
            return $this->redirect($this->generateUrl('brs_seg_error_permiso_especial'));            
        }*/        
        $session->set('filtroRhuCodigoProgramacionPago', $codigoProgramacionPago); 
        $session->set('filtroRhuCodigoPago', $codigoPago); 
        $form = $this->formularioLista();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnEnviar')->isClicked()) {
                $codigo = $form->get('codigoProgramacionPago')->getData();  
                $arProgramacionPago = new \Brasa\RecursoHumanoBundle\Entity\RhuProgramacionPago();
                $arProgramacionPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuProgramacionPago')->find($codigo);
                if($arProgramacionPago) {
                    if($arProgramacionPago->getEstadoPagado() == 1) {
                        set_time_limit(0);
                        ini_set("memory_limit", -1);                        
                        $arConfiguracionGeneral = new \Brasa\GeneralBundle\Entity\GenConfiguracion();
                        $arConfiguracionGeneral = $em->getRepository('BrasaGeneralBundle:GenConfiguracion')->find(1);
                        $arConfiguracion = new \Brasa\RecursoHumanoBundle\Entity\RhuConfiguracion();
                        $arConfiguracion = $em->getRepository('BrasaRecursoHumanoBundle:RhuConfiguracion')->find(1);
                        $codigoFormato = $arConfiguracion->getCodigoFormatoPago();                    
                        $ruta = $arConfiguracionGeneral->getRutaTemporal();
                        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();                
                        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findBy(array('codigoProgramacionPagoFk' => $codigo));                
                        foreach ($arPagos as $arPago) {
                            if($codigoFormato <= 1) {
                                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\PagoMasivo1();
                                $objFormatoPago->Generar($this, "", $ruta, $arPago->getCodigoPagoPk(), "", "", "", "", "", "");
                            }   
                            if($codigoFormato == 2) {
                                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\PagoMasivo2();
                                $objFormatoPago->Generar($this, "", $ruta, $arPago->getCodigoPagoPk(), "", "", "", "", "", "");
                            }  

                            $correo = $arPago->getEmpleadoRel()->getCorreo();
                            $correoNomina = $arConfiguracion->getCorreoNomina();
                            if($correo) {
                                $rutaArchivo = $ruta."Pago".$arPago->getCodigoPagoPk().".pdf";
                                $strMensaje = "Se adjunta comprobante de pago (sogaApp)";                
                                $message = \Swift_Message::newInstance()
                                    ->setSubject('Comprobante de pago ')
                                    ->setFrom($correoNomina, "SogaApp" )
                                    ->setTo(strtolower($correo))
                                    ->setBody($strMensaje,'text/html')                            
                                    ->attach(\Swift_Attachment::fromPath($rutaArchivo));                
                                $this->get('mailer')->send($message);                                 
                            }                             
                        }
                        $objMensaje->Mensaje("error", "Se han enviado los correos exitosamente", $this);
                        /*foreach ($arPagos as $arPago) {
                            $rutaArchivo = $ruta."Pago".$arPago->getCodigoPagoPk().".pdf";
                            unlink($rutaArchivo);
                        } */                       
                    } else {
                        $objMensaje->Mensaje("error", "La programacion de pago debe estar pagada", $this);
                    }                   
                } else {
                    $objMensaje->Mensaje("error", "La programacion de pago " . $codigo . " no existe", $this);
                }                                                                              
            }            
        }                    
        return $this->render('BrasaRecursoHumanoBundle:Utilidades/ProgramacionesPago:comprobanteCorreo.html.twig', array(            
            'form' => $form->createView()));
    }              
    
    private function formularioLista() {  
        $em = $this->getDoctrine()->getManager();  
        $session = $this->get('session');
               
        $form = $this->createFormBuilder()                  
            ->add('codigoProgramacionPago','text', array('required'  => false, 'data' => $session->get('filtroRhuCodigoProgramacionPago')))
            ->add('codigoPago','text', array('required'  => false, 'data' => $session->get('filtroRhuCodigoPago')))
            ->add('BtnEnviar', 'submit', array('label'  => 'Enviar'))    
            ->getForm();        
        return $form;
    }                         

}
