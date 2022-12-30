var comments = document.getElementById("comments");
var cur_lastComment = 0;
var canPrintComment = true;
var arrayComment = [];
var arrayReply = [];

document.addEventListener("scroll", () => reloadComments());

function printComment(idComment) {
    //load comment
    axios.get('utils/api.php?q=getComment&id=' + idComment).then(r => {
        let post = r.data;
        let htmlContent = 
            `<div id="ID_COMMENTO" class="row mt-2">
                <div class="d-flex col-1" style="max-width: 60px; min-width: 50px;">
                    <img src="../img/recipe-icon.png" style="max-width: 40px; max-height: 40px; margin-top: 10px;">
                </div>
                <div class="d-flex flex-column col-10 col-lg-11">
                    <span class="fw-bold">
                        NOME_PERSONA
                    </span>
                    <span>
                        A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A
                    </span>
                    <div class="d-flex">
                        <button style="border: none; background: none; margin-right: 5px;"><img src="../img/like-icon.png" style="max-width: 20px; margin-right: 5px;"/>23</button>
                        <a class="btn btn-primary" style="max-height: 40px;" data-bs-toggle="collapse" href="#comment1Replies" role="button" aria-expanded="false" aria-controls="comment1Replies">
                            Replies ▼
                        </a>
                    </div>
                </div>
                <!--risposte commento 1-->
                <div class="offset-1 collapse" id="comment1Replies">
                    <!--risposta 1-->
                    <div id="ID_COMMENTO" class="row mt-2">
                        <div class="d-flex col-1" style="max-width: 60px; min-width: 50px;">
                            <img src="../img/recipe-icon.png" style="max-width: 40px; max-height: 40px; margin-top: 10px;">
                        </div>
                        <div class="d-flex flex-column col-10">
                            <span class="fw-bold">
                                NOME_PERSONA
                            </span>
                            <span>
                                A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A
                            </span>
                            <div class="d-flex">
                                <button style="border: none; background: none; margin-right: 5px;"><img src="../img/like-icon.png" style="max-width: 20px; margin-right: 5px;"/>15</button>
                            </div>
                        </div>
                    </div>
                    <!--risposta 2-->
                    <div id="ID_COMMENTO" class="row mt-2">
                        <div class="d-flex col-1" style="max-width: 60px; min-width: 50px;">
                            <img src="../img/recipe-icon.png" style="max-width: 40px; max-height: 40px; margin-top: 10px;">
                        </div>
                        <div class="d-flex flex-column col-10">
                            <span class="fw-bold">
                                NOME_PERSONA
                            </span>
                            <span>
                                A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A A
                            </span>
                            <div class="d-flex">
                                <button style="border: none; background: none; margin-right: 5px;"><img src="../img/like-icon.png" style="max-width: 20px; margin-right: 5px;"/></button>
                            </div>
                        </div>
                    </div>
                </div>`;
        main.innerHTML += htmlContent;
    });
    //get replies
    axios.get('utils/api.php?q=getReplies&id=' + idComment).then(r => {

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