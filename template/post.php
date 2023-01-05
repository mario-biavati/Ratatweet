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
    <div class="row">
        <div class="d-flex flex-column col-10">
            <h1><?php echo $postData["title"];?></h1>
            <div class="d-flex">
                <h5><?php echo $postData["username"];?></h5>
            </div>
        </div>
        <div class="float-right d-flex col-2 justify-content-end">
            <a href="#"><img src="img/recipe-icon.png" style="max-width: 12vw; max-height: 60px;"/></a>
        </div>
    </div>
    <div class="row">
        <img src="data:image/png;base64,<?php echo $postData["pic"]; ?>" style="max-width: 100%; height: auto;"/>
    </div>
    <div class="row mt-1">
        <div class="d-flex">
            <div class="rating">
                <input type="radio" name="rating" value="5" id="star5"><label for="star5">☆</label> <input type="radio" name="rating" value="4" id="star4"><label for="star4">☆</label> <input type="radio" name="rating" value="3" id="star3"><label for="star3">☆</label> <input type="radio" name="rating" value="2" id="star2"><label for="star2">☆</label> <input type="radio" name="rating" value="1" id="star1"><label for="star1">☆</label>
            </div>
            <div class="d-flex align-items-center">
                <h5 class="fs-2 pt-2 ps-1">15</h5>
            </div>
        </div>
    </div>
    <section class="row mt-2" id="description">
        <h2 class="fw-bold">Description:</h2>
        <p><?php echo $postData["description"];?></p>
    </section>
    <section class="row mt-5" id="recipe">
        <h2 class="fw-bold">Recipe:</h2>
        <h3>Ingredients:</h3>
        <ul>
            <?php foreach(json_decode($recipeData["ingredients"], true) as $ing => $q) {
                echo "<li>".$ing.": ".$q."</li>";
            }?>
        </ul>
        <h3>Method:</h3>
        <p><?php echo $recipeData["method"];?></p>
    </section>
</article>
<section class="container mt-5 mb-5 col-12 col-md-10 col-lg-8">
    <h2 class="fw-bold" id="commentsAmount">Comments: <button class="fs-4 fw-bold" style="float: right; border: none; background: none; padding-right: 10px;" data-bs-toggle="collapse" data-bs-target="#collapseAddComment" aria-expanded="false" aria-controls="collapseAddComment">123 <img src="img/comment-icon.png" style="max-width: 35px"/></button></h2>
    <form id="collapseAddComment" method="post" class="collapse" onsubmit="return false;">
        <input type="text" name="comment" class="form-control" id="addComment" placeholder="Comment">
        <button type="submit" class="btn btn-info mb-2">Post Comment</button>
        <button type="reset" class="btn btn-secondary mb-2" data-bs-toggle="collapse" data-bs-target="#collapseAddComment" aria-expanded="false" aria-controls="collapseAddComment">Cancel</button>
    </form>
    <!--commenti-->
    <div id="comments"></div>
</section>
    <?php endif; ?>  
<?php endif; ?>