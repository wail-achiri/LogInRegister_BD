<?php 
    require_once('./db/connecta_db.php');
    require_once('./php/funcions_login.php');
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
              if($lineas){
                    $pwdHash=consultaPwd($user_email,$db);
                    if(password_verify($password,$pwdHash)){
                      $actualitzat=actualitzarTemps($user_email,$db);
                      if($actualitzat){
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
                array_push($errors,"<b class='errors'>¡Aquest usuari/mail <strong class='error'>no esta registrat!</strong></b>");
              }
            }catch(PDOException $e){
              echo 'Error amb la BDs: ' . $e->getMessage();
            }
          }else{
            array_push($errors,"<b class='errors'>¡Un dels camps estan <strong class='error'>buits!</strong></b>");   
          }
        }
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
    </head>
    <body>
      <div class="logo">
        <img src="./img/logo.png" width="150" height="100" >
      </div>
      <div class="login-container">
          <form form onsubmit="return ocultar()" class="form-login" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
           <?php if(isset($errors)){
              if(count($errors)!=0){
                foreach($errors as $value){
                  echo $value;
                  echo "<br>";
                }
              }
            }?>
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
          <a href="register.php" class="login__forgot">Encara no tens compte ? Registra't</a>
        </div>
        <script src="./js/ocultar_correo_pwd.js"></script>
    </body>
</html>