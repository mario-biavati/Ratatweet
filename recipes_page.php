<?php
require_once("bootstrap.php");

$templateParams["title"] = "Ratatweet - Recipes"; 
$templateParams["main"] = "recipes.php";
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/recipes.js");

require_once("template/base.php");
?>