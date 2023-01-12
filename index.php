<?php
require_once("bootstrap.php");
$templateParams["header"] = "header_long.html";
$templateParams["title"] = "Ratatweet - Home"; 

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/home.js");

require_once("template/base.php");
?>