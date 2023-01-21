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
    $id = $_SESSION["idUser"];
    $savedRecipes = $dbh->getSavedRecipes($id);
    $userRecipes = $dbh->getUserRecipes($id);

    if (isset($_GET["IDrecipe"]) && !contains_value($_GET["IDrecipe"], $savedRecipes) && !contains_value($_GET["IDrecipe"], $userRecipes)) {
        $templateParams["main"] = "error.html";
        $templateParams["user"] = $_SESSION["idUser"];
    } else {
        $templateParams["main"] = "post_form.php";
        $templateParams["user"] = $_SESSION["idUser"];
    }
}

require_once("template/base.php");
?>