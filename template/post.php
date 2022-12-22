<?php
if (isset($_GET["idPost"])) :
    $id = $_GET["idPost"];
    $postData = $dbh->getPostById($id);
    $recipeData = $dbh->getRecipe($id);
    $commentData = $dbh->getCommentsByPostID($id);
    if (count($postData) == 1 && count($recipeData) == 1) :
        $postData = $postData[0];
        $recipeData = $recipeData[0];
        ?>
        <article id=<?php echo "{$postData["IDpost"]}"; ?>>
            <header>
                <a href="">
                    <h1> <?php echo "{$postData["title"]}"; ?> </h1>
                </a>
                <a href="">
                    <h2> <?php echo "{$postData["username"]}"; ?> </h2>
                </a>
                <input type="image" src="img/recipe-icon.png" alt="Save recipe" onclick="">
            </header>
            <section>
                <div>
                    <img src=<?php echo "{$postData["pic"]}"; ?> alt=<?php echo "{$postData["title"]}"; ?> />
                </div>
                <div>
                    <h2>Description</h2>
                    <p><?php echo "{$postData["description"]}"; ?></p>
                </div>
                <div>
                    <h2>Recipe</h2>
                    <h3>Ingredients</h3>
                    <p><?php echo "{$recipeData["ingredients"]}"; ?></p>
                    <h3>Method</h3>
                    <p><?php echo "{$recipeData["method"]}"; ?></p>
                </div>
                <div>
                    <h2>Comments</h2>
                    <?php foreach($commentData as $comment): ?>
                        <p><?php echo "{$comment["username"]}"; ?></p>
                        <p><?php echo "{$comment["text"]}"; ?></p>
                    <?php endforeach; ?>
                </div>
            </section>
            <footer>
                <div alt="Rating">
                    <img src="./img/stella_vuota.png" id="<?php echo "{$postData["IDpost"]}"; ?>-Star1" alt="vota 1 stella" />
                    <img src="./img/stella_vuota.png" id="<?php echo "{$postData["IDpost"]}"; ?>-Star2" alt="vota 2 stelle" />
                    <img src="./img/stella_vuota.png" id="<?php echo "{$postData["IDpost"]}"; ?>-Star3" alt="vota 3 stelle" />
                    <img src="./img/stella_vuota.png" id="<?php echo "{$postData["IDpost"]}"; ?>-Star4" alt="vota 4 stelle" />
                    <img src="./img/stella_vuota.png" id="<?php echo "{$postData["IDpost"]}"; ?>-Star5" alt="vota 5 stelle" />
                    <h2>Average rating: <?php echo "{$postData["avgRating"]}"; ?></h2>
                </div>
                <div alt="Comments">
                    <h2><?php echo "{$postData["numComments"]}"; ?></h2>
                    <a href="">
                        <img src="img/comment-icon.png" alt="Scrivi commento" />
                    </a>
                </div>
            </footer>
        </article>
    <?php endif; ?>  
<?php endif; ?>