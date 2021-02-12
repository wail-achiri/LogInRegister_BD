<?php
  require_once('./lib/funcions.php');
  require_once("./db/connecta_db.php");
  require_once("./lib/modal.php");
  if(isset($_GET["code"])){
      if(isset($_GET["mail"])){
        $mail_user = htmlspecialchars($_GET["mail"]);
      }else if(isset($_GET["user"])){
        $mail_user = htmlspecialchars($_GET["user"]);
      }
      $code = htmlspecialchars($_GET["code"]);
      
      $dades = array("codi" => $code, "mail_user"=> $mail_user);
      setcookie("parametres",json_encode($dades),time() + 60 * 30);
  }
  $errors = array();
  if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_COOKIE["parametres"])){
        if(isset($_POST["pwd_hidde_primer"]) && isset($_POST["pwd_hidde_segon"])){
          $pwd_primer=filter_input(INPUT_POST,"pwd_hidde_primer",FILTER_SANITIZE_SPECIAL_CHARS);
          $pwd_segon=filter_input(INPUT_POST,"pwd_hidde_segon",FILTER_SANITIZE_SPECIAL_CHARS);
          
          $dades = json_decode($_COOKIE["parametres"]);
          cambiarVariables($code,$mail_user,$dades); //NOTE OBTINDREM DE L'ARRAY DE DADES EL CODI I EL MAIL
          if(!empty($pwd_primer) || !empty($pwd_segon)){
              if($pwd_primer==$pwd_segon){ //NOTE COMPROBEM QUE SIGUIN IGUALS LAS CONTRASENYAS QUE VOL CANVIAR
                
                $existeixReset = obtenirExisteixReset($mail_user,$db);
                $caducitat = obtenirCaducitat($mail_user,$db);

                if($existeixReset && $caducitat){ //NOTE COMPROBEM SI EXISTEIX EL CODI I CORREO RESET I SI NO HA CADUCAT
                    actualitzarContra($db,$mail_user,$pwd_primer);
                    require_once("./mail/mailconfirmReset.php");
                    header("Location:index.php?reset");
                }else{
                    anularVerificacio($db,$mail_user);
                    $fatal = true;
                }
                setcookie("parametres", "", time() - 36000 ); //NOTE ELIMINAREM LA COOKIE
              }else{
                array_push($errors,"<b class='errors'>¡Las contrasenyas <strong class='error'>no coincideixen!</strong></b>");
              }
          }else{
            array_push($errors,"<b class='errors'>¡Els camps de password <strong class='error'>estan buits!</strong></b>");
          }
        }
     }
  }
?>
<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="Wail El Achiri Naimi">
        <meta name="description" content="Pràctica PHP Log In Base de Dades">
        <title>Recuperar contrasenya</title>
        <meta name="description" content="HTML QUE PERMETRA RECUPERAR CONTRASENYA" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="short icon" type="image/png" href="./img/favicon.png">
        <link rel="stylesheet" href="./style/style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    </head>
    <body>
      <div class="logo">
        <img src="./img/logo.png" width="150" height="100" >
      </div>
      <div class="login-container">
        <form form onsubmit="return ocultarPwd()" class="form-login" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
          <?php 
              if(isset($errors)){
                mostrarErrors($errors);
              }
           ?>
          <ul class="nav">
            <li class="nav__item active">
              <p>Recupera Contrasenya</p>
            </li>
          </ul>
          <label class="login__label">
              Contrasenya
          </label>
          <input id="input-password-primer" class="login__input" type="password" />
          <input id="input-hidde-primer" type="hidden" class="login__input" name="pwd_hidde_primer">
          <label class="login__label">
            Torna a escriure la contrasenya
          </label>
          <input id="input-password-segon" class="login__input" type="password" />
          <input id="input-hidde-segon" type="hidden" class="login__input" name="pwd_hidde_segon">
          <button class="login__submit" type="submit">Actualitzar</button>
        </form>
      </div>
      <?php 
        if(isset($fatal) && $fatal){
          echo $modal_error_reset;
          echo "<script src='./js/obrirModal.js'></script>";
        }
      ?>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    
    <script src="./js/ocultar_correo_pwd.js"></script>
</html>