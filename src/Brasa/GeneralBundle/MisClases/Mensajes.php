<?php
namespace Brasa\GeneralBundle\MisClases;

use Symfony\Component\HttpFoundation\Request;

class Mensajes {

    /**
     * Construye los parametros requeridos para generar un mensaje
     * @param string $strTipo El tipo de mensaje a generar  se debe enviar en minuscula <br> error, informacion
     * @param string $strMensaje El mensaje que se mostrara
     * @param string $vista la vista donde se mostrara el mensaje
     */
    public function Mensaje($strTipo, $strMensaje, $vista) {
        $vista->get('session')->getFlashBag()->add($strTipo, $strMensaje);                
    }
}
?>

