<?php
    session_start();
    if(!isset($_SESSION['user']) || !isset($_SESSION['mail'])){
      header("Location: index.php");
    }

?>
<!DOCTYPE html>
<html lang="ca">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="WAIL EL ACHIRI NAIMI">
    <meta name="description" content="HTML QUE MOSTRARARA LA BENVINGUDA" >
    <title>Hola <?php echo $_SESSION['user']; ?></title>
    <link rel="stylesheet" href="./style/home.css" />
    <link rel="short icon" type="image/png" href="./img/favicon.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous"/>
  </head>
  <body>
    <!--NAVBAR-->
    <nav class="navbar">
      <div class="navbar__container">
        <a href="#home" id="navbar__logo"><img src="./img/logo.png" width="120" height="70" ></a>
        <div class="navbar__toggle" id="mobile-menu">
          <span class="bar"></span> <span class="bar"></span>
          <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
          <li class="navbar__item">
            <a href="#mail" class="navbar__links separate_mail" id="home-page"><?php echo $_SESSION['mail']; ?></a>
          </li>
          <li class="navbar__item">
            <a href="#user" class="navbar__links separate_user" id="services-page" ><?php echo $_SESSION['user']; ?></a>
          </li>
        </ul>
      </div>
    </nav>
    <!--WELCOME-->
    <div class="welcome" id="home">
      <div class="welcome__container">
        <h1 class="heading">Benvingut <span><?php echo $_SESSION['user']; ?></span></h1>
        <p class="description">IMAGINEST</p>
        <button class="main__btn"><a href="logout.php">Log out</a></button>
      </div>
    </div>

  </body>
</html>