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
    echo json_encode($dbh->search($_GET["text"]));
}
else if ($_GET["q"] == "getUserInfo") {
    echo json_encode($dbh->getUserById($loggedUser)[0]);
}
else if ($_GET["q"] == "getComments" && isset($_GET["id"])) {
    echo json_encode($dbh->getCommentsByPostID($_GET["id"]));
}
else if ($_GET["q"] == "getReplies" && isset($_GET["id"])) {
    echo json_encode($dbh->getRepliesByCommentID($_GET["id"]));
}
else if ($_GET["q"] == "getComment" && isset($_GET["id"])) {
    echo json_encode($dbh->getCommentByID($_GET["id"])[0]);
}
else if ($_POST["q"] == "saveRecipe" && isset($_SESSION["idUser"]) && isset($_POST["id"])) {
    $dbh->saveRecipe($loggedUser, $_POST["id"]);
}
else if ($_POST["q"] == "addFollowed" && isset($_SESSION['idUser']) && isset($_POST["idFollowed"])) {
    $dbh->addFollowed($loggedUser, $_POST["idFollowed"]);
}
else if ($_POST["q"] == "removeFollowed" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->removeFollowed($loggedUser, $_POST["idFollowed"]);
}
else if ($_POST["q"] == "enableNotifications" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->enableNotifications($loggedUser, $_POST["idFollowed"], 1);
}
else if ($_POST["q"] == "disableNotifications" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->enableNotifications($loggedUser, $_POST["idFollowed"], 0);
}
else if ($_POST["q"] == "seenNotification" && isset($_SESSION["idUser"]) && isset($_POST["idNotification"])) {
    $dbh->seenNotification($_POST["idNotification"]);
}
else {
    echo '{}';
}

?>