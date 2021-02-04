function ocultar(){ 
    var correo = document.getElementById("login-input-user");
    var pwd = document.getElementById("login-input-password");
    
    var correo_hidde = document.getElementById("email_user");
    var pwd_hidde = document.getElementById("pwd_hidde");
    
    correo_hidde.value=correo.value;
    pwd_hidde.value = pwd.value;

}