<?php
if (isset($_GET["idPost"]))
{
    $id = $_GET["idPost"];
    $postData = $dbh->getPostById($id);
    if (count($postData) == 1) {
        $postData = $postData[0];
        echo `
        <article id="{$postData["IDpost"]}">
            <header>
                <a href="">
                    <h1> {$postData["title"]} </h1>
                </a>
                <a href="">
                    <h2> {$postData["username"]} </h2>
                </a>
                <input type="image" src="img/recipe-icon.png" alt="Save recipe" onclick="">
            </header>
            <section>
                <div>
                    <img src="{$postData["pic"]}" alt="{$postData["title"]}" />
                </div>
            </section>
            <footer>
                <div alt="Average rating">
                    <img src="./img/stella_vuota.png" id="{$postData["IDpost"]}-Star1" alt="vota 1 stella" />
                    <img src="./img/stella_vuota.png" id="{$postData["IDpost"]}-Star2" alt="vota 2 stelle" />
                    <img src="./img/stella_vuota.png" id="{$postData["IDpost"]}-Star3" alt="vota 3 stelle" />
                    <img src="./img/stella_vuota.png" id="{$postData["IDpost"]}-Star4" alt="vota 4 stelle" />
                    <img src="./img/stella_vuota.png" id="{$postData["IDpost"]}-Star5" alt="vota 5 stelle" />
                    <h2>"Average rating: {$postData["avgRating"]}"</h2>
                </div>
                <div alt="Comments number">
                    <h2>{$postData["numComments"]}</h2>
                    <a href="">
                        <img src="img/comment-icon.png" alt="Guarda commenti" />
                    </a>
                </div>
            </footer>
        </article>`;
    }   
}
else
{
    // error
    echo "";
}
?>