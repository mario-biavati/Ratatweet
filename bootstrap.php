<?php
session_start();
define("UPLOAD_DIR", "./db/upload/");
require_once("db/database.php");
$dbh = new DatabaseHelper("localhost", "root", "", "RATATWEET", 3306);
?>