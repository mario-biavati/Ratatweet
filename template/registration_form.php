<div class="d-block p-5 text-center">
    <form action="#" method="POST">
        <h1>Registration</h1>
        <p class="text-danger"></p>
        <ul class="list-group list-group-flush">
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto">
                <input type="text" id="username" name="username" placeholder="Username" class="form-control w-100"/>
            </li>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto">
                <input type="password" name="password" id="password" placeholder="Password" class="form-control w-100"/>
            </li>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto mt-3">
                <textarea id="bio" name="bio" rows="3" placeholder="Description" class="form-control w-100"></textarea>
            </li>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto mt-3">
                <label for="pic" class="fs-6 fw-semibold">Select Profile icon:</label>
                <input type="file" accept=".jpg,.jpeg,.png" name="pic" id="pic" class="form-control-file mt-2"/>
            </li>
            <li class="list-group-item border-0 col-9 col-md-5 col-lg-4 mx-auto mt-3">
                <input type="submit" name="submit" value="Register" class="btn btn-info text-white"/>
            </li>
        </ul>
    </form>
</div>