<?php
require_once '../bootstrap.php';

if (isset($_SESSION["idUser"]))
{
    $user = $dbh->getUserById($_SESSION["idUser"]);
    echo json_encode($user[0]);
} else {
    echo '{}';
}
?>