<?php
session_start();
define("UPLOAD_DIR", "./db/upload/");
require_once("utils/functions.php");
require_once("db/database.php");
$dbh = new DatabaseHelper("localhost", "root", "", "RATATWEET", 3306);
require_once("utils/functions.php");
?>