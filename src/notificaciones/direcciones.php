<?php
ini_set('display_errors', 1);
function direcciones($strCampo, $servidor) {
    $arrDireccionesDevolver = array();
    $strSql = "SELECT $strCampo "
            . "FROM gen_configuracion_notificaciones WHERE codigo_configuracion_notificaciones_pk = 1";
    $arConfiguracionNotificaciones = $servidor->query($strSql);    
    $arConfiguracionNotificacion = $arConfiguracionNotificaciones->fetch_assoc();    
    if($arConfiguracionNotificacion['correo_turno_inconsistencia'] != '') {
        $arrDirecciones = explode(",", $arConfiguracionNotificacion['correo_turno_inconsistencia']);
        foreach ($arrDirecciones as $direccion) {
            $arrDireccionesDevolver[] = array('direccion' => $direccion);
        }
    }    
    return $arrDireccionesDevolver;
}

