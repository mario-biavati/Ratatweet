<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/user.js");
$templateParams["title"] = "Ratatweet - User"; 
if (isset($_GET["id"])) {
    //$templateParams["header"] = "header_short.html";
    $templateParams["main"] = "user.php";
    $templateParams["user"] = $_GET["id"];
}
else if(!isUserLoggedIn()) {
    //$templateParams["header"] = "header_short.html";
    $templateParams["main"] = "login_form.php";
    array_push($templateParams["js"], "js/login.js");
}
else {
    //$templateParams["header"] = "header_short_logout.html";
    $templateParams["main"] = "user.php";
    $templateParams["user"] = $_SESSION["idUser"];
}

require_once("template/base.php");
?>