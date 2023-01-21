<?php
if (isUserLoggedIn()):
    $idUser = $_SESSION["idUser"];
    $notifications = $dbh->getNotifications($idUser);
    if(empty($notifications)):
        ?>
        <p class="fs-2 text-center text-muted p-5">No new notification</p>
    <?php endif;?>
    <ul id=<?php echo "notifications{$idUser}"; ?> class="list-group">
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
                $notificationType=" used your ";
                $postHref="post.php?id={$notification["IDpost"]}";
            }
            else {
                $notificationType="unknow notification type";
            }
            $userHref="user_page.php?id={$userData["IDuser"]}";
        ?>
            <li id="notification<?php echo $notification['IDnotification']; ?>" class="list-group-item rounded border my-2 mx-auto col-10 col-md-9 col-lg-8">
                <img src="data:image/png;base64,<?php echo $userData["pic"]; ?>" alt=<?php echo "{$userData["username"]}_Pic"; ?> width = "50px" height = "50px" class="icon-small"/>
                <a href=<?php echo "{$userHref}"; ?>><?php echo "{$userData["username"]}"; ?></a>
                <p>
                    <?php echo $notificationType; if($notification["type"]=="Post" || $notification["type"]=="Comment"): ?>
                        <a href=<?php echo "{$postHref}"; ?>>post</a>
                    <?php elseif($notification["type"]=="Recipe"): ?>
                        <a href=<?php echo "{$postHref}"; ?>>recipe</a>
                    <?php endif; ?>
                </p>
                <div class="d-flex w-100 justify-content-end">
                    <button type="button" class="btn btn-info text-white" data-bs-toggle="button" alt="Seen notification" id="SeenNotification-button" onclick="seenNotification(<?php echo $notification['IDnotification']; ?>)">Seen</button>
                </div>    
            </li>
        <?php endforeach;?>
    </ul> 
<?php endif;?>