<?php
require_once '../bootstrap.php';
header("Content-Type: application/json");

$loggedUser = (isset($_SESSION["idUser"])) ? $_SESSION["idUser"] : -1;

if ($_GET["q"] == "getLoggedUser") {                //ritorna l'ID dello user loggato
    echo json_encode(array("idUser" => $loggedUser));
}
else if ($_GET["q"] == "getFeedPosts" && isset($_GET["offset"])) {
    echo json_encode($dbh->getFollowedRandomPosts($loggedUser,50,$_GET["offset"]));
}
else if ($_GET["q"] == "getPost" && isset($_GET["id"])) {
    echo json_encode($dbh->getPostByID($_GET["id"])[0]);
}
else if ($_GET["q"] == "search" && isset($_GET["text"])) {
    $searchtxt = str_replace("+", " ", $_GET["text"]);
    echo json_encode($dbh->search($searchtxt));
}
else if ($_GET["q"] == "getUserInfo") {
    echo json_encode($dbh->getUserById($loggedUser)[0]);
}
else if ($_POST["q"] == "saveRecipe" && isset($_SESSION["idUser"]) && isset($_POST["id"])) {
    $dbh->saveRecipe($loggedUser, $_POST["id"]);
}
else {
    echo '{}';
}

?>