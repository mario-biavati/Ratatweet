<article class="container col-12 col-md-10 col-lg-8">
    <div>
        <div class="d-block w-100">
            <div class="d-flex justify-content-between">
                <h1><?php echo $postData["title"];?></h1>
                <?php if(isset($_SESSION["idUser"]) && $_SESSION["idUser"]==$postData["IDuser"]):?> 
                <button id="deletePostButton" aria-label="Delete post" class="btn btn-danger border-dark py-1 my-auto" onclick="deletePost(<?php echo $id;?>)">Delete</button>
                <?php else: ?>
                <button id="recipe-button" class="bg-white border-0"><img src="img/recipe-icon.png" aria-label="Save recipe" alt="Save recipe" style="height: calc(1.5rem + 1.5vw);"/></button>
                <?php endif; ?>
            </div>
            <h2 class="fs-5"><a <?php echo ($postData["IDuser"]==1) ? 'role="link" aria-disabled="true"': 'href="user_page.php?id='.$postData["IDuser"].'"'?>><?php echo $postData["username"];?></a></h2>
        </div>
    </div>
    <div>
        <img src="data:image/png;base64,<?php echo $postData["pic"]; ?>" alt="<?php echo $postData["title"];?>" class="mw-100 w-100 h-auto rounded"/>
    </div>
    <div class="w-100 row mt-1">
        <div class="d-flex justify-content-between">
            <div class="rating">
                <input type="radio" aria-label="vota 5 stelle" name="rating" value="5" id="star5" onclick="insertRating(5)"><label for="star5">★</label> <input type="radio" aria-label="vota 4 stelle" name="rating" value="4" id="star4" onclick="insertRating(4)"><label for="star4">★</label> <input type="radio" aria-label="vota 3 stelle" name="rating" value="3" id="star3" onclick="insertRating(3)"><label for="star3">★</label> <input type="radio" aria-label="vota 2 stelle" name="rating" value="2" id="star2" onclick="insertRating(2)"><label for="star2">★</label> <input type="radio" aria-label="vota 1 stelle" name="rating" value="1" id="star1" onclick="insertRating(1)"><label for="star1">★</label>
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
    <h2 class="fw-bold" id="commentsAmount">Comments: <button id="addCommentButton" class="fs-4 fw-bold" style="float: right; border: none; background: none; padding-right: 10px;" data-bs-toggle="collapse" data-bs-target="#collapseAddComment" aria-expanded="false" aria-controls="collapseAddComment"><span style="padding-right: 0.5em">666</span><img src="img/comment-icon.png" alt="Comment post" class="icon-tiny"/></button></h2>
    <form id="collapseAddComment" method="post" class="collapse" onsubmit="return false;">
        <label for="addComment" hidden>Write Comment</label><input type="text" name="comment" class="form-control" id="addComment" placeholder="Comment"/>
        <button type="submit" class="btn btn-info border-dark mb-2 mt-1">Post Comment</button>
        <button type="reset" class="btn btn-outline-secondary mb-2 mt-1" data-bs-toggle="collapse" data-bs-target="#collapseAddComment" aria-expanded="false" aria-controls="collapseAddComment">Cancel</button>
    </form>
    <!--commenti-->
    <div id="comments"></div>
</section>