<?php
if (isset($_GET["id"])) :
    $id = $_GET["id"];
    $postData = $dbh->getPostById($id);
    //$recipeData = $dbh->getRecipe($id)[0];
    //$commentData = $dbh->getCommentsByPostID($id);
    if (count($postData) == 1) :
        $postData = $postData[0];
        $recipeData = $dbh->getRecipe($postData["IDrecipe"])[0];
        ?>
<article class="container col-12 col-md-10 col-lg-8">
    <div>
        <div class="d-block w-100">
            <div class="d-flex justify-content-between">
                <h1><?php echo $postData["title"];?></h1>
                <span id="recipe-button" role="button"><img src="img/recipe-icon.png" style="height: calc(1.5rem + 1.5vw);"/></span>
            </div>
            <h2 class="fs-5"><a href="user_page.php?id=<?php echo $postData["IDuser"]; ?>"><?php echo $postData["username"];?></a></h2>
            <?php if(isset($_SESSION["idUser"]) && $_SESSION["idUser"]==$postData["IDuser"]):?> 
            <button id="deletePostButton" onclick="deletePost(<?php echo $id;?>)">Delete</button>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <img src="data:image/png;base64,<?php echo $postData["pic"]; ?>" class="mw-100 w-100 h-auto"/>
    </div>
    <div class="w-100 row mt-1">
        <div class="d-flex justify-content-between">
            <div class="rating">
                <input type="radio" name="rating" value="5" id="star5" onclick="insertRating(5)"><label alt="vota 5 stelle" for="star5">☆</label> <input type="radio" name="rating" value="4" id="star4" onclick="insertRating(4)"><label alt="vota 4 stelle" for="star4">☆</label> <input type="radio" name="rating" value="3" id="star3" onclick="insertRating(3)"><label alt="vota 3 stelle" for="star3">☆</label> <input type="radio" name="rating" value="2" id="star2" onclick="insertRating(2)"><label alt="vota 2 stelle" for="star2">☆</label> <input type="radio" name="rating" value="1" id="star1" onclick="insertRating(1)"><label alt="vota 1 stella" for="star1">☆</label>
            </div>
            <span id="avgRating" class="fs-3 pt-2 ps-1">??</span>
        </div>
    </div>
    <section class="mt-2" id="description">
        <h2 class="fw-bold">Description:</h2>
        <p><?php echo $postData["description"];?></p>
    </section>
    <section class="mt-5" id="recipe">
        <h2 class="fw-bold">Recipe:</h2>
        <h3>Ingredients:</h3>
        <ul style="margin-left: 20px;">
            <?php foreach(json_decode($recipeData["ingredients"], true) as $ing => $q) {
                echo "<li>".$ing.": ".$q."</li>";
            }?>
        </ul>
        <h3>Method:</h3>
        <p><?php echo $recipeData["method"];?></p>
    </section>
</article>
<section class="container mt-5 mb-5 col-12 col-md-10 col-lg-8">
    <h2 class="fw-bold" id="commentsAmount">Comments: <button id="addCommentButton" class="fs-4 fw-bold" style="float: right; border: none; background: none; padding-right: 10px;" data-bs-toggle="collapse" data-bs-target="#collapseAddComment" aria-expanded="false" aria-controls="collapseAddComment"><span style="padding-right: 0.5em">666</span><img src="img/comment-icon.png" class="icon-tiny"/></button></h2>
    <form id="collapseAddComment" method="post" class="collapse" onsubmit="return false;">
        <input type="text" name="comment" class="form-control" id="addComment" placeholder="Comment"/>
        <button type="submit" class="btn btn-info text-white mb-2 mt-1">Post Comment</button>
        <button type="reset" class="btn btn-outline-secondary mb-2 mt-1" data-bs-toggle="collapse" data-bs-target="#collapseAddComment" aria-expanded="false" aria-controls="collapseAddComment">Cancel</button>
    </form>
    <!--commenti-->
    <div id="comments"></div>
</section>
    <?php endif; ?>  
<?php endif; ?>