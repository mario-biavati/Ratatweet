function deleteRecipe(idPost) {
    const formData = new FormData();
    formData.append('q', "deleteRecipe");
    formData.append('idPost', idPost);
    axios.post('utils/api.php', formData).then(response => {
        document.querySelector("#DeleteRecipe-button").innerText = "Deleted!";
    });
}

function useRecipe(idPost) {
    location.href = "create_post.php?idPost=" + idPost;
}