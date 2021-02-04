<?php

     function consultaActiu($mail_user,$db){
         $sql = 'SELECT * FROM users WHERE active = ? AND (mail= ? OR username = ?)';
         $preparada = $db->prepare($sql);
         $preparada->execute(array(1,$mail_user,$mail_user));
        
        return $preparada->fetchAll(PDO::FETCH_ASSOC); //NOTE retorna si hi ha alguna fila activa amb l'usuari o mail introduït
    }

    function consultaPwd($mail_user,$db){
        $sql ="SELECT passHash FROM users WHERE mail= ? OR username = ?";
        $contra = $db->prepare($sql);
        $contra->execute(array($mail_user,$mail_user));
        foreach ($contra as $fila) {
            $pwdHash=$fila['passHash'];
        } 
        return $pwdHash; ///NOTE Retornarem el hash amb el que verificarem la contrasenya
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

