<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/user.js");
$templateParams["title"] = "Ratatweet - User"; 
//$templateParams["header"] = "header_long.html";
if (isset($_GET["id"])) {
    $templateParams["main"] = "user.php";
    $templateParams["user"] = $_GET["id"];
}
else if(!isUserLoggedIn()) {
    $templateParams["main"] = "login_form.php";
    array_push($templateParams["js"], "js/login.js");
}
else {
    $templateParams["main"] = "user.php";
    $templateParams["user"] = $_SESSION["idUser"];
}

require_once("template/base.php");
?>