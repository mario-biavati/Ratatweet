var idUser = -1;
var arrayPost = [];
var cur_lastPost = 0;

function loadPosts(n_post) {
    for (let i = 0; i < n_post; i++) {
        let idPost = i + cur_lastPost;
        getPost(idPost);
    }
    cur_lastPost += n_post;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >=(document.documentElement.scrollHeight ||document.documentElement.clientHeight);
}

axios.get('utils/api.php?q=getLoggedUser').then(r1 => {

    idUser = r1.data.IDuser;

    axios.get('utils/api.php?q=getFeedPosts').then(r2 => {

        r2.data.forEach(element => {
            arrayPost.push(element.IDpost);
        });

        loadPosts(5);
    });
});