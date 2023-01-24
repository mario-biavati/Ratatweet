<?php
$h1_value = "Registration";
$submitValue = "Register";
$username = "";
$bio = "";
$password = "";
$text_for_image = "Select";

if(isUserLoggedIn()) {
    $h1_value = "Modification";
    $submitValue = "Modify";
    $userData = $dbh->getUserById($_SESSION["idUser"])[0];
    $username = $userData["username"];
    $bio = $userData["bio"];
    $text_for_image = "Update";
}
?>

<div class="d-block p-5 text-center">
    <form action="#" method="POST">
        <h1><?php echo $h1_value;?></h1>
        <p class="text-danger"></p>
        <ul class="list-group list-group-flush">
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto">
                <label for="username" class="fs-6 fw-semibold">Username:</label><br>
                <input type="text" id="username" name="username" placeholder="Username" class="form-control w-100" value = "<?php echo $username;?>" />
            </li>
            <?php if(!isUserLoggedIn()): ?>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto">
                <label for="password" class="fs-6 fw-semibold">Password:</label><br>
                <input type="password" name="password" id="password" placeholder="Password" class="form-control w-100" />
            </li>
            <?php endif; ?>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto mt-3">
                <label for="bio" class="fs-6 fw-semibold">Bio:</label><br>
                <textarea id="bio" name="bio" rows="3" placeholder="Description" class="form-control w-100"><?php echo $bio;?></textarea>
            </li>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto mt-3">
                <label for="pic" class="fs-6 fw-semibold"><?php echo $text_for_image;?> Profile icon:</label>
                <input type="file" accept=".jpg,.jpeg,.png" name="pic" id="pic" class="form-control-file mt-2"/>
            </li>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto mt-3">
                <input type="submit" name="submit" value=<?php echo $submitValue;?> class="btn btn-info border-dark" id="submitButton"/>
            </li>
        </ul>
    </form>
</div>