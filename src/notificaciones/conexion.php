<?php
    require_once('spyc/spyc.php');
    if(isset($argv[1])) {
        $data = Spyc::YAMLLoad($argv[1]);                   
    } else {
        $data = Spyc::YAMLLoad('../../app/config/parameters.yml');                   
    }    
    $arrParametros = $data['parameters'];    
    $host = $arrParametros['database_host'];
    $usuario = $arrParametros['database_user'];
    $clave = $arrParametros['database_password'];
    $baseDatos = $arrParametros['database_name'];
    $servidor = new mysqli($host, $usuario, $clave, $baseDatos);
    if ($servidor->connect_error) {
        die("Fallo la conexion: " . $servidor->connect_error);
    }
    

