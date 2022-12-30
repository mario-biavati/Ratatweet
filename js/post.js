var comments = document.getElementById("comments");
var cur_lastComment = 0;
var canPrintComment = true;
var arrayComment = [];

document.addEventListener("scroll", () => reloadComments());

function printComment(idComment) {
    axios.get('utils/api.php?q=getComment&id=' + idComment).then(r => {
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
            <section>
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
            </section>
        </article>`;
        main.innerHTML += htmlContent;
    });
}

function loadComments(n_comments) {
    for (let i = 0; i < n_comments; i++) {

        if (i + cur_lastComment >= arrayComment.length) {
            return;
        }
        printComment(arrayComment[i + cur_lastComment]);
    }
    cur_lastComment = cur_lastComment + n_comments;
    canPrintComment = true;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >= (document.documentElement.scrollHeight);
}
function reloadComments() {
    if (isAtBottom() && canPrintComment) {
        canPrintComment = false;
        loadComments(5);
    }
}

// on page load
axios.get("utils/api.php?getComments&id=" + id).then(r => {
    r.data.forEach(element => {
        arrayComment.push(element.IDcomment);
    });
});