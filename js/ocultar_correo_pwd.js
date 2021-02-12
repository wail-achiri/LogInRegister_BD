
//NOTE FUNCIO QUE ENS PERMETRA OCULTAR TANT EL CORREO I CONTRASENYA A L'HORA DE FER EL LOGIN
function ocultar(){ 
    var correo = document.getElementById("login-input-user");
    var pwd = document.getElementById("login-input-password");
    
    var correo_hidde = document.getElementById("email_user");
    var pwd_hidde = document.getElementById("pwd_hidde");
    
    correo_hidde.value=correo.value;
    pwd_hidde.value = pwd.value;

}

//NOTE FUNCIO PER OCULTAR LAS CONTRASENYAS DEL SCRIPT RESETPASSWORD.PHP
function ocultarPwd(){ 
    var pwdPrimer = document.getElementById("input-password-primer");
    var pwd_hidde_primer = document.getElementById("input-hidde-primer");
    
    var pwdSegon = document.getElementById("input-password-segon");
    var pwd_hidde_segon = document.getElementById("input-hidde-segon");

    pwd_hidde_primer.value=pwdPrimer.value;
    pwd_hidde_segon.value = pwdSegon.value;

    console.log(pwd_hidde_primer.value);
    console.log(pwd_hidde_segon.value);

}