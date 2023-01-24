let navs = document.querySelectorAll("main ul.nav li");

function deleteRecipe(idPost) {
    const formData = new FormData();
    formData.append('q', "deleteRecipe");
    formData.append('idPost', idPost);
    axios.post('utils/api.php', formData).then(response => {
        const idRecipe = "#Post" + idPost;
        document.querySelector(idRecipe).remove();
    });
}

function useRecipe(idRecipe) {
    location.href = "create_post.php?IDrecipe=" + idRecipe;
}

function selectSavedRecipes() {
    let elem1 = document.getElementById("savedRecipes0");
    elem1.classList.remove("d-none");
    elem1.classList.add("d-block");
    let elem2 = document.getElementById("savedRecipes1");
    elem2.classList.remove("d-block");
    elem2.classList.add("d-none");
    navs[0].style.backgroundColor = null;
    navs[1].style.backgroundColor = "#dee2e6";
}
function selectMyRecipes() {
    let elem1 = document.getElementById("savedRecipes1");
    elem1.classList.remove("d-none");
    elem1.classList.add("d-block");
    let elem2 = document.getElementById("savedRecipes0");
    elem2.classList.remove("d-block");
    elem2.classList.add("d-none");
    navs[1].style.backgroundColor = null;
    navs[0].style.backgroundColor = "#dee2e6";
}

navs[0].addEventListener("click", selectSavedRecipes);
navs[1].addEventListener("click", selectMyRecipes);

navs[0].click();