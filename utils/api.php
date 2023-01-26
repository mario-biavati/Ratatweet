<?php
require_once '../bootstrap.php';
header("Content-Type: application/json");

$loggedUser = (isset($_SESSION["idUser"])) ? $_SESSION["idUser"] : -1;
if (isset($_GET["q"]) && $_GET["q"] == "getLoggedUser") {                //ritorna l'ID dello user loggato
    echo json_encode(array("idUser" => $loggedUser));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getSelectedRecipe") {                //ritorna la ricetta eventualmente selezionata 
    if(isset($_GET["IDrecipe"])) echo json_encode(array("IDrecipe" => $_GET["IDrecipe"]));
    else echo json_encode(array("IDrecipe" => -1));
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
    $id = (isset($_GET["idUser"])) ? $_GET["idUser"] : $loggedUser;
    echo json_encode($dbh->getUserById($id)[0]);
}
else if (isset($_GET["q"]) && $_GET["q"] == "getComments" && isset($_GET["id"])) {
    echo json_encode($dbh->getCommentsByPostID($_GET["id"]));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getReplies" && isset($_GET["id"])) {
    echo json_encode($dbh->getRepliesByCommentID($_GET["id"]));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getComment" && isset($_GET["id"])) {
    echo json_encode($dbh->getCommentByID($_GET["id"], $loggedUser)[0]);
}
else if (isset($_GET["q"]) && $_GET["q"] == "getUserPosts" && isset($_GET["id"])) {
    echo json_encode($dbh->getUserPosts($_GET["id"]));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getNotificationNumber") {
    echo json_encode($dbh->getNotificationNumber($loggedUser)[0]);
}
else if (isset($_GET["q"]) && $_GET["q"] == "getUserFollowers" && isset($_GET["idUser"])) {
    $id = ($_GET["idUser"]!=-1) ? $_GET["idUser"] : $loggedUser;
    echo json_encode($dbh->getFollowers($id));
}
else if (isset($_GET["q"]) && $_GET["q"] == "getUserFollowed" && isset($_GET["idUser"])) {
    $id = ($_GET["idUser"]!=-1) ? $_GET["idUser"] : $loggedUser;
    echo json_encode($dbh->getFollowed($id));
}
else if (isset($_GET["q"]) && $_GET["q"] == "isRecipeSaved" && isset($_GET["id"])) { //!! questa funzione richiede l'id del POST
    echo json_encode($dbh->isRecipeSaved($loggedUser, $_GET["id"])[0]);
}
else if (isset($_POST["q"]) && $_POST["q"] == "postComment" && isset($_POST["id"]) && isset($_POST["comment"])) {
    if (isset($_SESSION["idUser"])) {
        $val = $dbh->addCommentOnPost($_POST["id"], $loggedUser, $_POST["comment"]);
        $result["esito"] = true;
        $result["errore"] = "Nessuno";
        $result["id"] = $val;

        //notify comment
        $dbh->notifyComment($loggedUser, $_POST["id"]);
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
else if (isset($_POST["q"]) && $_POST["q"] == "insertLike" && isset($_POST["id"])) {
    if (isset($_SESSION["idUser"])) {
        $val = $dbh->insertLike($_POST["id"], $loggedUser);
        $result["esito"] = true;
        $result["errore"] = "Nessuno";
        $result["id"] = $val;
    } else {
        $result["esito"] = false;
        $result["errore"] = "Not Logged";
    }
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "deleteLike" && isset($_POST["id"])) {
    if (isset($_SESSION["idUser"])) {
        $val = $dbh->deleteLike($_POST["id"], $loggedUser);
        $result["esito"] = true;
        $result["errore"] = "Nessuno";
        $result["id"] = $val;
    } else {
        $result["esito"] = false;
        $result["errore"] = "Not Logged";
    }
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "saveRecipe" && isset($_POST["id"])) { //!! questa funzione richiede l'id del POST
    if (isset($_SESSION["idUser"])) {
        $dbh->saveRecipe($loggedUser, $_POST["id"]);
        $result["esito"] = true;
        $result["errore"] = "Nessuno";
        header('Content-Type: application/json');
    } else {
        $result["esito"] = false;
        $result["errore"] = "Not Logged";
    }
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "addFollowed" && isset($_SESSION['idUser']) && isset($_POST["idFollowed"])) {
    $dbh->addFollowed($loggedUser, $_POST["idFollowed"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";

    //notify follow
    $dbh->notifyFollow($loggedUser, $_POST["idFollowed"]);

    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "removeFollowed" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->removeFollowed($loggedUser, $_POST["idFollowed"]);
    $dbh->removeFollowNotification($loggedUser, $_POST["idFollowed"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "enableNotifications" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->setNotifications($loggedUser, $_POST["idFollowed"], 1);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "disableNotifications" && isset($_SESSION["idUser"]) && isset($_POST["idFollowed"])) {
    $dbh->setNotifications($loggedUser, $_POST["idFollowed"], 0);
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
    $dbh->removeRecipe($loggedUser, $_POST["idPost"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    $result["IDpost"] = $_POST["idPost"];
    $result["IDuser"] = $_SESSION["idUser"];
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "login" && isset($_POST["username"]) && isset($_POST["password"])) {
    $result["logineseguito"] = false;
    $login_result = $dbh->login($_POST["username"], $_POST["password"]);
    if($login_result["esito"]==false) $result["errorelogin"] = $login_result["description"];
    else registerLoggedUser($login_result[0]);
    if(isUserLoggedIn()) $result["logineseguito"] = true;
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "register" && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["bio"]) && isset($_POST["pic"])) {
    $result["esito"] = false;
    $result["errore"] = "Non so!";
    $register_result = $dbh->insertUser($_POST["username"], $_POST["password"], $_POST["bio"], $_POST["pic"]);
    if($register_result==0 || $register_result=="false") $result["errore"] = "Errore! Username già utilizzato!";
    else {
        $login_result = $dbh->login($_POST["username"], $_POST["password"]);
        if($login_result["esito"]==true) registerLoggedUser($login_result[0]);
    }
    if(isUserLoggedIn()) $result["esito"] = true;
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "new_post" && isset($_POST["titolo"]) && isset($_POST["descrizione"]) && isset($_POST["pic"]) && isset($_POST["IDricetta"])) {
    $result["esito"] = false;
    $result["errore"] = "Non so!";
    $result["IDpost"] = -1;
    $create_post_result = $dbh->createPost($_POST["titolo"], $_POST["pic"], $_POST["descrizione"], $loggedUser, $_POST["IDricetta"]);
    if($create_post_result==0 || $create_post_result=="false") $result["errore"] = "Errore! Impossibile creare post!";
    else {
        $result["esito"] = true;
        $result["errore"] = "Nessun errore";
        $result["IDpost"] = $create_post_result;

        //notify post
        $dbh->notifyPost($loggedUser, $result["IDpost"]);
        $dbh->notifyRecipe($loggedUser, $result["IDpost"]);
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "new_recipe" && isset($_POST["ingredients"]) && isset($_POST["method"])) {
    $result["esito"] = false;
    $result["errore"] = "Non so!";
    $result["IDrecipe"] = -1;
    $create_recipe_result = $dbh->insertRecipe($_POST["ingredients"], $_POST["method"], $loggedUser);
    if($create_recipe_result==0 || $create_recipe_result=="false") $result["errore"] = "Errore! Impossibile creare post!";
    else {
        $result["esito"] = true;
        $result["errore"] = "Nessun errore";
        $result["IDrecipe"] = $create_recipe_result;
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "logout") {
    logout();
}
else if (isset($_POST["q"]) && $_POST["q"] == "insertRating" && isset($_SESSION["idUser"]) && isset($_POST["idPost"]) && isset($_POST["rating"])) {
    $dbh->insertRating($_SESSION["idUser"], $_POST["idPost"], $_POST["rating"]);
    $result["esito"] = true;
    $result["errore"] = "Nessuno";
    $result["idPost"] = $_POST["idPost"];
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "updateUser" && isset($_POST["username"]) && isset($_POST["bio"])) {
    $result["esito"] = false;
    $result["errore"] = "Non so!";
    if(isset($_POST["pic"])) $result["esito"] = $dbh->updateUserWithPic($_SESSION["idUser"], $_POST["username"], $_POST["bio"], $_POST["pic"]);
    else $result["esito"] = $dbh->updateUser($_SESSION["idUser"], $_POST["username"], $_POST["bio"]);
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
else if (isset($_POST["q"]) && $_POST["q"] == "deletePost" && isset($_POST["idPost"])) {
    $result["esito"] = false;
    $result["errore"] = "Non so!";
    $result["esito"] = $dbh->deletePost($_POST["idPost"]);
    
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