<?php
if (isUserLoggedIn()):
    $id = $_SESSION["idUser"];
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
                <?php echo
                    '<img src = "data:image/png;base64,' . $userData["pic"] . '" width = "50px" height = "50px"/>'
                ?>
                </p>
                 <!--Pulsanti-->
                <?php if(isUserLoggedIn()):
                    $idLogged=$_SESSION["idUser"];
                    $FollowStatus = "Follow";
                    if(!empty($dbh->getFollowerStatus($idLogged, $id))) $FollowStatus = "Unfollow"; ?>
                    <button type="button" class=<?php echo $FollowStatus; ?> alt="<?php echo $FollowStatus; ?> user" id="FollowUnfollow-Button" onclick="followunfollow(<?php echo $id; ?>)"><?php echo $FollowStatus; ?></button>
                    <?php 
                    $notificationStatus = $dbh->getNotificationStatus($idLogged, $id);
                    $notif="Disable";
                    if(empty($notificationStatus) || $notificationStatus[0]["notification"]==0) $notif="Enable"; ?>
                    <button type="button" class=<?php echo $notif; ?> alt="<?php echo $notif; ?> notifications" id="Notifications-Button" onclick="modifyNotifications(<?php echo $id; ?>)"><?php echo $notif; ?> notifications</button>
                <?php endif; ?>
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
            <section>
                <h2>Posts</h2>
                <?php var_dump($userPosts); ?>
                <?php foreach($userPosts as $postData): ?>
                    <div id=<?php echo "user{$userData["IDuser"]}post{$postData["IDpost"]}"; ?>>
                        <!--Immagine post-->
                        <img src=<?php echo "{$postData["pic"]}"; ?> alt=<?php echo "{$postData["title"]}"; ?>>
                        <!--Titolo del post-->
                        <a href="">
                            <h3> <?php echo "{$postData["title"]}"; ?> </h3>
                        </a>
                        <!--Rating medio del post-->
                        <div alt="Rating">
                            <?php for($i=1; $i<=$postData["avgRating"]; $i++): ?>
                                <img src="./img/stella_piena.png" alt="voto <?php echo "{$i}"; ?> stelle" />
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </article>
    <?php endif; ?>  
<?php endif; ?>