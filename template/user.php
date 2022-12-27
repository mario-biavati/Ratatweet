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
                <img src=<?php echo "{$userData["pic"]}"; ?> alt=<?php echo "{$userData["username"]}"; ?>>
                 <!--Pulsanti-->
                <?php if(isset($_SESSION["idUser"])):
                    $idLogged=$_SESSION["idUser"];
                    if(!empty($dbh->getFollowerStatus($idLogged, $id))):?>
                        <button type="button" alt="Unfollow user" id="Unfollow-button">Unfollow</button>
                    <?php else: ?>
                        <button type="button" alt="Follow user" id="Follow-button">Follow</button>
                    <?php endif; ?>
                    <?php if(getNotificationStatus($idLogged, $id)[0]["notification"]=="true"):?>
                        <button type="button" alt="Disable notifications" id="DisableNotifications-button">Disable notifications</button>
                    <?php else: ?>
                        <button type="button" alt="Enable notifications" id="EnableNotifications-button">Enable notifications</button>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
            <!--Statistiche sull'utente-->
            <section>
                <h2>Posts</h2>
                <p><?php echo $userStat["post"]; ?></p>
                <h2>Followers</h2>
                <p><?php echo $userStat["follower"]; ?></p>
                <h2>Followed</h2>
                <p><?php echo $userStat["followed"]; ?></p>
                <h2>Rating</h2>
                <p><?php echo $userStat["avg_rating"]; ?></p>
            </section>
            <!--Lista dei post di un utente-->
            <section>
                <h2>Posts</h2>
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