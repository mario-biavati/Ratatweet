<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/create_post.js");
$templateParams["title"] = "Ratatweet - New Post"; 
$templateParams["header"] = "header_short.html";
if(!isUserLoggedIn()) {
    $templateParams["main"] = "login_form.php";
    array_push($templateParams["js"], "js/login.js");
}
else {
    $templateParams["main"] = "post_form.php";
    $templateParams["user"] = $_SESSION["idUser"];
}

require_once("template/base.php");
?>