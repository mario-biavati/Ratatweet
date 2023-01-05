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
        <li>
            <input type="submit" name="submit" value="Invia"/>
            <a href="index.php">Annulla</a>
        </li>
    </ul>
    <!-- Pulsante per l'utilizzo di una ricetta salvata -->
    <button type="button" id="usa_ricetta">Usa ricetta salvata</button>
    <ul id="ricette_salvate" hidden="hidden">
        <?php foreach($savedRecipes as $recipe) : ?>
            <li>
                <input type="radio" id=<?php echo "{$recipe["IDpost"]}"; ?> name="recipe" value="<?php echo "{$recipe["IDpost"]}"; ?>"><label for="<?php echo "{$recipe["IDpost"]}"; ?>"><?php echo "{$recipe["title"]}"; ?></label>
            </li>
        <?php endforeach; ?>
    </ul>
    <!-- Pulsante per la creazione di una nuova ricetta -->
    <button type="button" id="crea_ricetta">Crea nuova ricetta</button>
    <ul id="form_crea_ricetta" hidden="hidden">
        <li>
            <label for="ingrediente">Ingrediente:</label><input type="text" id="ingrediente" name="ingrediente"/>
        </li>
        <li>
            <label for="descrizione">Quantità:</label><input type="text" name="quantita" id="quantita" />
        </li>
        <li>
            <label for="procedimento">Procedimento:</label><input type="text" name="procedimento" id="procedimento" />
        </li>
        <li>
            <input type="submit" name="submit" value="Conferma"/>
            <a href="index.php">Annulla</a>
        </li>
    </ul>
</form>