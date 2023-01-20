var arrayPost = [];
var cur_lastPost = 0;
var canPrintPost = true;
var isFeedPage = true;

var main = document.getElementsByTagName("main")[0];

document.addEventListener("scroll", () => reloadFeed());

var searchBars = document.querySelectorAll("input[type=search]");

function printPost(idPost) {
    return new Promise((resolve) => {
        axios.get('utils/api.php?q=getPost&id=' + idPost).then(r => {
            let post = r.data;
            let htmlContent = 
            `<article class="d-flex flex-column flex-md-row">
                <div class="d-flex d-md-none">
                    <div>
                        <h1>NOME_POST</h1>
                        <span>NOME_UTENTE</span>
                    </div>
                    <div class="d-flex flex-fill justify-content-end"><img src="img/recipe-icon-save.png" style="width: 40px; height: 40px;"/></div>
                </div>
                <a href="#" class="col-md-4 me-2">
                    <img src="test/food-placeholder.png" class="img-fluid col-12 me-2" style="object-fit: cover;"/>
                </a>
                <div class="d-flex flex-column">
                    <div class="d-flex d-none d-md-block flex-column">
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column">
                                <h1>NOME_POST</h1>
                                <span>NOME_UTENTE</span>
                            </div>
                            <div class="d-flex flex-fill justify-content-end"><img src="img/recipe-icon-save.png" style="width: 40px; height: 40px;"/></div>
                        </div>
                        <p class="mt-4" style="max-height: 150px; overflow:hidden;"> a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a a</p>
                    </div>
                    <div class="d-flex flex-fill rating">
                        <div class="d-flex flex-fill justify-content-end mt-1"><img src="img/comment-icon.png" style="width: 40px; height: 40px;"/></div>
                        <span class="ratingScore">4.5</span>`
            for (let i = 5; i > 0; i--) {
                    htmlContent += `<span`;
                    if (ratingRounded == i) {
                        htmlContent += ` class="ratingDisplay"`;
                    }
                    htmlContent += `>☆</span>`;
                }
                
            htmlContent += `
                    </div>
                </div>
            </article>`;
            main.innerHTML += htmlContent;
            resolve(htmlContent);
        });
    });
}

async function loadPosts(n_post) {
    canPrintPost = false;
    let htmlContent = "";
    for (let i = 0; i < n_post; i++) {

        if (i + cur_lastPost >= arrayPost.length) {
            cur_lastPost += i;
            main.innerHTML += htmlContent;
            if (isFeedPage) {
                feed(n_post - i);
            }
            return;
        }
        htmlContent += await printPost(arrayPost[i + cur_lastPost]);
    }
    main.innerHTML += htmlContent;
    cur_lastPost = cur_lastPost + n_post;
    canPrintPost = true;
}
function isAtBottom() {
    return document.documentElement.clientHeight + window.scrollY >= (document.documentElement.scrollHeight);
}
function reloadFeed() {
    if (isAtBottom() && canPrintPost) {
        loadPosts(5);
    }
}

function resetFeed() {
    cur_lastPost = 0;
    main.innerHTML = "";
}

function psearch(n_post) {
    axios.get('utils/api.php?q=search&text='+search).then(r2 => {

        arrayPost = [];
        cur_lastPost = 0;
        r2.data.forEach(element => {
            arrayPost.push(element.IDpost);
        });
        console.log(arrayPost);
        loadPosts(n_post);
    });
}
function feed(n_post) {
    axios.get('utils/api.php?q=getFeedPosts&offset=0').then(r2 => {

        arrayPost = [];
        cur_lastPost = 0;
        r2.data.forEach(element => {
            arrayPost.push(element.IDpost);
        });
        loadPosts(n_post);
    });
}

function saveRecipe(IDrecipe) {
    const formData = new FormData();
    formData.append('q', "saveRecipe");
    formData.append('id', IDrecipe);
    axios.post('utils/api.php', formData);
    console.log('recipe '+IDrecipe+' saved!');
}

// on page load

if (typeof search === 'undefined' || search == '') {
    feed(5);
} else {
    searchBars.forEach(bar => {
        bar.setAttribute("value", search);
    });
    isFeedPage = false;
    psearch(5);
}