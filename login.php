<?php
require_once 'bootstrap.php';

if(isset($_POST["username"]) && isset($_POST["password"])){
    $login_result = $dbh->login($_POST["username"], $_POST["password"]);
    if(count($login_result)==0){
        //Login fallito
        $templateParams["errorelogin"] = "Errore! Controllare username o password!";
    }
    else{
        registerLoggedUser($login_result[0]);
    }
}

if(isUserLoggedIn()){
    $templateParams["title"] = "Ratatweet - User"; 
    header 
    if(isset($_GET["caller"])) $templateParams["main"] = $_GET["caller"];
    else $templateParams["main"] = "user.php";
}
else{
    $templateParams["title"] = "Ratatweet - Login";
    $templateParams["main"] = "login_form.php";
}

require 'template/base.php';
?>