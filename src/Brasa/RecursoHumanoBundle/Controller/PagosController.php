<?php

namespace Brasa\RecursoHumanoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagosController extends Controller
{
    public function listaAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $form = $this->createFormBuilder()
            ->add('BtnPdf', 'submit', array('label'  => 'PDF',))
            ->add('BtnExcel', 'submit', array('label'  => 'Excel',))
            ->getForm();
        $form->handleRequest($request);        
        
        $arPagos = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPagos = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->findAll();                       

        return $this->render('BrasaRecursoHumanoBundle:Pagos:lista.html.twig', array(
            'arPagos' => $arPagos,
            'form' => $form->createView()));
    }       
    
    public function detalleAction($codigoPago) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();    
        $objMensaje = $this->get('mensajes_brasa');        
        $arPago = new \Brasa\RecursoHumanoBundle\Entity\RhuPago();
        $arPago = $em->getRepository('BrasaRecursoHumanoBundle:RhuPago')->find($codigoPago);
        $arPagoDetalles = new \Brasa\RecursoHumanoBundle\Entity\RhuPagoDetalle();
        $arPagoDetalles = $em->getRepository('BrasaRecursoHumanoBundle:RhuPagoDetalle')->findBy(array('codigoPagoFk' => $codigoPago));
        $form = $this->createFormBuilder()
            ->add('BtnImprimir', 'submit', array('label'  => 'Imprimir',))
            ->getForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            if($form->get('BtnImprimir')->isClicked()) {
                echo "Imprimir";
                $objFormatoPago = new \Brasa\RecursoHumanoBundle\Formatos\FormatoPago();
                $objFormatoPago->Generar($this, $codigoPago);
                //$objFormatoManifiesto = new \Brasa\TransporteBundle\Formatos\FormatoManifiesto();
                //$objFormatoManifiesto->Generar($this, $codigoDespacho);
            }
        }
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            $arrSeleccionados = $request->request->get('ChkSeleccionar');
            $arrDescuentosFinancierosSeleccionados = $request->request->get('ChkSeleccionarDescuentoFinanciero');
            switch ($request->request->get('OpSubmit')) {
                case "OpGenerar";
                    $strResultado = $em->getRepository('BrasaTransporteBundle:TteDespacho')->Generar($codigoDespacho);
                    if ($strResultado != "") {
                        $objMensaje->Mensaje("error", "No se genero el despacho: " . $strResultado, $this);
                    }                        
                    break;

                case "OpAnular";
                    $varAnular = $em->getRepository('BrasaTransporteBundle:TteDespacho')->Anular($codigoDespacho);
                    if ($varAnular != "") {
                        $objMensaje->Mensaje("error", "No se anulo el despacho: " . $varAnular, $this);
                    }                        
                    break;

                case "OpImprimir";   
                    $objFormatoManifiesto = new \Brasa\TransporteBundle\Formatos\FormatoManifiesto();
                    $objFormatoManifiesto->Generar($this, $codigoDespacho);
                    break;

                case "OpRetirar";
                    if (count($arrSeleccionados) > 0) {
                        $intUnidades = $arDespacho->getCtUnidades();
                        $intPesoReal = $arDespacho->getCtPesoReal();
                        $intPesoVolumen = $arDespacho->getCtPesoVolumen();
                        $intGuias = $arDespacho->getCtGuias();
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuia();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuia')->find($codigoGuia);
                            if($arGuia->getCodigoDespachoFk() != NULL) {
                                $arGuia->setCodigoDespachoFk(NULL);
                                $arGuia->setEstadoDespachada(0);                                
                                $em->persist($arGuia);
                                $em->flush(); 
                                $intUnidades = $intUnidades - $arGuia->getCtUnidades();
                                $intPesoReal = $intPesoReal - $arGuia->getCtPesoReal();
                                $intPesoVolumen = $intPesoVolumen - $arGuia->getCtPesoVolumen();
                                $intGuias = $intGuias - 1;                                  
                            }                            
                        }
                        $arDespacho->setCtUnidades($intUnidades);
                        $arDespacho->setCtPesoReal($intPesoReal);
                        $arDespacho->setCtPesoVolumen($intPesoVolumen);
                        $arDespacho->setCtGuias($intGuias);
                        $em->persist($arDespacho);
                        $em->flush();                        
                    }
                    break;                                                          
            }
        }
        
        return $this->render('BrasaRecursoHumanoBundle:Pagos:detalle.html.twig', array(
                    'arPago' => $arPago,
                    'arPagoDetalles' => $arPagoDetalles,
                    'form' => $form->createView()
                    ));
    }        
}
