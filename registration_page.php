<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/registration.js");
if(isUserLoggedIn()) {
    $templateParams["title"] = "Ratatweet - Registration"; 
}
else {
    $templateParams["title"] = "Ratatweet - Modification"; 
}
$templateParams["header"] = "header_short.html";
$templateParams["main"] = "registration_form.php";

require_once("template/base.php");
?>