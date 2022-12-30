<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $templateParams["title"]; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <header class="d-flex fixed-top bg-white">
        <?php
        if(isset($templateParams["header"])){
            require($templateParams["header"]);
        }
        ?>
    </header>
    <nav id="navBar">
        <!--Reindirizzamento se un utente è loggato o meno-->
        <?php 
        if(isUserLoggedIn()) {
            $href_notification = "notifications_page.php";
            $href_user = "user_page.php";
            $href_recipe = "recipes_page.php";
        }
        else {
            $href_notification = "login.php";
            $href_user = "login.php";
            $href_recipe = "login.php";
        }
        ?>
        <!--Mobile-->
        <div class="d-md-none container">
            <div class="d-flex border-top border-dark fixed-bottom">
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 20%">
                    <input type="image" src="img/search-icon.png" class="img-fluid" style="max-height: 30px;" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch"/>
                </div>
                <div class="col-9 bg-info collapse collapse-horizontal" id="collapseSearch">
                    <form class="p-2" action="index.php">
                        <input type="search" name="search" id="searchBar" class="form-control" placeholder="Search" aria-label="Search" style="max-height: 30px; width: 73vw"/>
                    </form>
                </div>
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 20%">
                    <a href="index.php"><img src="img/home-icon.png" class="img-fluid" style="max-height: 30px"/></a>
                </div>
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 20%">
                    <a href="<?php echo "{$href_notification}"; ?>?caller=notifications.php" class="position-relative"><img src="img/notification-icon.png" class="img-fluid" style="max-height: 30px"/>
                        <span class="position-absolute top-0 notification start-100 translate-middle badge rounded-pill bg-danger" style="max-height: 20px">99+
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                </div>
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 20%">
                    <a href="<?php echo "{$href_recipe}"; ?>?caller=recipes.php"><img src="img/recipe-icon.png" class="img-fluid" style="max-height: 30px"/></a>
                </div>
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 20%">
                    <a href="<?php echo "{$href_user}"; ?>?caller=user.php"><img src="img/default-profile-icon.png" class="img-fluid" style="max-height: 30px"/></a>
                </div>
            </div>
        </div>
        <!--Desktop-->
        <div class="d-none d-md-block">
            <div class="fixed-top col-1 col-lg-3 border-dark border-end d-flex flex-column bg-info text-dark p-3">
                <a href="#" class="d-flex mt-2 mb-3 p-1 d-flex text-decoration-none text-dark" data-bs-toggle="collapse" data-bs-target="#collapseSearchMD" aria-expanded="false" aria-controls="collapseSearchMD"><img src="img/search-icon.png" class="img-fluid" style="max-height: 30px"/><h5 class="d-none d-lg-block ms-5 me-5">Search</h5></a>
                <form action="index.php" class="collapse justify-content-center" id="collapseSearchMD">
                    <input type="search" name="search" id="searchBarLG" class="form-control" placeholder="Search" aria-label="Search" style="max-height: 40px"/>
                </form>
                <a href="index.php" class="d-flex mt-3 mb-3 p-1 text-decoration-none text-dark"><img src="img/home-icon.png" class="img-fluid" style="max-height: 30px"/><h5 class="d-none d-lg-block ms-5 me-5">Home</h5></a>
                <a href="<?php echo "{$href_notification}"; ?>?caller=notifications.php" class="d-flex mt-3 mb-3 p-1 text-decoration-none text-dark">
                    <div class="position-relative">
                        <img src="img/notification-icon.png" class="img-fluid" style="max-height: 30px"/>
                        <span class="position-absolute top-0 notification start-100 translate-middle badge rounded-pill bg-danger" style="max-height: 20px">99+
                            <span class="visually-hidden">unread messages
                            </span>
                        </span>
                    </div>
                    <h5 class="d-none d-lg-block ms-5">Notifications</h5>
                </a>
                <a href="<?php echo "{$href_recipe}"; ?>?caller=recipes.php" class="d-flex mt-3 mb-3 p-1 text-decoration-none text-dark"><img src="img/recipe-icon.png" class="img-fluid" style="max-height: 30px"/><h5 class="d-none d-lg-block ms-5 me-2">Recipes</h5></a>
                <a href="<?php echo "{$href_user}"; ?>?caller=user.php" class="d-flex mt-3 mb-3 p-1 text-decoration-none text-dark"><img src="img/default-profile-icon.png" class="img-fluid" style="max-height: 30px"/><h5 class="d-none d-lg-block ms-5 me-5">Profile</h5></a>
                <div class="d-flex" style="min-height: 100vh">

                </div>
            </div>
        </div>
    </nav>
    <main class="mt-5 mb-5 offset-md-1 offset-lg-3">
        <?php
        if(isset($templateParams["main"])){
            require($templateParams["main"]);
        }
        ?>
    </main>
    <!--Inclusione script Javascript-->
    <script type="text/javascript">
    <?php foreach($_GET as $key => $val) {
        echo `var {$key} = {$val};`;
    }?>
    </script>
    <?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</body>
</html>