<?php
require_once("bootstrap.php");

$templateParams["title"] = "Ratatweet - Post"; 
$templateParams["header"] = "header_long.html";
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/check_notifications.js", "js/post.js");

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $postData = $dbh->getPostById($id);
    //$recipeData = $dbh->getRecipe($id)[0];
    //$commentData = $dbh->getCommentsByPostID($id);
    if (count($postData) == 1) {
        $postData = $postData[0];
        $recipeData = $dbh->getRecipe($postData["IDrecipe"])[0];

        $templateParams["main"] = "template/post.php";
    } else {
        $templateParams["main"] = "template/error.html";
    }
} else {
    $templateParams["main"] = "template/error.html";
}

require_once("template/base.php");
?>