<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/check_notifications.js", "js/user.js");
$templateParams["title"] = "Ratatweet - User"; 
if (isset($_GET["id"]) || isUserLoggedIn()) {
    if (isset($_GET["id"])) {                                   //pagina di un altro utente
        $templateParams["header"] = "header_short.html";
        $templateParams["main"] = "user.php";
        $templateParams["user"] = $_GET["id"];
    } else {                                                    //tua pagina
        $templateParams["header"] = "header_short_logout.html";
        $templateParams["main"] = "user.php";
        $templateParams["user"] = $_SESSION["idUser"];
    }
    
    $id = $templateParams["user"];
    $userData = $dbh->getUserById($id);
    $userPosts = $dbh->getUserPosts($id, -1);
    $userStat = $dbh->getUserStats($id);
    if (count($userData) == 1 && $userData[0]["IDuser"]!=1) {
        $userData = $userData[0];
        $userStat = $userStat[0];
    } else {
        $templateParams["main"] = "error.html";                 //accede all'utente ANONIMO oppure un utente che non esiste, bloccato
    }
} 
else {                                                          //non loggato: login
    $templateParams["header"] = "header_short.html";
    $templateParams["main"] = "login_form.php";
    array_push($templateParams["js"], "js/login.js");
}

require_once("template/base.php");
?>