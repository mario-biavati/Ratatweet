var idUser = -1;
var arrayPost = [];
var cur_lastPost = 0;
var main = document.getElementsByTagName("main")[0];

function printPost(idPost) {
    axios.get('utils/api.php?q=getPost&id=' + idPost).then(r => {
        let post = r.data;
        let htmlContent = 
        `<article id="${post.IDpost}">
            <header>
                <a href="post.php?id=${post.IDpost}">
                    <h1> ${post.title} </h1>
                </a>
                <a href="user_page.php?id=${post.IDuser}">
                    <h2> ${post.username} </h2>
                </a>
                <input type="image" src="img/recipe-icon.png" alt="Save recipe" onclick="saveRecipe(${post.IDrecipe})">
            </header>
            <section>
                <div>
                    <img src="${post.pic}" alt="${post.title}" />
                </div>
            </section>
            <footer>
                <div alt="Average rating">
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star1" alt="vota 1 stella" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star2" alt="vota 2 stelle" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star3" alt="vota 3 stelle" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star4" alt="vota 4 stelle" />
                    <img src="./img/stella_vuota.png" id="${post.IDpost}-Star5" alt="vota 5 stelle" />
                    <h2>"Average rating: ${post.avgRating}"</h2>
                </div>
                <div alt="Comments number">
                    <h2>${post.numComments}</h2>
                    <a href="post.php?id=${post.IDpost}">
                        <img src="img/comment-icon.png" alt="Guarda commenti" />
                    </a>
                </div>
            </footer>
        </article>`;
        main.innerHTML += htmlContent;
    });
}

function loadPosts(n_post) {
    for (let i = 0; i < n_post; i++) {
        let idPost = i + cur_lastPost;
        printPost(idPost);
    }
    cur_lastPost += n_post;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >=(document.documentElement.scrollHeight ||document.documentElement.clientHeight);
}

function saveRecipe() {

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