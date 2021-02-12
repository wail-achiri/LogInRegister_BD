<?php
    //TODO MAIL QUE ENS CONFIRMARA PER CORREO QUE S'HA CANVIAT LA CONTRASENYA
    use PHPMailer\PHPMailer\PHPMailer;
    require 'vendor/autoload.php'; ///error
    $mail = new PHPMailer();
    $mail->IsSMTP();

    //Configuració del servidor de Correu
    //Modificar a 0 per eliminar msg error
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    
    
    //Credencials del compte GMAIL
    $mail->Username = 'welachiri2021@educem.net';
    $mail->Password = '';

    //Dades del correu electrònic
    $mail->SetFrom('imagin@imaginest.com','Imaginest');
    $mail->Subject = 'Confirmacio contrasenya actualitzada';
    $mail->addEmbeddedImage('./img/logo.png','logomail'); 
    $mail->MsgHtml("<h1 style='text-align: center;'>IMAG<strong style='color:#fc00ff'>INEST</strong></h1><div style='text-align: center;'><img width='700' height='450' src='cid:logomail'/></div></p><h2 style='text-align: center;'>La contrasenya ha sigut cambiada<strong style='color:#fc00ff'> correctament.</strong><a style='color:#fc00ff' href=' http://localhost/ExercicisClasse/Practicas/5-LogInBaseDades/index.php'> Entra!</a></h2> ");
    
    //Destinatari
    $address = 'welachiri2021@educem.net';
    $mail->AddAddress($address, 'Test');

    //Enviament
    $result = $mail->Send();
    if(!$result){
        echo 'Error: ' . $mail->ErrorInfo;
    }