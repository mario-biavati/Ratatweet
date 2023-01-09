<?php
if(isUserLoggedIn()) {
    $id = $templateParams["user"];
    $savedRecipes = $dbh->getSavedRecipes($id);
}
else {
    header("Location: registration_page.php");
}
?>

<form action="#" method="POST">
    <!-- Parametri del post -->
    <h1>POST</h1>
    <p></p>
    <ul>
        <li>
            <label for="titolo">Titolo:</label><input type="text" id="titolo" name="titolo"/>
        </li>
        <li>
            <label for="descrizione">Descrizione:</label><input type="text" name="descrizione" id="descrizione" />
        </li>
        <li>
            <label for="pic">Pic:</label><input type="file" name="pic" id="pic" />
        </li>
    </ul>
    <!-- Pulsante per l'utilizzo di una ricetta salvata -->
    <button type="button" id="usa_ricetta">Usa ricetta salvata</button>
    <ul id="ricette_salvate" hidden="hidden">
        <?php foreach($savedRecipes as $recipe) : ?>
            <li>
                <input type="radio" id=<?php echo "Recipe{$recipe["IDrecipe"]}"; ?> name="recipe" value="<?php echo "{$recipe["IDrecipe"]}"; ?>"><label for="Ricetta<?php echo "{$recipe["IDrecipe"]}"; ?>"><?php echo "{$recipe["title"]}"; ?></label>
            </li>
        <?php endforeach; ?>
    </ul>
    <!-- Pulsante per la creazione di una nuova ricetta -->
    <button type="button" id="crea_ricetta">Crea nuova ricetta</button>
    <div id="form_crea_ricetta" hidden="hidden">
        <label>Ingredients:</label>
        <ul id="ingredients_list">
            <li>
                <input type="text" name="ingrediente" placeholder="Ingredient"/>
                <input type="text" name="quantita" placeholder="Quantity"/>
            </li>
            <li>
                <button type="button" id="new_ingredient" onclick="addIngredient()">Add Ingredient</button>
            </li>
        </ul>
        <label for="procedimento">Method:</label><input type="text" name="procedimento" id="procedimento" />
        </div>
    <input type="submit" name="submit" value="Invia"/>
    <a href="index.php">Annulla</a>
</form>