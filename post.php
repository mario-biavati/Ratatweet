<?php
require_once("bootstrap.php");

$templateParams["title"] = "Ratatweet - Post"; 
$templateParams["header"] = "header_long.html";
$templateParams["main"] = "template/post.php";
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/post.js");

require_once("template/base.php");
?>