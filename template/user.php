<?php
$id = $templateParams["user"];
$userData = $dbh->getUserById($id);
$userPosts = $dbh->getUserPosts($id, -1);
$userStat = $dbh->getUserStats($id);
if (count($userData) == 1) :
    $userData = $userData[0];
    $userStat = $userStat[0];
    ?>
    <article id=<?php echo "user{$userData["IDuser"]}"; ?>>
            <!--Info sull'utente-->
            <div class="d-flex" style="padding-left: 21px;padding-right: 21px;padding-top: 17px;">
                <!--Pic-->
                <picture>
                    <img src="data:image/png;base64,<?php echo $userData["pic"]; ?>" style="height: 100px;" />
                </picture>
                <div style="padding-left: 13px;">
                <!--Username-->
                    <h1 style="padding-left: 0px;"><?php echo $userData["username"]; ?></h1>
                <!--Bio-->
                    <p style="overflow: hidden;max-height: 70px;"><?php echo $userData["bio"]; ?></p>
                </div>
            </div>
            <!--Pulsanti-->
            <div class="d-flex justify-content-center">
            <?php $idLogged = $_SESSION["idUser"];
                if($id == $idLogged): ?>
                <div style="margin-right: 1em">
                    <button type="button" class="btn btn-primary" alt="Modify profile" id="Modify-Button" onclick="modify()" style="height:30px; width: auto; padding: auto; line-height: 10px;">
                        Modify
                    </button>
                </div>
                <?php else: 
                $FollowStatus = "Follow";
                if(isUserLoggedIn() && !empty($dbh->getFollowerStatus($idLogged, $id))) $FollowStatus = "Unfollow"; ?>
                <div style="margin-right: 1em">
                    <button type="button" class="btn btn-primary" alt="<?php echo $FollowStatus; ?> user" id="followbutton" onclick="<?php echo strtolower($FollowStatus)."()"?>" style="height:30px; width: auto; padding: auto; line-height: 10px;">
                        <?php echo $FollowStatus; ?>
                    </button>
                </div>
                <?php 
                $notificationStatus = $dbh->getFollowerStatus($idLogged, $id);
                $notif="Disable";
                if(!empty($notificationStatus) && $notificationStatus[0]["notification"]==0) $notif="Enable"; ?>
                <div style="margin-left: 1em">
                    <button type="button" class="btn btn-secondary" alt="<?php echo $notif; ?> notifications" id="notificationbutton" onclick="<?php echo ($notif == "Enable") ? "enableNotifications()" : "disableNotifications()"; ?>" <?php if ($FollowStatus == "Follow") echo "disabled"; ?> style="height:30px; width: auto; padding: auto; line-height: 10px;">
                        <?php echo $notif; ?> Notifications
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <!--Statistiche utente-->
            <ul class="list-group list-group-horizontal text-center justify-content-evenly">
                <li class="list-group-item d-flex flex-column" style="display: block;border-style: none;">
                    <span>
                        <strong>Posts</strong>
                    </span>
                    <span>
                        <strong><?php echo $userStat["post"]; ?></strong>
                    </span>
                </li>
                <li class="list-group-item d-flex flex-column" style="display: block;border-style: none;">
                    <span>
                        <strong>Followers</strong>
                    </span>
                    <span>
                        <strong><?php echo $userStat["follower"]; ?></strong>
                    </span>
                </li>
                <li class="list-group-item d-flex flex-column" style="display: block;border-style: none;">
                    <span>
                        <strong>Followed</strong>
                    </span>
                    <span>
                        <strong><?php echo $userStat["followed"]; ?></strong>
                    </span>
                </li>
                <li class="list-group-item d-flex flex-column" style="display: block;border-style: none;">
                    <span>
                        <strong>Rating</strong>
                    </span>
                    <span>
                        <strong><?php echo $userStat["avg_rating"]; ?></strong>
                    </span>
                </li>
            </ul>
        <!--Lista dei post di un utente-->
        <div class="container mb-md-1 mb-5" id="posts">
        </div>
    </article>
<?php endif; ?>  
