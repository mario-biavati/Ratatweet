<?php
if (isUserLoggedIn()):
    $idUser = $_SESSION["idUser"];
    $allRecipes[0] = $dbh->getSavedRecipes($idUser);
    $allRecipes[1] = $dbh->getUserRecipes($idUser); ?>
    
    <!-- tabs -->
    <ul class="nav justify-content-around">
        <li class="nav-item p-2 w-50 text-secondary text-center fs-6 border border-bottom-0 rounded-top" role="button">Saved Recipes</li>
        <li class="nav-item p-2 w-50 text-secondary text-center fs-6 border border-bottom-0 rounded-top" role="button">My Recipes</li>
    </ul>
    <!-- containers -->
    <?php foreach($allRecipes as $n => $recipes): ?>
    <div id="savedRecipes<?php echo $n; ?>" class="d-none">
    <?php if(empty($recipes)):
        ?>
        <p class="fs-2 text-center text-muted d-block mx-auto my-5">No <?php if($n == 0) echo "saved"; ?> recipes</p>
    <?php else: ?>
        <section id="comments" class="container mt-5 mb-5 col-12 col-md-10 col-lg-8">
        <?php foreach($recipes as $recipe): ?>
            <!--visualizzazione titolo ricetta-->
            <div id=<?php echo "Post{$recipe["IDpost"]}"; ?> class="row mt-2">
                <div class="d-flex col-1" style="max-width: 60px; min-width: 50px;">
                    <img src="img/recipe-icon.png" style="max-width: 40px; max-height: 40px; margin-top: 10px;">
                </div>
                <div class="d-flex flex-column col-10 col-lg-11">
                    <span class="fw-bold">
                        <?php echo "{$recipe["title"]}"; ?>
                    </span>
                    <span>
                        <?php echo "by {$recipe["username"]}"; ?>
                    </span>
                    <div class="d-flex">
                        <a class="btn btn-primary" style="max-height: 40px;" data-bs-toggle="collapse" href="#more<?php echo "{$recipe["IDpost"]}"; ?>" role="button" aria-expanded="false" aria-controls="more<?php echo "{$recipe["IDpost"]}"; ?>">
                            See ▼
                        </a>
                    </div>
                </div>
                <!--visualizzazione dettagli ricetta-->
                <div class="offset-1 collapse" id="more<?php echo "{$recipe["IDpost"]}"; ?>">
                    <!--ingredienti-->
                    <div id="<?php echo "Ingredients{$recipe["IDrecipe"]}"; ?>ingredients" class="row mt-2">
                        <div class="d-flex flex-column col-10">
                            <span class="fw-bold">
                                INGREDIENTS
                            </span>
                            <span>
                                <ul>
                                <?php foreach(json_decode($recipe["ingredients"], true) as $ing => $q) {
                                    echo "<li>".$ing.": ".$q."</li>";
                                }?>
                                </ul>
                            </span>
                        </div>
                    </div>
                    <!--procedura-->
                    <div id="<?php echo "Method{$recipe["IDrecipe"]}"; ?>method" class="row mt-2">
                        <div class="d-flex flex-column col-10">
                            <span class="fw-bold">
                                METHOD
                            </span>
                            <span>
                                <?php echo "{$recipe["method"]}"; ?>
                            </span>
                        </div>
                    </div>
                    <!-- Pulsanti "Usa" ed "Elimina" -->
                    <button type="button" alt="Use recipe" id="UseRecipe-button<?php echo $recipe['IDpost']; ?>" onclick="useRecipe(<?php echo $recipe['IDrecipe']; ?>)">Use</button>
                    <button type="button" alt="Delete recipe" id="DeleteRecipe-button<?php echo $recipe['IDpost']; ?>" onclick="deleteRecipe(<?php echo $recipe['IDpost']; ?>)">Delete</button>
                </div>

            </div>
        <?php endforeach;?>
        </section>
    <?php endif;?>
    </div>
    <?php endforeach; ?>
<?php endif;?>