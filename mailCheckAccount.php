<?php
    require_once('./db/connecta_db.php');
    require_once('./lib/funcions.php');
    if(isset($_GET['code']) && isset($_GET['mail'])){
        if(!empty($_GET['code']) && !empty($_GET['mail'])){
            $codi_check=filter_input(INPUT_GET,"code",FILTER_SANITIZE_SPECIAL_CHARS);
            $mail_check=filter_input(INPUT_GET,"mail",FILTER_SANITIZE_SPECIAL_CHARS);
            try{
                $lineas = consultarMailCode($codi_check,$mail_check,$db);  
                if($lineas){
                    $entrat = actualitzatActiveCode($mail_check,$db);     
                    if($entrat){
                        header("Location: index.php?verified=true");
                    }
                }else{
                    header("Location: index.php?verified=false");
                }
            }catch(PDOException $e){
                echo 'Error amb la BDs: ' . $e->getMessage();
            }
        }
    }

    