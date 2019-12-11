<?php

namespace Brasa\GeneralBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BusquedasController extends Controller
{
    
    public function buscarTerceroAction($campoCodigo, $campoNombre) {
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            if ($request->request->get('TxtCodigo') != "" && is_numeric($request->request->get('TxtCodigo'))) {
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findBy(array('codigoItemPk' => $request->request->get('TxtCodigoItem')));
            } elseif ($request->request->get('TxtDescripcionItem') != "") {
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));
            }
            // Todos los productos
            else
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        }
        else {
            $arTerceros = $em->getRepository('BrasaGeneralBundle:GenTercero')->findAll();
        }

        return $this->render('BrasaGeneralBundle:Busquedas:buscarTercero.html.twig', array(
            "arTerceros" => $arTerceros,
            "campoCodigo" => $campoCodigo,
            "campoNombre" => $campoNombre));
    }
    
    /**
     * @Route("/general/buscar/ciudad/{campoCodigo}/{campoNombre}/", name="brs_gen_buscar_ciudad")
     */
    public function buscarCiudadAction($campoCodigo, $campoNombre) {
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->getRequest();

        if ($request->getMethod() == 'POST') {
            if ($request->request->get('TxtCodigo') != "" && is_numeric($request->request->get('TxtCodigo'))) {
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findBy(array('codigoItemPk' => $request->request->get('TxtCodigoItem')));
            } elseif ($request->request->get('TxtDescripcionItem') != "") {
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));
            }
            // Todos los productos
            else
                $arItem = $em->getRepository('BrasaInventarioBundle:InvItem')->findAll();
        }
        else {
            $arCiudades = $em->getRepository('BrasaGeneralBundle:GenCiudad')->findAll();
        }

        return $this->render('BrasaGeneralBundle:Busquedas:buscarCiudad.html.twig', array(
            "arCiudades" => $arCiudades,
            "campoCodigo" => $campoCodigo,
            "campoNombre" => $campoNombre));
    }    

    
    public function buscarEmpleadoAction() {
            $em = $this->getDoctrine()->getEntityManager();

            //$strEmpleado = $_GET["q"];
            $strEmpleado = "a";
            
            $arEmpleados = new \Brasa\RecursoHumanoBundle\Entity\RhuEmpleado();            
            //$Terceros = new \zikmont\FrontEndBundle\Entity\GenTerceros();
            $mm = $this->getRequest()->isXmlHttpRequest();
            if ($this->getRequest()->isXmlHttpRequest())
                $arEmpleados = $em->getRepository('BrasaRecursoHumanoBundle:RhuEmpleado')->buscarNombre($strEmpleado);

            foreach ($arEmpleados as $key => $value) {
                $array[] = $value->getNumeroIdentificacion() . "-" . trim($value->getNombreCorto());
            }
            
            //Falta controlar el error si no devuelve 
            if (!is_array($array)) {
                return false;
            }
            $construct = "";

            foreach ($array as $value) {

                // Format the value:
                if (is_array($value)) {
                    $value = array_to_json($value);
                } else if (!is_numeric($value) || is_string($value)) {
                    $value = $value;
                }

                $construct .= $value . "\n";
            }

            $construct = str_replace('Array', '', $construct);            
            return new \Symfony\Component\HttpFoundation\Response((string) $construct);            
            //return new JsonResponse(array('data' => 'this is a json response'));
            ////return new JsonResponse($arEmpleados);
            //return new Response(json_encode($array));
    }    
    
}
