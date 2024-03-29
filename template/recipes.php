<?php
if (isUserLoggedIn()):
    $idUser = $_SESSION["idUser"];
    $allRecipes[0] = $dbh->getSavedRecipes($idUser);
    $allRecipes[1] = $dbh->getUserRecipes($idUser); ?>
    
    <!-- tabs -->
    <ul class="nav justify-content-around">
        <li class="nav-item w-50 text-center fs-6 border border-bottom-0 rounded-top">
            <button class="bg-none border-0 text-secondary  p-2 w-100 h-100">Saved Recipes</button>
        </li>
        <li class="nav-item w-50 text-center fs-6 border border-bottom-0 rounded-top">
            <button class="bg-none border-0 text-secondary p-2 w-100 h-100">My Recipes</button>
        </li>
    </ul>
    <!-- containers -->
    <?php foreach($allRecipes as $n => $recipes): ?>
    <section id="savedRecipes<?php echo $n; ?>" class="d-none">
    <?php if(empty($recipes)):
        ?>
        <p class="fs-2 text-center text-muted d-block mx-auto my-5">No <?php if($n == 0) echo "saved"; ?> recipes</p>
    <?php else: ?>
        <ul class="list-group my-5 mx-auto col-10 col-md-8">
        <?php foreach($recipes as $recipe): ?>
            <!--visualizzazione titolo ricetta-->
            <li id=<?php echo "Post{$recipe["IDpost"]}"; ?> class="list-group-item border rounded w-100 mx-auto mt-2">
                <div class="d-block d-md-flex justify-content-between">
                    <div class="d-flex col-9">
                        <img src="img/recipe-icon.png" alt="Recipe icon" class="icon-tiny">
                        <div class="d-block h-100 ms-3">
                            <h1 class="fw-bold fs-5 h-50 m-0">
                                <a href="post.php?id=<?php echo $recipe["IDpost"]?>" class="decoration-none"><?php echo "{$recipe["title"]}"; ?></a>
                            </h1>
                            <p class="h-auto my-2">
                                by <a href="user_page.php?id=<?php echo $recipe["IDuser"]?>"><?php echo $recipe["username"]; ?></a>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-auto">
                        <a class="btn btn-info border-dark" style="max-height: 40px;" aria-label="See all ingredients" data-bs-toggle="collapse" href="#more<?php echo "{$recipe["IDpost"]}"; ?>" role="button" aria-expanded="false" aria-controls="more<?php echo "{$recipe["IDpost"]}"; ?>">
                            See ▼
                        </a>
                    </div>
                </div>
                <!--visualizzazione dettagli ricetta-->
                <div class="my-2 p-2 border-top collapse" id="more<?php echo "{$recipe["IDpost"]}"; ?>">
                    <!--ingredienti-->
                    <div id="<?php echo "Ingredients{$recipe["IDrecipe"]}"; ?>ingredients">
                        <h2 class="fs-5">
                            INGREDIENTS
                        </h2>
                        <ul>
                        <?php foreach(json_decode($recipe["ingredients"], true) as $ing => $q) {
                            echo "<li>".$ing.": ".$q."</li>";
                        }?>
                        </ul>
                    </div>
                    <!--procedura-->
                    <div id="<?php echo "Method{$recipe["IDrecipe"]}"; ?>method" class="mt-2">
                        <div class="d-flex flex-column col-10">
                            <h2 class="fs-5">
                                METHOD
                            </h2>
                            <p>
                                <?php echo "{$recipe["method"]}"; ?>
                            </p>
                        </div>
                    </div>
                    <!-- Pulsanti "Usa" ed "Elimina" -->
                    <button type="button" aria-label="Use recipe" class="btn btn-info border-dark" id="UseRecipe-button<?php echo $recipe['IDpost']; ?>" onclick="useRecipe(<?php echo $recipe['IDrecipe']; ?>)">Use</button>
                    <?php if($n == 0): ?><button type="button" aria-label="Delete recipe" class="ms-3 btn btn-danger border-dark" id="DeleteRecipe-button<?php echo $recipe['IDpost']; ?>" onclick="deleteRecipe(<?php echo $recipe['IDpost']; ?>)">Delete</button><?php endif; ?>
                </div>

            </li>
        <?php endforeach;?>
                    </ul>
    <?php endif;?>
    </section>
    <?php endforeach; ?>
<?php endif;?>