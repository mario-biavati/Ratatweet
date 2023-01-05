<?php
require_once '../bootstrap.php';
header("Content-Type: application/json");

$loggedUser = (isset($_SESSION["idUser"])) ? $_SESSION["idUser"] : -1;

if (isset($_GET["q"]) && $_GET["q"] == "getLoggedUser") {                //ritorna l'ID dello user loggato
    echo json_encode(array("idUser" => $loggedUser));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getFeedPosts" && isset($_GET["offset"])) {
    echo json_encode($dbh->getFollowedRandomPosts($loggedUser,50,$_GET["offset"]));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getPost" && isset($_GET["id"])) {
    echo json_encode($dbh->getPostByID($_GET["id"])[0]);
}
else if (isset($_GET["q"]) && $_GET["q"] == "search" && isset($_GET["text"])) {
    echo json_encode($dbh->search($_GET["text"]));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getUserInfo") {
    echo json_encode($dbh->getUserById($loggedUser)[0]);
}
else if (isset($_GET["q"]) && $_GET["q"] == "getComments" && isset($_GET["id"])) {
    echo json_encode($dbh->getCommentsByPostID($_GET["id"]));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getReplies" && isset($_GET["id"])) {
    echo json_encode($dbh->getRepliesByCommentID($_GET["id"]));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getComment" && isset($_GET["id"])) {
    echo json_encode($dbh->getCommentByID($_GET["id"])[0]);
}
else if (isset($_GET["q"]) && $_GET["q"] == "getUserPosts" && isset($_GET["id"])) {
    echo json_encode($dbh->getUserPosts($_GET["id"]));
}
else if (isset($_POST["q"]) && $_POST["q"] == "postComment" && isset($_POST["id"]) && isset($_POST["comment"])) {
    if (isset($_SESSION["idUser"])) {
        $val = $dbh->addCommentOnPost($_POST["id"], $loggedUser, $_POST["comment"]);
        $result["esito"] = true;
        $result["errore"] = "Nessuno";
        $result["id"] = $val;
    } else {
        $result["esito"] = false;
        $result["errore"] = "Not Logged";
    }
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "postReply" && isset($_POST["id"]) && isset($_POST["idComment"]) && isset($_POST["comment"])) {
    if (isset($_SESSION["idUser"])) {
        $val = $dbh->addReplyOnComment($_POST["id"], $loggedUser, $_POST["comment"], $_POST["idComment"]);
        $result["esito"] = true;
        $result["errore"] = "Nessuno";
        $result["id"] = $val;
    } else {
        $result["esito"] = false;
        $result["errore"] = "Not Logged";
    }
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "saveRecipe" && isset($_SESSION["idUser"]) && isset($_POST["id"])) {
    $dbh->saveRecipe($loggedUser, $_POST["id"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "addFollowed" && isset($_SESSION['idUser']) && isset($_POST["idFollowed"])) {
    $dbh->addFollowed($loggedUser, $_POST["idFollowed"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "removeFollowed" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->removeFollowed($loggedUser, $_POST["idFollowed"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "enableNotifications" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->enableNotifications($loggedUser, $_POST["idFollowed"], 1);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "disableNotifications" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->enableNotifications($loggedUser, $_POST["idFollowed"], 0);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "seenNotification" && isset($_SESSION["idUser"]) && isset($_POST["idNotification"])) {
    $dbh->seenNotification($_POST["idNotification"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "deleteRecipe" && isset($_SESSION["idUser"]) && isset($_POST["idPost"])) {
    $dbh->removeRecipe($_SESSION["idUser"], $_POST["idPost"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "login" && isset($_POST["username"]) && isset($_POST["password"])) {
    $result["logineseguito"] = false;
    $login_result = $dbh->login($_POST["username"], $_POST["password"]);
    if(count($login_result)==0) $result["errorelogin"] = "Errore! Controllare username o password!";
    else registerLoggedUser($login_result[0]);
    if(isUserLoggedIn()) $result["logineseguito"] = true;
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "register" && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["bio"]) && isset($_POST["pic"])) {
    $result["esito"] = false;
    $result["errore"] = "Non so!";
    var_dump($_POST["pic"]);
    $register_result = $dbh->insertUser($_POST["username"], $_POST["password"], $_POST["bio"], $_POST["pic"]);
    if($register_result==0 || $register_result=="false") $result["errore"] = "Errore! Username già utilizzato!";
    else {
        $login_result = $dbh->login($_POST["username"], $_POST["password"]);
        if(count($login_result)!=0) registerLoggedUser($login_result[0]);
    }
    if(isUserLoggedIn()) $result["esito"] = true;
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
else {
    $result["esito"] = false;
    $result["errore"] = "Funzione non riconsciuta";
    header('Content-Type: application/json');
    echo json_encode($result);
}

?>