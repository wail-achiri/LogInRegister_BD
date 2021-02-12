<?php 
    require_once('./db/connecta_db.php');
    require_once('./lib/funcions.php');
    require_once('./lib/modal.php');
    session_start();
    $errors = array();
    if(!isset($_SESSION['user']) || !isset($_SESSION['mail'])){ //NOTE ens asegurem que no entri un usuari ja amb login
      if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["email_user_hidde"]) && isset($_POST["pwd_hidde"])){
          if(!empty($_POST["email_user_hidde"]) || !empty($_POST["pwd_hidde"])){
            $user_email=filter_input(INPUT_POST,"email_user_hidde",FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST,"pwd_hidde",FILTER_SANITIZE_SPECIAL_CHARS);
            try{
              $lineas =consultaActiu($user_email,$db);
              if($lineas){ //NOTE MIRARA SI L'USUARI ESTA ACTIU O NO
                if(consultaPwd($user_email,$db,$password)){ //NOTE FUNCIO QUE COMPROBARA SI LA CONTRASENYA ES CORRECTA
                  $actualitzat=actualitzarTemps($user_email,$db);
                  if($actualitzat){ //NOTE SI S'HA ACTUALITZAT CORRECTAMENT
                    session_start();
                    $dades = obtenirUserMail($db);
                    $dades->execute(array($user_email,$user_email));
                    foreach($dades as $fila){
                      $_SESSION['mail'] = $fila['mail'];
                      $_SESSION['user'] = $fila['username'];
                    }
                      header("Location: home.php");
                    }else{
                      array_push($errors,"<b class='errors'>¡ERROR INESPERAT!</b>");
                    }
                }else{
                  array_push($errors,"<b class='errors'>¡L'usuari/mail o contrasenya són <strong class='error'>incorrectes!</strong></b>");
                }
              }else{
                array_push($errors,"<b class='errors'>¡Aquest usuari/mail <strong class='error'>no esta registrat o activat!</strong></b>");
              }
            }catch(PDOException $e){
              echo 'Error amb la BDs: ' . $e->getMessage();
            }
          }else{
            array_push($errors,"<b class='errors'>¡Un dels camps estan <strong class='error'>buits!</strong></b>");   
          }
        }
      }
      if(isset($_GET["noexist"])){
        array_push($errors,"<b class='errors'>¡No es pot recuperar la contrasenya, perque no existeix  <strong class='error'>la compte!</strong></b>");
      }
    }else{
      header("Location:home.php");
    }
?>
<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="Wail El Achiri Naimi">
        <meta name="description" content="Pràctica PHP Log In Base de Dades">
        <title>Inicia Sessió</title>
        <meta name="description" content="HTML QUE PERMETRA LOGIN" >
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
          <form form onsubmit="return ocultar()" class="form-login" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
           <?php 
              if(isset($errors)){
                mostrarErrors($errors);
              }
           ?>
            <ul class="nav">
              <li class="nav__item active">
                <p>Entra</p>
              </li>
            </ul>
            <label for="login-input-user" class="login__label">
                Username / Mail
            </label>
            <input id="login-input-user" class="login__input" type="text" />
            <input id="email_user" type="hidden" class="login__input" name="email_user_hidde">
            <label for="login-input-password" class="login__label">
              Password
            </label>
            <input id="login-input-password" class="login__input" type="password" />
            <input id="pwd_hidde" type="hidden" class="login__input" name="pwd_hidde">
            <button class="login__submit" type="submit">Iniciar Sessió</button>
          </form>
          <a href="register.php" class="login__forgot">¿Encara no tens compte? Registra't</a>
          <a href="#" data-target="#pwdModal" data-toggle="modal" class="login__forgot">¿Has oblidat la contrasenya?</a>
        </div>
        <!--NOTE FORM QUE S'ENCARREGARA D'ENVIAR EL CORREO PER FER EL RESET-->
        <form action="resetPasswordSend.php" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
          <?php echo $modal_reset; ?> 
        </form>
        <?php //NOTE CONDICIONALS QUE ENS MOSTRARAN ELS MODALS DEPENENT DEL GET 
          if(isset($_GET['verified'])){
            if(!empty($_GET['verified'])){
              if($_GET['verified']=='true'){
                echo $modal_verificat;
              }else{
                echo $modal_error;
              }    
              $mostrar=true;
            }
          }
          if(isset($_GET["reset"])){
            echo $modal_canviat;
            $mostrar = true;
          }

          if(isset($_GET["enviat_reset"])){
            echo $modal_correu_enviat ;
            $mostrar = true;
          }

          if(isset($mostrar) && $mostrar){
            echo "<script src='./js/obrirModal.js'></script>";    
          }   
        ?>
      
    </body>
    <script src="./js/ocultar_correo_pwd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</html>