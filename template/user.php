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
        <div class="d-flex justify-content-center my-3">
        <?php $idLogged = isset($_SESSION["idUser"]) ? $_SESSION["idUser"] : -1;
            if(isset($_SESSION["idUser"]) && $id == $_SESSION["idUser"]): ?>
            <div style="margin-right: 1em">
                <button type="button" class="btn btn-info text-white" alt="Modify profile" id="Modify-Button" onclick="modify()" style="height:30px; width: auto; padding: auto; line-height: 10px;">
                    Modify
                </button>
            </div>
            <?php else: 
            $FollowStatus = "Follow";
            if(isUserLoggedIn() && !empty($dbh->getFollowerStatus($idLogged, $id))) $FollowStatus = "Unfollow"; ?>
            <button type="button" class="btn <?php echo ($FollowStatus=="Follow") ? "btn-info" : "btn-secondary"?> text-white py-1 col-3 col-md-2 mx-2" alt="<?php echo $FollowStatus; ?> user" id="followbutton" onclick="<?php echo strtolower($FollowStatus)."()"?>">
                <?php echo $FollowStatus; ?> 
            </button>
            <?php 
            $notificationStatus = $dbh->getFollowerStatus($idLogged, $id);
            $notif="Disable";
            if(!empty($notificationStatus) && $notificationStatus[0]["notification"]==0) $notif="Enable"; ?>
            <button type="button" class="btn <?php echo ($notif == "Enable") ? "btn-secondary" : "btn-info"; ?> py-1 col-3 col-md-2 mx-2" alt="<?php echo $notif; ?> notifications" id="notificationbutton" onclick="<?php echo ($notif == "Enable") ? "enableNotifications()" : "disableNotifications()"; ?>" <?php if ($FollowStatus == "Follow") echo "disabled"; ?>>
                <img src="img/notification-icon.png" style="height: 20px;">
            </button>
            <?php endif; ?>
        </div>
        <!--Statistiche utente-->
        <ul class="nav list-group-horizontal text-center justify-content-evenly my-3">
            <li class="nav-item d-flex flex-column" role="button" data-bs-toggle="collapse" data-bs-target="#posts:not(.show),#followers.show,#followed.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="posts">
                <span>
                    <strong>Posts</strong>
                </span>
                <span>
                    <strong id="NumPosts"><?php echo $userStat["post"]; ?></strong>
                </span>
            </li>
            <li class="nav-item d-flex flex-column" role="button" data-bs-toggle="collapse" data-bs-target="#followers:not(.show),#posts.show,#followed.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="followers">
                <span>
                    <strong>Followers</strong>
                </span>
                <span>
                    <strong id="NumFollowers"><?php echo $userStat["follower"]; ?></strong>
                </span>
            </li>
            <li class="nav-item d-flex flex-column" role="button" data-bs-toggle="collapse" data-bs-target="#followed:not(.show),#posts.show,#followers.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="followed">
                <span>
                    <strong>Followed</strong>
                </span>
                <span>
                    <strong id="NumFollowed"><?php echo $userStat["followed"]; ?></strong>
                </span>
            </li>
            <li class="nav-item d-flex flex-column" >
                <span>
                    <strong>Rating</strong>
                </span>
                <span>
                    <strong><?php echo $userStat["avg_rating"]; ?></strong>
                </span>
            </li>
        </ul>
        <div id="collapse-container" class="mt-5 mx-3">
            <!--Lista dei post di un utente-->
            <div class="container collapse show" id="posts">
            </div>
            <!--Lista dei follower di un utente-->
            <div class="container collapse" id="followers">
            </div>
            <!--Lista dei seguiti di un utente-->
            <div class="container collapse" id="followed">
            </div>
        </div>
</article>
