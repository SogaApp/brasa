<?php
namespace Brasa\GeneralBundle\MisClases;

use Symfony\Component\HttpFoundation\Request;

class Funciones {

    /**
     * Construye los parametros requeridos para generar un mensaje
     * @param string $strTipo El tipo de mensaje a generar  se debe enviar en minuscula <br> error, informacion
     * @param string $strMensaje El mensaje que se mostrara
     * @param string $vista la vista donde se mostrara el mensaje
     */
    public function devuelveBoolean($dato) {
        $strResultado = "";
        if($dato == TRUE) {
            $strResultado = 'SI';
        } else {
            $strResultado = 'NO';
        }
        return $strResultado;                
    }
}
?>
