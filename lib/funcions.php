<?php

    //NOTE FUNCIONS INDEX.PHP

    function consultaActiu($mail_user,$db){
        $sql = 'SELECT * FROM users WHERE active = ? AND (mail= ? OR username = ?)';
        $preparada = $db->prepare($sql);
        $preparada->execute(array(1,$mail_user,$mail_user));
       
       return $preparada->fetchAll(PDO::FETCH_ASSOC); //NOTE retorna si hi ha alguna fila activa amb l'usuari o mail introduït
   }

   function consultaPwd($mail_user,$db,$pwd){
       $sql ="SELECT passHash FROM users WHERE mail= ? OR username = ?";
       $contra = $db->prepare($sql);
       $contra->execute(array($mail_user,$mail_user));
       foreach ($contra as $fila) {
           $pwdHash=$fila['passHash'];
       } 
       return password_verify($pwd,$pwdHash);
   }

   function actualitzarTemps($mail_user,$db){
       $sql = "UPDATE users SET lastSignIn = current_timestamp() where mail = ? or username = ?";
       $actualitzar = $db->prepare($sql);
       return $actualitzar->execute(array($mail_user,$mail_user)); //NOTE retornara si ha sigut actualitzat o no
   }

   function obtenirUserMail($db){
       $sql = "SELECT mail,username FROM users WHERE mail= ? or username = ?";
       $dades = $db->prepare($sql);
       return $dades; //Retornarem les dades preparades
   }

   function mostrarErrors($errors_mostrar){
       if(count($errors_mostrar)!=0){
          foreach($errors_mostrar as $value){
           echo $value;
           echo "<br>";
          }
       }    
   }

   //NOTE FUNCIONS REGISTER.PHP

   function comprobacioErrors($error,$username,$email,$nom,$cognom,$password,$clon_pwd){
    if(empty($username)){
      array_push($error,"<b class='errors'>¡El camp <strong class='error'>usuari</strong> esta buit!</b>");
    }
    if(empty($email)){
      array_push($error,"<b class='errors'>¡El camp <strong class='error'>correo</strong> esta buit!</b>");
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
      array_push($error,"<b class='errors'>¡El correo <strong class='error'>no es valid!</strong></b>");
    }
    if(empty($nom)){
      array_push($error,"<b class='errors'>¡El camp <strong class='error'>nom</strong> esta buit!</b>");
    }
    if(empty($cognom)){
      array_push($error,"<b class='errors'>¡El camp <strong class='error'>cognom</strong> esta buit!</b>");
    }
    if(empty($password)){
      array_push($error,"<b class='errors'>¡El camp <strong class='error'>contrasenya</strong> esta buit!</b>");
    }
    if(empty($clon_pwd)){
      array_push($error,"<b class='errors'>¡El camp repetir <strong class='error'>contrasenya</strong> esta buit!</b>");
    }
    if($password!=$clon_pwd){
      array_push($error,"<b class='errors'>¡Las contrasenyas <strong class='error'>no coincideixen!</strong></b>");
    }      
    return $error; //NOTE retornarem els errors que tinguem
  }

  
  function consultarExisteix($user,$mail,$db){
      $sql = "SELECT * FROM users WHERE mail= ? OR username= ?";
      $consulta = $db->prepare($sql);
      $consulta->execute(array($mail,$user));
      return $consulta->fetchAll(PDO::FETCH_ASSOC); //NOTE retornara las filas que ha trobat amb les dades
  }
  
  function consultaInserirRegistre($mail,$user,$pwd,$name,$lastname,$codi,$db){
      $sql = "INSERT INTO users(mail,username,passHash,userFirstName,userLastName,active,activationCode) VALUES (?,?,?,?,?,?,?)";
      $consulta = $db->prepare($sql);
      return $consulta->execute(array($mail,$user,$pwd,$name,$lastname,0,$codi)); //NOTE retornara si ha fet correctament o no el insert 
  }


  //NOTE FUNCIONS MAILCHECKACCOUNT.PHP

  function consultarMailCode($code,$mail,$db){
    $sql = "SELECT * FROM users where mail= ? and activationCode= ?";
    $consulta = $db->prepare($sql);
    $consulta->execute(array($mail,$code));
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
    //NOTE retornara las filas que ha trobat amb les dades
}


function actualitzatActiveCode($mail,$db){
    $sql = "UPDATE users SET active = ?,activationDate= current_timestamp(), activationCode = ? where mail = ?";
    $actualitzar = $db->prepare($sql);
    return $actualitzar->execute(array(1,null,$mail));   
}


//NOTE FUNCIONS RESETPASSWORD.PHP

  //NOTE FUNCIOO QUE RETORNARA PER REFERENCIA ELS VALORS DE CADA VARIABLE
function cambiarVariables(&$codi,&$usuari,$dades){
    $codi=$dades->{'codi'};
    $usuari=$dades->{"mail_user"};
}


function obtenirExisteixReset($usuari,$db){
    $sql = "SELECT * FROM users WHERE resetPass = ? AND (mail= ? or username= ?) ";
    $preparada = $db->prepare($sql);
    $preparada->execute(array(1,$usuari,$usuari));
    return $preparada->fetchAll(PDO::FETCH_ASSOC);
}


function obtenirCaducitat($usuari,$db){
    $sql = "SELECT * FROM USERS WHERE now()<resetPassExpiry and (mail = ? or username = ?)";
    $preparada = $db->prepare($sql);
    $preparada->execute(array($usuari,$usuari));
    return $preparada->fetchAll(PDO::FETCH_ASSOC);
}


function anularVerificacio($db,$usuari){
    $sql = "UPDATE users SET resetPassCode = ?,resetPassExpiry = ?, activationCode = ?, resetPass = ? WHERE mail = ? OR username = ?";
    $actualitzar = $db->prepare($sql);
    $actualitzar->execute(array(null,null,null,$usuari,$usuari));
}

function actualitzarContra($db,$usuari,$pwd){
    
    $pwd_hash = password_hash($pwd,PASSWORD_DEFAULT);
    $sql = "UPDATE users SET passHash = ? WHERE mail = ? or username = ?";
    $actualitzar = $db->prepare($sql);
    $actualitzar->execute(array($pwd_hash,$usuari,$usuari));
}


//NOTE FUNCIONS RESETPASSWORDSEND.PHP

function actualitzarCodeExpPass($codi,$usuari,$db){
  $sql = "UPDATE USERS set  resetPassCode = ?, resetPass = ? , resetPassExpiry= DATE_ADD(now(), INTERVAL 30 MINUTE) where username = ? or mail = ?";
  $consulta = $db->prepare($sql);
  return $consulta->execute(array($codi,1,$usuari,$usuari));
}







