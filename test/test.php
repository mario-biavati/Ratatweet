<?php
require_once("../bootstrap.php");
echo "HOLA TEST YEA";

echo "<br>Test getUserById=";
var_dump($dbh->getUserByID(2));

echo "<br>Test getPostById=";
var_dump($dbh->getPostByID(3));
?>