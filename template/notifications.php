<?php
if (isUserLoggedIn()):
    $idUser = $_SESSION["idUser"];
    $notifications = $dbh->getNotifications($idUser);
    if(empty($notifications)):
        ?>
        <h2>No new notification</h2>
    <?php endif;?>
    <ul id=<?php echo "notifications{$idUser}"; ?>>
        <?php foreach($notifications as $notification): 
            var_dump($notification["notifier"]);
            $userData= $dbh->getUserById($notification["notifier"])[0];
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
                <button type="button" alt="Seen notification" id="SeenNotification-button" onclick="seenNotification(<?php echo $notification['IDnotification']; ?>)">Seen</button>
            </li>
        <?php endforeach;?>
    </ul> 
<?php endif;?>