<?php 
    require_once('./db/connecta_db.php');
    require_once('./php/modal.php');
    require_once('./php/funcions_register.php');
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['mail'])){
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $errors = array();
            $errors=comprobacioErrors($errors);
            if(count($errors)==0){//NOTE mirem que no tinguem cap error
                //NOTE filtrarem per si hi ha algún caracter especial
                $username=filter_input(INPUT_POST,"username",FILTER_SANITIZE_SPECIAL_CHARS);
                $email=filter_input(INPUT_POST,"email",FILTER_SANITIZE_SPECIAL_CHARS);
                $nom=filter_input(INPUT_POST,"nom",FILTER_SANITIZE_SPECIAL_CHARS);
                $cognom=filter_input(INPUT_POST,"cognom",FILTER_SANITIZE_SPECIAL_CHARS);
                $password=filter_input(INPUT_POST,"contra",FILTER_SANITIZE_SPECIAL_CHARS);

                $password = password_hash($password,PASSWORD_DEFAULT); //NOTE convertirem la contrasenya en hash
                $lineas = consultarExisteix($username,$email,$db);
                if($lineas){
                    array_push($errors,"<b class='errors'>¡L'username o email ja <strong class='error'>estan registrats!</strong></b>");
                }else{
                    $inserit = consultaInserirRegistre($email,$username,$password,$nom,$cognom,$db);
                    if($inserit){//NOTE si s'ha inserit correctament
                        $ok=true; //NOTE variable que ens permetra mostra el modal
                    }else{
                        array_push($errors,"<b class='errors'>¡No s'ha pogut registrar correctament!</b>");
                    }
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
        <meta name="description" content="HTML QUE PERMETRA EL REGISTRE">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registra</title>
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
          <form class="form-register" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
            <?php if(isset($errors)){ //NOTE mostrem els errors si en tenim
              if(count($errors)!=0){
                foreach($errors as $value){
                  echo $value;
                  echo "<br>";
                }
              }
            }?>
            <ul class="nav">
              <li class="nav__item active">
                <p>Registrat</p>
              </li>
            </ul>
            <label for="login-input-user" class="login__label">
                USERNAME
            </label>
            <input id="login-input-user" class="login__input" type="text" name="username"/>
            <label for="login-input-email" class="login__label">
              EMAIL
            </label>
            <input id="login-input-email" class="login__input" type="email" name="email" />
            <label for="login-input-name" class="login__label">
                NOM
            </label>
            <input id="login-input-name" class="login__input" type="text" name="nom" />
            <label for="login-input-lastname" class="login__label">
                COGNOM
            </label>
            <input id="login-input-lastname" class="login__input" type="text"  name="cognom"/>
            <label for="login-input-password" class="login__label">
                PASSWORD
            </label>
            <input id="login-input-password" class="login__input" type="password" name="contra" />
            <label for="login-input-verifyPass" class="login__label">
                REPETEIX LA CONTRASENYA
            </label>
            <input id="login-input-verifyPass" class="login__input" type="password" name="clon_pwd" />
            
            <button class="register__submit" type="submit">Registrarse</button>
          </form>
        </div>
        
    </body>
    <?php //NOTE si s'ha registrat correctament, mostrara el modal i activara el jquery
        if(isset($ok) && $ok){
            echo $modal;
            echo "<script src='./js/obrirModal.js'></script>";
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</html>