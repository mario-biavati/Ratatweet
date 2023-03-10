<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta charset="UTF-8"/>
    <title><?php echo $templateParams["title"]; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/rating.css" rel="stylesheet"/>
    <link href="css/like.css" rel="stylesheet"/>
    <link rel="shortcut icon" type="image/png" href="img/ratatweet-icon.png"/>
</head>
<body>
    <header class="fixed-top bg-white d-flex justify-content-end overflow-hidden" style="height: 7vh;">
        <?php
        if(isset($templateParams["header"])){
            require($templateParams["header"]);
        }
        ?>
    </header>
    <nav id="navBar" class="navbar">
        <!--Reindirizzamento se un utente è loggato o meno-->
        <?php 
            $href_notification = "notifications_page.php";
            $href_user = "user_page.php";
            $href_recipe = "recipes_page.php";
            $href_create_post = "create_post.php";
        ?>
        <!--Mobile-->
        <div class="d-md-none container">
            <ul class="border-top border-dark fixed-bottom navbar-nav d-flex flex-row bg-info" style="height: 7vh;">
                <li class="d-flex justify-content-center p-2 navbar-item" style="min-width: 20%">
                    <button class="bg-none border-0 p-0 mb-auto" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch"><img src="img/search-icon.png" alt="Seach" class="img-fluid" style="max-height: 30px"/></button>
                </li>
                <li class="col-9 me-2 collapse collapse-horizontal" id="collapseSearch">
                    <form class="py-2" action="index.php">
                        <input type="search" name="search" id="searchBar" class="form-control" placeholder="Search" aria-label="Search" style="max-height: 30px; width: 72vw"/>
                        <label for="searchBar" hidden>Insert search text</label>
                    </form>
                </li>
                <li class="d-flex justify-content-center p-2 navbar-item" style="min-width: 20%">
                    <a href="index.php"><img src="img/home-icon.png" alt="Home" class="img-fluid" style="max-height: 30px"/></a>
                </li>
                <li class="d-flex justify-content-center p-2 navbar-item" style="min-width: 20%">
                    <a href="<?php echo "{$href_notification}"; ?>" class="position-relative"><img src="img/notification-icon.png" alt="Notifications" class="img-fluid" style="max-height: 30px"/>
                        <span class="position-absolute top-0 notification start-100 translate-middle badge rounded-pill bg-danger" style="max-height: 20px" hidden>99+
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                </li>
                <li class="d-flex justify-content-center p-2 navbar-item" style="min-width: 20%">
                    <a href="<?php echo "{$href_recipe}"; ?>"><img src="img/recipe-icon.png" alt="My Recipies" class="img-fluid" style="max-height: 30px"/></a>
                </li>
                <li class="d-flex justify-content-center p-2 navbar-item" style="min-width: 20%">
                    <a href="<?php echo "{$href_user}"; ?>"><img src="img/default-profile-icon.png" alt="Profile" class="img-fluid" style="max-height: 30px"/></a>
                </li>
            </ul>
        </div>
        <!--Desktop-->
        <div class="d-none d-md-block">
            <ul class="fixed-top col-1 col-lg-3 border-dark border-end d-flex flex-column bg-info text-dark p-3 navbar-nav" style="height: 100vh;">
                <li class="navbar-item">
                <button class="d-flex mt-5 mb-3 p-1 d-flex text-decoration-none text-dark border-0 bg-none" data-bs-toggle="collapse" data-bs-target="#collapseSearchMD" aria-expanded="false" aria-controls="collapseSearchMD"><img src="img/search-icon.png" alt="Search" class="img-fluid" style="max-height: 30px"/><span class="d-none d-lg-block ms-5 me-5 fs-5 fw-semibold">Search</span></button>
                <form action="index.php" class="collapse justify-content-center search-bar" id="collapseSearchMD">
                    <div class="py-2 pe-2 bg-info rounded">
                       <input type="search" name="search" id="searchBarLG" class="form-control" placeholder="Search" aria-label="Search" style="max-height: 40px"/>
                       <label for="searchBarLG" hidden>Insert search text</label>
                    </div>
                </form>
                </li>
                <li class="navbar-item">
                    <a href="index.php" class="d-flex my-4 p-1 text-decoration-none text-dark"><img src="img/home-icon.png" alt="Home" class="img-fluid" style="max-height: 30px"/><span class="d-none d-lg-block ms-5 me-5 fs-5 fw-semibold">Home</span></a>
                </li>
                <li class="navbar-item">
                    <a href="<?php echo "{$href_notification}"; ?>" class="d-flex my-4 p-1 text-decoration-none text-dark">
                        <div class="position-relative">
                            <img src="img/notification-icon.png" alt="Notifications" class="img-fluid" style="max-height: 30px"/>
                            <span class="position-absolute top-0 notification start-100 translate-middle badge rounded-pill bg-danger" style="max-height: 20px" hidden>99+
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </div>
                        <span class="d-none d-lg-block ms-5 fs-5 fw-semibold">Notifications</span>
                    </a>
                </li>  
                <li class="navbar-item">
                    <a href="<?php echo "{$href_recipe}"; ?>" class="d-flex my-4 p-1 text-decoration-none text-dark"><img src="img/recipe-icon.png" alt="My Recipies" class="img-fluid" style="max-height: 30px"/><span class="d-none d-lg-block ms-5 me-2 fs-5 fw-semibold">Recipes</span></a>
                </li>
                <li class="navbar-item">
                    <a href="<?php echo "{$href_user}"; ?>" class="d-flex my-4 p-1 text-decoration-none text-dark"><img src="img/default-profile-icon.png" alt="Profile" class="img-fluid" style="max-height: 30px"/><span class="d-none d-lg-block ms-5 me-5 fs-5 fw-semibold">Profile</span></a>
                </li>
                <li class="navbar-item d-none d-lg-block mt-auto mb-5 p-3 text-center">
                    <a href="<?php echo "{$href_create_post}"; ?>" class="text-decoration-none text-dark">
                        <div class="fs-5 fw-semibold mb-2">Create Post</div>
                        <img src="img/upload-icon.png" alt="Create Post" class="img-fluid" style="max-height: 60px;"/>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <main class="offset-md-1 offset-lg-3" style="margin-top: 8vh; margin-bottom: 10vh;">
        <?php
        if(isset($templateParams["main"])){
            require($templateParams["main"]);
        }
        ?>
    </main>
    <!--Inclusione script Javascript-->
    <script>
    <?php foreach($_GET as $key => $val) {
        echo 'const '.$key.'="'.$val.'";';
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