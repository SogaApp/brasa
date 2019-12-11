<?php

function enviarCorreo($strMensaje = "", $arrDirecciones = "", $strAsunto = "") {
    if($arrDirecciones) {
        require('phpmailer/class.phpmailer.php');
        require('phpmailer/class.smtp.php');

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->From = "soganotificaciones@gmail.com";
        $mail->FromName = "Soga notificaciones";
        $mail->Subject = $strAsunto;
        $mail->AltBody = "";
        $mail->MsgHTML($strMensaje);        
        foreach ($arrDirecciones as $direccion) {
            $mail->AddAddress($direccion['direccion'], '');
        }
        $mail->SMTPAuth = true;
        $mail->Username = "soganotificaciones@gmail.com";
        $mail->Password = "70143086";

        if (!$mail->Send()) {
            echo "Error enviando: " . $mail->ErrorInfo;
        } else {
            echo "¡¡Enviado!!";
        }        
    }
}
?>




