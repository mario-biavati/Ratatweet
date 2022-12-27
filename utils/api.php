<?php
require_once '../bootstrap.php';
header("Content-Type: application/json");

$loggedUser = (isset($_SESSION["idUser"])) ? $_SESSION["idUser"] : -1;

if ($_GET["q"] == "getLoggedUser") {                //ritorna l'ID dello user loggato
    echo json_encode(array("idUser" => $loggedUser));
}
else if ($_GET["q"] == "getFeedPosts") {
    echo json_encode($dbh->getFollowedRandomPosts($loggedUser));
}
else if ($_GET["q"] == "getUserInfo") {
    $user = $dbh->getUserById($_SESSION["idUser"]);
    echo json_encode($user[0]);
}
else {
    echo '{"val":"NOTHING"}';
}

?>