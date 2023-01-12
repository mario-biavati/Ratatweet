<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/registration.js");
$templateParams["title"] = "Ratatweet - Registration"; 
$templateParams["header"] = "header_short.html";
$templateParams["main"] = "registration_form.php";

require_once("template/base.php");
?>