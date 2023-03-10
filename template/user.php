<article id=<?php echo "user{$userData["IDuser"]}"; ?>>
        <!--Info sull'utente-->
        <div class="d-flex" style="padding-left: 21px;padding-right: 21px;padding-top: 17px;">
            <!--Pic-->
            <picture class="icon-user overflow-hidden d-flex justify-content-center align-content-center rounded">
                <img src="data:image/png;base64,<?php echo $userData["pic"]; ?>" class="fit-cover" alt="Profile_icon"/>
            </picture>
            <div style="padding-left: 13px;">
            <!--Username-->
                <h1 class="ps-0"><?php echo $userData["username"]; ?></h1>
            <!--Bio-->
                <p style="overflow: hidden;max-height: 70px;"><?php echo $userData["bio"]; ?></p>
            </div>
        </div>
        <!--Pulsanti-->
        <div class="d-flex justify-content-center my-3">
        <?php $idLogged = isset($_SESSION["idUser"]) ? $_SESSION["idUser"] : -1;
            if(isset($_SESSION["idUser"]) && $id == $_SESSION["idUser"]): ?>
            <button type="button" title="Modify profile" aria-label="Modify profile" class="btn btn-info border-dark py-1 col-3 col-md-2 mx-2" id="Modify-Button" onclick="modify()">
                Modify
            </button>
            <?php else: 
            $FollowStatus = "Follow";
            if(isUserLoggedIn() && !empty($dbh->getFollowerStatus($idLogged, $id))) $FollowStatus = "Unfollow"; ?>
            <button type="button" title="<?php echo $FollowStatus; ?> user" aria-label="<?php echo $FollowStatus; ?> user" class="btn <?php echo ($FollowStatus=="Follow") ? "btn-info" : "btn-secondary"?> border-dark py-1 col-3 col-md-2 mx-2" id="followbutton" onclick="<?php echo strtolower($FollowStatus)."()"?>">
                <?php echo $FollowStatus; ?> 
            </button>
            <?php 
            $notificationStatus = $dbh->getFollowerStatus($idLogged, $id);
            $notif="Disable";
            if(!empty($notificationStatus) && $notificationStatus[0]["notification"]==0) $notif="Enable"; ?>
            <button type="button" title="<?php echo $notif; ?> notifications" aria-label="<?php echo $notif; ?> notifications" class="btn <?php echo ($notif == "Enable") ? "btn-secondary" : "btn-info"; ?> border-dark py-1 col-3 col-md-2 mx-2" id="notificationbutton" onclick="<?php echo ($notif == "Enable") ? "enableNotifications()" : "disableNotifications()"; ?>" <?php if ($FollowStatus == "Follow") echo "disabled"; ?>>
                <img src="img/<?php echo ($notif == "Enable") ? "notification-disabled-icon.png" : "notification-icon.png"; ?>" alt="Enable/disable notifications" style="height: 20px;">
            </button>
            <?php endif; ?>
        </div>
        <!--Statistiche utente-->
        <ul class="nav list-group-horizontal justify-content-evenly my-3">
            <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#posts:not(.show),#followers.show,#followed.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="posts">
                <button class="d-flex flex-column bg-none border-0">
                    <span class="mx-auto">
                        <strong>Posts</strong>
                    </span>
                    <span class="mx-auto">
                        <strong id="NumPosts"><?php echo $userStat["post"]; ?></strong>
                    </span>
                </button>
            </li>
        
            <li class="nav-item d-flex flex-column" data-bs-toggle="collapse" data-bs-target="#followers:not(.show),#posts.show,#followed.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="followers">
                <button  class="d-flex flex-column bg-none border-0">    
                    <span class="mx-auto">
                        <strong>Followers</strong>
                    </span>
                    <span class="mx-auto">
                        <strong id="NumFollowers"><?php echo $userStat["follower"]; ?></strong>
                    </span>
                </button>
            </li>

            <li class="nav-item d-flex flex-column" data-bs-toggle="collapse" data-bs-target="#followed:not(.show),#posts.show,#followers.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="followed">
                <button class="d-flex flex-column bg-none border-0">    
                    <span class="mx-auto">
                        <strong>Followed</strong>
                    </span>
                    <span class="mx-auto">
                        <strong id="NumFollowed"><?php echo $userStat["followed"]; ?></strong>
                    </span>
                </button>
            </li>

            <li class="nav-item d-flex flex-column" >
                <span class="mx-auto">
                    <strong>Rating</strong>
                </span>
                <span class="mx-auto">
                    <strong><?php echo $userStat["avg_rating"]; ?></strong>
                </span>
            </li>
        </ul>
        <div id="collapse-container" class="mt-5">
            <!--Lista dei post di un utente-->
            <div class="collapse show" id="posts">
            </div>
            <!--Lista dei follower di un utente-->
            <div class="collapse" id="followers">
            </div>
            <!--Lista dei seguiti di un utente-->
            <div class="collapse" id="followed">
            </div>
        </div>
</article>
