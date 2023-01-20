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
            <div class="d-flex justify-content-center my-3">
            <?php $idLogged = $_SESSION["idUser"];
                if($id == $idLogged): ?>
                <div style="margin-right: 1em">
                    <button type="button" class="btn btn-info" alt="Modify profile" id="Modify-Button" onclick="modify()" style="height:30px; width: auto; padding: auto; line-height: 10px;">
                        Modify
                    </button>
                </div>
                <?php else: 
                $FollowStatus = "Follow";
                if(isUserLoggedIn() && !empty($dbh->getFollowerStatus($idLogged, $id))) $FollowStatus = "Unfollow"; ?>
                <div style="margin-right: 1em">
                    <button type="button" class="btn btn-info" alt="<?php echo $FollowStatus; ?> user" id="followbutton" onclick="<?php echo strtolower($FollowStatus)."()"?>" style="height:30px; width: auto; padding: auto; line-height: 10px;">
                        <?php echo $FollowStatus; ?>
                    </button>
                </div>
                <?php 
                $notificationStatus = $dbh->getFollowerStatus($idLogged, $id);
                $notif="Disable";
                if(!empty($notificationStatus) && $notificationStatus[0]["notification"]==0) $notif="Enable"; ?>
                <div style="margin-left: 1em">
                    <button type="button" class="btn <?php echo ($notif == "Enable") ? "btn-secondary" : "btn-info"; ?>" alt="<?php echo $notif; ?> notifications" id="notificationbutton" onclick="<?php echo ($notif == "Enable") ? "enableNotifications()" : "disableNotifications()"; ?>" <?php if ($FollowStatus == "Follow") echo "disabled"; ?> style="height:30px; width: auto; padding: auto; line-height: 10px;">
                        <img src="img/notification-icon.png" style="height: 20px;">
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <!--Statistiche utente-->
            <ul class="nav list-group-horizontal text-center justify-content-evenly my-3">
                <li class="nav-item d-flex flex-column" data-bs-toggle="collapse" data-bs-target="#posts:not(.show),#followers.show,#followed.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="posts">
                    <span>
                        <strong>Posts</strong>
                    </span>
                    <span>
                        <strong><?php echo $userStat["post"]; ?></strong>
                    </span>
                </li>
                <li class="nav-item d-flex flex-column" data-bs-toggle="collapse" data-bs-target="#followers:not(.show),#posts.show,#followed.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="followers">
                    <span>
                        <strong>Followers</strong>
                    </span>
                    <span>
                        <strong><?php echo $userStat["follower"]; ?></strong>
                    </span>
                </li>
                <li class="nav-item d-flex flex-column" data-bs-toggle="collapse" data-bs-target="#followed:not(.show),#posts.show,#followers.show" data-parent="#collapse-container" aria-expanded="false" aria-controls="followed">
                    <span>
                        <strong>Followed</strong>
                    </span>
                    <span>
                        <strong><?php echo $userStat["followed"]; ?></strong>
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
                <div class="container collapse" id="posts">
                    <p class="fs-2 text-muted text-center p-5 m-0">No posts</p>
                </div>
                <!--Lista dei follower di un utente-->
                <div class="container collapse" id="followers">
                    <p class="fs-2 text-muted text-center p-5 m-0">No followers</p>
                </div>
                <!--Lista dei seguiti di un utente-->
                <div class="container collapse" id="followed">
                    <p class="fs-2 text-muted text-center p-5 m-0">No followed</p>
                </div>
            </div>
    </article>
<?php endif; ?>  
