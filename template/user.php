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
        <section>
                <!--Username-->
            <h1><?php echo $userData["username"]; ?></h1>
                <!--Bio-->
            <p><?php echo $userData["bio"]; ?></p>
                <!--Pic-->
            <p>
            <img src="data:image/png;base64,<?php echo $userData["pic"]; ?>" width = "50px" height = "50px" />
            </p>
                <!--Pulsanti-->
            <div id="buttons">
            <?php $idLogged = $_SESSION["idUser"];
                if($id == $idLogged): ?>
                <button type="button" class="modify" alt="Modify profile" id="Modify-Button" onclick="">Modify</button>
            <?php else: 
                $FollowStatus = "Follow";
                if(isUserLoggedIn() && !empty($dbh->getFollowerStatus($idLogged, $id))) $FollowStatus = "Unfollow"; ?>
                <button type="button" class=<?php echo $FollowStatus; ?> alt="<?php echo $FollowStatus; ?> user" id="followbutton" onclick="<?php echo strtolower($FollowStatus)."()"?>"><?php echo $FollowStatus; ?></button>
                <?php 
                $notificationStatus = $dbh->getFollowerStatus($idLogged, $id);
                $notif="Disable";
                if(!empty($notificationStatus) && $notificationStatus[0]["notification"]==0) $notif="Enable"; ?>
                <button type="button" class=<?php echo $notif; ?> alt="<?php echo $notif; ?> notifications" id="notificationbutton" onclick="<?php echo ($notif == "Enable") ? "enableNotifications()" : "disableNotifications()"; ?>" <?php if ($FollowStatus == "Follow") echo "disabled"; ?>><?php echo $notif; ?> Notifications</button>
            <?php endif; ?>
            </div>
        </section>
        <!--Statistiche sull'utente-->
        <section>
            <h2>Posts</h2>
            <p><?php echo $userStat["post"]; ?></p>
            <h2>Followers</h2>
            <p id="NumFollowers"><?php echo $userStat["follower"]; ?></p>
            <h2>Followed</h2>
            <p id="NumFollowed"><?php echo $userStat["followed"]; ?></p>
            <h2>Rating</h2>
            <p><?php echo $userStat["avg_rating"]; ?></p>
        </section>
        <!--Lista dei post di un utente-->
        <section id="posts">
            <h2>Posts</h2>
            
        </section>
    </article>
<?php endif; ?>  
