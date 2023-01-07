function deleteRecipe(idPost) {
    const formData = new FormData();
    formData.append('q', "deleteRecipe");
    formData.append('idPost', idPost);
    axios.post('utils/api.php', formData).then(response => {
        //console.log(response.data);
        const idButton = "#DeleteRecipe-button" + idPost;
        document.querySelector(idButton).innerText = "Deleted!";
    });
}

function useRecipe(idRecipe) {
    location.href = "create_post.php?IDrecipe=" + idRecipe;
}