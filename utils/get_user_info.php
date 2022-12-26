<?php
if (isset($_GET["idUser"]))
{
    echo json_encode($dbh->getUserById($_GET["idUser"]));
}
?>