<?php
if (isUserLoggedIn()):
    $idUser = $_SESSION["idUser"];
    $recipes = $dbh->getSavedRecipes($idUser);
    if(empty($recipes)):
        ?>
        <h2>No saved recipes</h2>
    <?php else: ?>
        <section id="comments" class="container mt-5 mb-5 col-12 col-md-10 col-lg-8">
        <?php foreach($recipes as $recipe): ?>
            <!--visualizzazione titolo ricetta-->
            <div id=<?php echo "{$recipe["IDpost"]}"; ?> class="row mt-2">
                <div class="d-flex col-1" style="max-width: 60px; min-width: 50px;">
                    <img src="../img/recipe-icon.png" style="max-width: 40px; max-height: 40px; margin-top: 10px;">
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
                    <div id="<?php echo "{$recipe["IDpost"]}"; ?>ingredients" class="row mt-2">
                        <div class="d-flex flex-column col-10">
                            <span class="fw-bold">
                                INGREDIENTS
                            </span>
                            <span>
                                <ul>
                                    <?php $ingredients=explode(",", $recipe["ingredients"]);
                                    foreach($ingredients as $ingredient):
                                        $ingDescription = explode(":", $ingredient);
                                        $name = $ingDescription[0];
                                        $quantity = $ingDescription[1];
                                    ?>
                                    <li><?php echo "{$name}"; ?> : <?php echo "{$quantity}"; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </span>
                        </div>
                    </div>
                    <!--procedura-->
                    <div id="<?php echo "{$recipe["IDpost"]}"; ?>method" class="row mt-2">
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
                    <button type="button" alt="Use recipe" id="Use-button" onclick="seenNotification(<?php echo $notification['IDnotification']; ?>)">Seen</button>
                    <button type="button" alt="Delete recipe" id="SeenNotification-button" onclick="seenNotification(<?php echo $notification['IDnotification']; ?>)">Seen</button>
                </div>

            </div>
        <?php endforeach;?>
        </section>
    <?php endif;?>
<?php endif;?>