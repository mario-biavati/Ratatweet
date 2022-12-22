<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $templateParams["title"]; ?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <header>
        <?php
        if(isset($templateParams["header"])){
            require($templateParams["header"]);
        }
        ?>
    </header>
    <nav>
        <!--Mobile-->
        <div class="d-md-none container">
            <div class="d-flex border-top border-dark fixed-bottom">
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 25%">
                    <input type="image" src="https://cdn-icons-png.flaticon.com/512/151/151773.png" class="img-fluid" style="max-height: 30px;" data-bs-toggle="collapse" data-bs-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch"/>
                </div>
                <div class="col-9 bg-info collapse collapse-horizontal" id="collapseSearch">
                    <div class="p-2">
                        <input type="search" id="searchBar" class="form-control" placeholder="Type here" aria-label="Search" style="max-height: 30px; width: 70vw"/>
                    </div>
                </div>
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 25%">Two</div>
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 25%">Three</div>
                <div class="d-flex justify-content-center p-2 bg-info" style="min-width: 25%">Four</div>
            </div>
        </div>
        <!--Desktop-->
        <div class="d-none d-md-block fixed-bottom">
            <div class="vh-100 vw-100">
                <div class="h-100 w-25 bg-info">
                    <div class="h-auto p-2 bg-info">One</div>
                    <div class="h-auto p-2 bg-info">Two</div>
                    <div class="h-auto p-2 bg-info">Three</div>
                    <div class="h-auto p-2 bg-info">Four</div>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <?php
        if(isset($templateParams["main"])){
            require($templateParams["main"]);
        }
        ?>
    </main>
    <!--Inclusione script Javascript-->
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