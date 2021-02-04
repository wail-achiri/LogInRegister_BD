<?php
    
    function comprobacioErrors($error){
      if(empty($_POST["username"])){
        array_push($error,"<b class='errors'>¡El camp <strong class='error'>usuari</strong> esta buit!</b>");
      }
      if(empty($_POST["email"])){
        array_push($error,"<b class='errors'>¡El camp <strong class='error'>correo</strong> esta buit!</b>");
      }
      if(!filter_var($_POST["email"],FILTER_VALIDATE_EMAIL)){
        array_push($error,"<b class='errors'>¡El correo <strong class='error'>no es valid!</strong></b>");
      }
      if(empty($_POST["nom"])){
        array_push($error,"<b class='errors'>¡El camp <strong class='error'>nom</strong> esta buit!</b>");
      }
      if(empty($_POST["cognom"])){
        array_push($error,"<b class='errors'>¡El camp <strong class='error'>cognom</strong> esta buit!</b>");
      }
      if(empty($_POST["contra"])){
        array_push($error,"<b class='errors'>¡El camp <strong class='error'>contrasenya</strong> esta buit!</b>");
      }
      if(empty($_POST["clon_pwd"])){
        array_push($error,"<b class='errors'>¡El camp repetir <strong class='error'>contrasenya</strong> esta buit!</b>");
      }
      if($_POST["contra"]!=$_POST["clon_pwd"]){
        array_push($error,"<b class='errors'>¡Las contrasenyas <strong class='error'>no coincideixen!</strong></b>");
      }      
      return $error; //NOTE retornarem els errors que tinguem
    }

    
    function consultarExisteix($user,$mail,$db){
        $sql = "SELECT * FROM users where mail= ? or username= ?";
        $consulta = $db->prepare($sql);
        $consulta->execute(array($mail,$user));
        return $consulta->fetchAll(PDO::FETCH_ASSOC); //NOTE retornara las filas que ha trobat amb les dades
    }
    
    function consultaInserirRegistre($mail,$user,$pwd,$name,$lastname,$db){
        $sql = "INSERT INTO users(mail,username,passHash,userFirstName,userLastName,active) VALUES (?,?,?,?,?,?)";
        $consulta = $db->prepare($sql);
        return $consulta->execute(array($mail,$user,$pwd,$name,$lastname,1)); //NOTE retornara si ha fet correctament o no el insert 
    }
    


