<div class="d-block py-5 px-3 text-center">
    <form action="#" method="POST">
        <!-- Parametri del post -->
        <h1>Crea Post</h1>
        <p class="text-danger"></p>
        <ul class="list-group list-group-flush">
            <li class="list-group-item border-0 col-10 col-md-5 col-lg-4 mx-auto">
                <label for="titolo" class="fw-semibold">Title:</label>
                <input type="text" id="titolo" name="titolo" class="form-control"/>
            </li>
            <li class="list-group-item border-0 col-10 mx-auto">
                <label for="pic" class="fw-semibold d-block">Upload Picture:</label>
                <input type="file" accept=".jpg,.jpeg,.png" name="pic" id="pic" class="form-control-file d-block mx-auto"/>
            </li>
            <li class="list-group-item border-0 col-10 col-md-5 col-lg-4 mx-auto">
                <label for="descrizione" class="fw-semibold">Description:</label>
                <textarea name="descrizione" id="descrizione" class="form-control" rows="2"></textarea>
            </li>
        </ul>
        
        <div class="d-block mx-auto mt-3" style="height: 5vh;">
            <img src="img/recipe-icon-save.png" alt="Recipe" class="h-100"/>
        </div>
        <div class="d-flex justify-content-around my-3" id="recipe-btn">
            <button type="button" id="usa_ricetta" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#ricette_salvate:not(.show),#form_crea_ricetta.show" aria-expanded="false" aria-controls="ricette_salvate">Use saved recipe</button>
            <button type="button" id="crea_ricetta" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#form_crea_ricetta:not(.show),#ricette_salvate.show" aria-expanded="false" aria-controls="form_crea_ricetta">Create new recipe</button>
        </div>

        <div id="collapse-container" class="d-block justify-content-center col-11 mx-3">
            <!-- Pulsante per l'utilizzo di una ricetta salvata -->
            <div id="ricette_salvate" class="collapse w-100" aria-labelledby="usa_ricetta" data-parent="#collapse-container">
                <ul class="nav nav-tabs justify-content-around border-0">
                    <li class="nav-item w-50 text-center fs-6 border border-bottom-0 rounded-top" onclick="selectSavedRecipes(this)">
                        <button type="button" class="bg-none border-0 text-secondary  p-2 w-100 h-100">Saved Recipes</button>
                    </li>
                    <li class="nav-item w-50 text-center fs-6 border border-bottom-0 rounded-top" style="background-color: #dee2e6;" onclick="selectMyRecipes(this)">
                        <button type="button" class="bg-none border-0 text-secondary  p-2 w-100 h-100">My Recipes</button>
                    </li>
                </ul>
                <div class="list-group list-group-flush border border-top-0 rounded-bottom">
                    <fieldset id="sr-container" class="mx-2">
                        <ul class="list-group list-group-flush">
                    <?php 
                    if (count($savedRecipes) == 0) : ?>
                        <li class="list-group-item border-0 d-block text-center">
                            <p class="text-muted fs-5 my-2">No saved recipes</p>
                        </li>
                    <?php endif;
                    foreach($savedRecipes as $recipe) : ?>
                        <li class="list-group-item border-0 d-flex justify-content-center">
                            <input type="radio" id="<?php echo "Recipe{$recipe["IDrecipe"]}"; ?>" name="recipe" value="<?php echo "{$recipe["IDrecipe"]}"; ?>" class="col-1 h-50 my-auto d-block"/>
                            <label for="Recipe<?php echo "{$recipe["IDrecipe"]}"; ?>" class="col-10 col-md-5 col-lg-3 text-center"><?php echo "{$recipe["title"]}"; ?></label>
                        </li>
                    <?php endforeach; ?>
                        </ul>
                    </fieldset>
                    <fieldset id="mr-container" class="d-none mx-2">
                        <ul class="list-group list-group-flush">
                    <?php 
                    if (count($userRecipes) == 0) : ?>
                        <li class="list-group-item border-0 d-block text-center">
                            <p class="text-muted fs-5 my-2">No recipes</p>
                        </li>
                    <?php endif;
                    foreach($userRecipes as $recipe) : ?>
                        <li class="list-group-item border-0 d-flex justify-content-center">
                            <input type="radio" id="<?php echo "Recipe{$recipe["IDrecipe"]}"; ?>" name="recipe" value="<?php echo "{$recipe["IDrecipe"]}"; ?>" class="col-1 h-50 my-auto d-block"/>
                            <label for="Recipe<?php echo "{$recipe["IDrecipe"]}"; ?>" class="col-10 col-md-5 col-lg-3 text-center"><?php echo "{$recipe["title"]}"; ?></label>
                        </li>
                    <?php endforeach; ?>
                        </ul>
                    </fieldset>
                    </div>
            </div>
            
            <!-- Pulsante per la creazione di una nuova ricetta -->
            <div class="collapse w-100 border rounded" id="form_crea_ricetta" aria-labelledby="crea_ricetta" data-parent="#collapse-container">
                <div class="m-2">
                    <label class="fs-5 fw-semibold">Ingredients:</label>
                    <ul id="ingredients_list" class="list-group list-group-flush">
                        <li class="list-group-item border-0 d-flex row mx-0 px-0">
                            <input type="text" id="ingrediente" name="ingrediente" class="form-control w-50" placeholder="Ingredient"/><label for="ingrediente" hidden>Ingredient</label>
                            <input type="text" id="quantita" name="quantita" class="form-control w-50" placeholder="Quantity"/><label for="quantita" hidden>Quantity</label>
                        </li>
                        <li class="list-group-item border-0 mx-auto">
                            <button type="button" class="btn btn-info border-dark" id="new_ingredient" onclick="addIngredient()">Add Ingredient</button>
                        </li>
                    </ul>  
                </div>
                <div class="m-2">
                    <label for="procedimento" class="fs-5 fw-semibold">Method:</label>
                    <textarea name="procedimento" id="procedimento" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
        <input type="submit" id="uploadButton" aria-label="Create post" name="submit" value="Upload" class="mt-3 mx-auto btn btn-info border-dark"/>
        <!-- <a href="index.php">Annulla</a> -->
    </form>
</div>