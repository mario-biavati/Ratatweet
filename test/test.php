<?php
require_once("../bootstrap.php");
echo "HOLA TEST YEA";

echo "<br>Test getUserById=";
var_dump($dbh->getUserByID(2));

echo "<br>Test getPostById=";
var_dump($dbh->getPostByID(3));

echo "<br>Test search(cremina)=";
var_dump($dbh->search("cremina"));

echo "<br>Test addFollower(2,5)=";
var_dump($dbh->addFollower(2,5));

echo "<br>Test enableNotifications()=";
var_dump($dbh->enableNotifications(2,5,false));

echo "<br>Test getFollowedRandomPosts()=";
var_dump($dbh->getFollowedRandomPosts(2));
?>