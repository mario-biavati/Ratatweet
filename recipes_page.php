<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/recipes.js");
$templateParams["title"] = "Ratatweet - Recipes"; 
//$templateParams["header"] = "header_long.html";
if(!isUserLoggedIn()) {
    $templateParams["main"] = "login_form.php";
    array_push($templateParams["js"], "js/login.js");
}
else $templateParams["main"] = "recipes.php";

require_once("template/base.php");
?>