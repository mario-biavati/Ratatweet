<?php
if (isset($_SESSION["idUser"])):
    $idUser = $_SESSION["idUser"];
    $notifications = $dbh->getNotifications($idUser);
    ?>
    <ul id=<?php echo "notifications{$userData["IDuser"]}"; ?>>
        <?php foreach($notifications as $notification): 
            $userData= getUserById($notification["notifier"])[0];
            $notificationType="";
            if($notification["type"]=="Follow") {
                $notificationType="started following you";
            }
            elseif($notification["type"]=="Comment") {
                $notificationType="commented your post";
            }
            elseif($notification["type"]=="Post") {
                $notificationType="created a new post";
            }
            elseif($notification["type"]=="Recipe") {
                $notificationType="used your recipe";
            }
            else {
                $notificationType="unknow notification type";
            }
        ?>
            <li>
                <img src=<?php echo "{$userData["pic"]}"; ?> alt=<?php echo "{$userData["username"]}"; ?>/>
                <p><?php echo "{$userData["username"]}"; ?></p>
                <p><?php echo "{$notificationType}"; ?></p>
            </li>
        <?php endforeach;?>
    </ul> 
<?php endif; ?>