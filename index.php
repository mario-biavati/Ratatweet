<?php
require_once("bootstrap.php");

$templateParams["title"] = "Ratatweet - Home"; 
//$templateParams["main"] = "home.php";
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js");

require_once("template/base.php");
?>