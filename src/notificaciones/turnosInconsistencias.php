<?php

ini_set('display_errors', 1);
include('mail.php');
include('direcciones.php');
include('conexion.php');
$dateFecha = new DateTime('now');
$strAnio = $dateFecha->format('Y');
$strMes = $dateFecha->format('m');
$strUltimoDiaMes = date("d",(mktime(0,0,0,$strMes+1,1,$strAnio)-1));

$strMensaje = "<br />Este es un correo enviado automaticamente desde AppSoga para informarle circunstancias precisas de su interes <br /><br />";
$strMensaje .= "Modulo: [Turnos] <br />";
$strMensaje .= "Destinatario: [Jefe operaciones]<br /><br />";

//Recursos sin programaciones
$arrRecursosSinProgramacion = array();
$strSql = "SELECT codigo_recurso_pk, nombre_corto "
        . "FROM tur_recurso WHERE estado_activo = 1";
$arRecursos = $servidor->query($strSql);
if ($arRecursos->num_rows > 0) {
    while ($arRecurso = $arRecursos->fetch_assoc()) {
        $strSql = "SELECT codigo_programacion_detalle_pk "
                . "FROM tur_programacion_detalle WHERE anio = $strAnio AND mes = $strMes AND codigo_recurso_fk = " . $arRecurso['codigo_recurso_pk'];
        $arProgramacionDetalles = $servidor->query($strSql);
        if ($arProgramacionDetalles->num_rows <= 0) {
            $arrRecursosSinProgramacion[] = array(
                'codigo' => $arRecurso['codigo_recurso_pk'],
                'nombre' => $arRecurso['nombre_corto']
            );
        }
    }
}
if ($arrRecursosSinProgramacion) {
    $strMensaje .= "Los siguientes recursos no tienen programacion para el mes $strMes año $strAnio<br /><br />";
    $strMensaje .= "<table border='2'>";
    $strMensaje .= "<tr>";
    $strMensaje .= "<th>CODIGO</th>";
    $strMensaje .= "<th>NOMBRE</th>";
    $strMensaje .= "</tr>";
    foreach ($arrRecursosSinProgramacion as $recurso) {
        $strMensaje .= "<tr>";
        $strMensaje .= "<td>" . $recurso['codigo'] . "</td>";
        $strMensaje .= "<td>" . $recurso['nombre'] . "</td>";
        $strMensaje .= "</tr>";
    }
    $strMensaje .= "</table><br /><br />";
}

//Turnos dobles
$arrTurnosDobles = array();
for ($i = 1; $i <= $strUltimoDiaMes; $i++) {
    $strSql = "SELECT
                codigo_recurso_fk as codigo_recurso_fk, 
                tur_recurso.nombre_corto as nombre_corto,                                                                   
                COUNT(dia_$i) AS numero
                FROM
                tur_programacion_detalle
                LEFT JOIN tur_recurso ON tur_programacion_detalle.codigo_recurso_fk = tur_recurso.codigo_recurso_pk                                
                WHERE
                dia_$i IS NOT NULL AND anio = $strAnio AND mes = $strMes
                GROUP BY
                codigo_recurso_fk";
    $arTurnosDobles = $servidor->query($strSql);
    if ($arTurnosDobles->num_rows > 0) {
        while ($arTurnosDoble = $arTurnosDobles->fetch_assoc()) {
            if($arTurnosDoble['numero'] > 1) {
                $arrTurnosDobles[] = array(
                    'codigo' => $arTurnosDoble['codigo_recurso_fk'],
                    'nombre' => $arTurnosDoble['nombre_corto'],
                    'dia' => $i
                );                  
            }          
        }
    }    
}
if($arrTurnosDobles) {
    $strMensaje .= "Los siguientes recursos tienen doble asignacion de turno para el mes $strMes año $strAnio<br /><br />";
    $strMensaje .= "<table border='2'>";
    $strMensaje .= "<tr>";
    $strMensaje .= "<th>CODIGO</th>";
    $strMensaje .= "<th>NOMBRE</th>";
    $strMensaje .= "<th>DIA</th>";
    $strMensaje .= "</tr>";
    foreach ($arrTurnosDobles as $recurso) {
        $strMensaje .= "<tr>";
        $strMensaje .= "<td>" . $recurso['codigo'] . "</td>";
        $strMensaje .= "<td>" . $recurso['nombre'] . "</td>";
        $strMensaje .= "<td>" . $recurso['dia'] . "</td>";
        $strMensaje .= "</tr>";
    }
    $strMensaje .= "</table><br /><br />";    
}

// Recursos sin turnos
$arrSinTurnos = array();
for ($i = 1; $i <= $strUltimoDiaMes; $i++) {
    $strSql = "SELECT codigo_recurso_pk FROM tur_recurso WHERE estado_activo = 1";
    $arRecursos = $servidor->query($strSql);
    while ($arRecurso = $arRecursos->fetch_assoc()) {
        $codigoRecurso = $arRecurso['codigo_recurso_pk'];
        $strSql = "SELECT
                        codigo_recurso_fk AS codigoRecursoFk, 
                        tur_recurso.nombre_corto AS nombreCorto,
                        tur_recurso.numero_identificacion AS numeroIdentificacion,
                        COUNT(dia_$i) AS numero
                        FROM
                        tur_programacion_detalle
                        LEFT JOIN tur_recurso ON tur_programacion_detalle.codigo_recurso_fk = tur_recurso.codigo_recurso_pk                                
                        WHERE
                        anio = $strAnio AND mes = $strMes AND codigo_recurso_fk = $codigoRecurso
                        GROUP BY
                        codigo_recurso_fk";   
        $arProgramacionesDetalles = $servidor->query($strSql);
        if ($arProgramacionesDetalles->num_rows > 0) {
            while ($arProgramacionDetalle = $arProgramacionesDetalles->fetch_assoc()) {
                if($arProgramacionDetalle['numero'] <= 0) {
                    $arrSinTurnos[] = array(
                    'codigo' => $arProgramacionDetalle['codigoRecursoFk'],
                    'identificacion' => $arProgramacionDetalle['numeroIdentificacion'],
                    'nombre' => $arProgramacionDetalle['nombreCorto'],
                    'dia' => $i
                );                  
                }
            }
        }
    }       
}
if($arrSinTurnos) {
    $strMensaje .= "Los siguientes recursos no tienen turno asignado el mes $strMes año $strAnio<br /><br />";
    $strMensaje .= "<table border='2'>";
    $strMensaje .= "<tr>";
    $strMensaje .= "<th>CODIGO</th>";
    $strMensaje .= "<th>IDENTIFICACION</th>";
    $strMensaje .= "<th>NOMBRE</th>";
    $strMensaje .= "<th>DIA</th>";
    $strMensaje .= "</tr>";
    foreach ($arrSinTurnos as $recurso) {
        $strMensaje .= "<tr>";
        $strMensaje .= "<td>" . $recurso['codigo'] . "</td>";
        $strMensaje .= "<td>" . $recurso['identificacion'] . "</td>";
        $strMensaje .= "<td>" . $recurso['nombre'] . "</td>";
        $strMensaje .= "<td>" . $recurso['dia'] . "</td>";
        $strMensaje .= "</tr>";
    }
    $strMensaje .= "</table><br /><br />";    
}

//Enviar mensaje
if ($arrRecursosSinProgramacion || $arrTurnosDobles || $arrSinTurnos) {
    $strMensaje .= "Por favor no conteste este mensaje, para comunicarse con servicio al cliente marque 4448120 ext 131<br /><br />";
    $strMensaje = utf8_decode($strMensaje);
    $arrDirecciones = direcciones('correo_turno_inconsistencia', $servidor);
    if ($arrDirecciones) {
        //echo $strMensaje;
        enviarCorreo($strMensaje, $arrDirecciones,"Inconsistencias recursos [SogaApp-turnos]");         
    }
}


