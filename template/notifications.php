<?php
if (isUserLoggedIn()):
    $idUser = $_SESSION["idUser"];
    $notifications = $dbh->getNotifications($idUser);
    if(empty($notifications)):
        ?>
        <h2>No new notification</h2>
    <?php endif;?>
    <ul id=<?php echo "notifications{$idUser}"; ?> style="list-style: none;">
        <?php foreach($notifications as $notification): 
            $userData= $dbh->getUserById($notification["notifier"])[0];
            $notificationType="";
            if($notification["type"]=="Follow") {
                $notificationType=" started following you";
            }
            elseif($notification["type"]=="Comment") {
                $notificationType=" commented your ";
                $postHref="post.php?id={$notification["IDpost"]}";
            }
            elseif($notification["type"]=="Post") {
                $notificationType=" created a new ";
                $postHref="post.php?id={$notification["IDpost"]}";
            }
            elseif($notification["type"]=="Recipe") {
                $notificationType=" used your recipe";
            }
            else {
                $notificationType="unknow notification type";
            }
            $userHref="user_page.php?id={$userData["IDuser"]}";
        ?>
            <li class="container mt-5 mb-5 col-12 col-md-10 col-lg-8">
                <img src="data:image/png;base64,<?php echo $userData["pic"]; ?>" alt=<?php echo "{$userData["username"]}_Pic"; ?> width = "50px" height = "50px" />
                <a href=<?php echo "{$userHref}"; ?>><?php echo "{$userData["username"]}"; ?></a>
                <p> <?php echo " {$notificationType}"; ?>
                <?php if($notification["type"]=="Post" || $notification["type"]=="Comment"): ?>
                    <a href=<?php echo "{$postHref}"; ?>><?php echo "post"; ?></a>
                <?php endif; ?>
                </p>
                <button type="button" class="btn btn-primary" data-bs-toggle="button" alt="Seen notification" id="SeenNotification-button" onclick="seenNotification(<?php echo $notification['IDnotification']; ?>)">Seen</button>
            </li>
        <?php endforeach;?>
    </ul> 
<?php endif;?>