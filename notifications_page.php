<?php
require_once("bootstrap.php");

$templateParams["title"] = "Ratatweet - Notifications"; 
$templateParams["main"] = "notifications.php";
$templateParams["js"] = array("https://unpkg.com/axios/dist/axios.min.js", "js/notification.js");

require_once("template/base.php");
?>