<?php
require_once("bootstrap.php");

$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js");
$templateParams["title"] = "Ratatweet - Notifications"; 
if(!isUserLoggedIn()) {
    $templateParams["main"] = "login_form.php";
    array_push($templateParams["js"], "js/login.js");
    $templateParams["header"] = "header_short.html";
}
else {
    array_push($templateParams["js"], "js/check_notifications.js");
    array_push($templateParams["js"], "js/notification.js");
    $templateParams["main"] = "notifications.php";
    $templateParams["header"] = "header_long.html";
}

require_once("template/base.php");
?>