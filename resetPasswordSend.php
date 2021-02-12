<?php 
    require_once('./db/connecta_db.php');
    require_once('./lib/funcions.php');
    $codi_hash = hash('sha256',rand());
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $email_user = filter_input(INPUT_POST,"reset_usermail",FILTER_SANITIZE_SPECIAL_CHARS);
        $lineas = consultarExisteix($email_user,$email_user,$db); //NOTE REUTILITZO LA FUNCIÓ DE LOGIN PER COMPROBAR L'USUARI O EL MAIL
        if($lineas){  
            $consulta= actualitzarCodeExpPass($codi_hash,$email_user,$db);
            if($consulta){
                require_once('./mail/mailpwdSender.php');
                header("Location:index.php?enviat_reset");
            }
        }else{
            header("Location:index.php?noexist");
        }
    }
?>