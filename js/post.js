var comments = document.getElementById("comments");
var cur_lastComment = 0;
var canPrintComment = true;
var arrayComment = [];
var arrayReply = [];

document.addEventListener("scroll", () => reloadComments());

function printComment(idComment, where, first = false) {
    //load comment
    axios.get('utils/api.php?q=getComment&id=' + idComment).then(r => {
        let comment = r.data;
        let htmlContent = 
        `<div id="${comment.IDcomment}" class="row mt-2">
        <div class="d-flex col-1" style="max-width: 60px; min-width: 50px;">
            <img src="${comment.pic}" style="max-width: 40px; max-height: 40px; margin-top: 10px;">
        </div>
        <div class="d-flex flex-column col-10 col-lg-11">
            <a class="fw-bold" href="user_page.php?id=${comment.IDuser}">
                ${comment.username}
            </a>
            <p>
                ${comment.text}
            </p>
            <div class="d-flex mt-1">
                <button style="border: none; background: none; margin-right: 5px;"><img src="img/like-icon.png" class="like liked"/>${comment.likes}</button>
                <button id="replyButton${comment.IDcomment}" class="ms-1 btn btn-info" style="max-height: 40px;" data-bs-toggle="collapse" role="button" aria-expanded="false" data-bs-target="#comment${comment.IDcomment}Replies" aria-controls="comment${comment.IDcomment}Replies">
                    Replies ▼
                </button>
                <button class="ms-1 fw-bold" style="float: right; border: none; background: none; padding-right: 10px;" data-bs-toggle="collapse" data-bs-target="#collapseAddComment${comment.IDcomment}" aria-expanded="false" aria-controls="collapseAddComment${comment.IDcomment}"><img src="img/comment-icon.png" style="max-width: 35px"/></button>
            </div>
        </div>
        <form id="collapseAddComment${comment.IDcomment}" class="collapse col-11 offset-1" onsubmit="return postReply(${comment.IDcomment},this);">
            <input type="text" name="comment" class="mt-1 form-control" placeholder="Reply">
            <button type="submit" class="btn btn-info mb-2 mt-1">Post Reply</button>
            <button type="reset" class="btn btn-secondary mb-2 mt-1" data-bs-toggle="collapse" data-bs-target="#collapseAddComment${comment.IDcomment}" aria-expanded="false" aria-controls="collapseAddComment${comment.IDcomment}">Cancel</button>
        </form>
        <div class="offset-1 collapse col-11" id="comment${comment.IDcomment}Replies">
            <div id="repliesContainer"></div>
            <button type="button">Load More</button>
        </div>
    </div>`;
        where.innerHTML = (first) ? (htmlContent + where.innerHTML) : (where.innerHTML + htmlContent);
    }).then(r1 => {
        //get replies
        axios.get('utils/api.php?q=getReplies&id=' + idComment).then(r => {
            arrayReply[idComment] = [];
            r.data.forEach(element => {
                arrayReply[idComment].push(element.IDcomment);
            });
            if (arrayReply[idComment].length == 0) {
                document.getElementById("replyButton"+idComment).style.display = "none";
            } else {
                //document.getElementById("replyButton"+idComment).addEventListener("click", () => {loadReplies(idComment);});
                document.getElementById("replyButton"+idComment).setAttribute("onclick", "showReplies(" + idComment + ")");
            }
        });
    });
}
function printReply(idComment, where, first = false) {
    //load comment
    axios.get('utils/api.php?q=getComment&id=' + idComment).then(r => {
        let comment = r.data;
        let htmlContent = 
        `<div id="${comment.IDcomment}" class="row mt-2">
        <div class="d-flex col-1" style="max-width: 60px; min-width: 50px;">
            <img src="${comment.pic}" style="max-width: 40px; max-height: 40px; margin-top: 10px;">
        </div>
        <div class="d-flex flex-column col-10 col-lg-11">
            <a class="fw-bold" href="user_page.php?id=${comment.IDuser}">
                ${comment.username}
            </a>
            <p>
                ${comment.text}
            </p>
            <div class="d-flex mt-1">
                <button style="border: none; background: none; margin-right: 5px;"><img src="img/like-icon.png" class="like liked"/>${comment.likes}</button>
            </div>
        </div>
    </div>`;
        where.innerHTML = (first) ? (htmlContent + where.innerHTML) : (where.innerHTML + htmlContent);
    });
}

function showReplies(idComment) {
    document.getElementById("replyButton"+idComment).removeAttribute("onclick");
    loadReplies(idComment);
}

function loadReplies(idComment) {
    let replies = document.querySelector("#comment" + idComment + "Replies>div");
    arrayReply[idComment].forEach(idReply => {
        printReply(idReply, replies);
    });
}

function loadComments(n_comments) {
    for (let i = 0; i < n_comments; i++) {

        if (i + cur_lastComment >= arrayComment.length) {
            cur_lastComment += i;
            return;
        }
        printComment(arrayComment[i + cur_lastComment], comments);
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

/* Post Comments Functions */
function postComment(form) {
    const formData = new FormData();
    formData.append('q', "postComment");
    formData.append('id', id);
    formData.append('comment', form["comment"].value);
    axios.post('utils/api.php', formData).then(r => {
        if (r.data["esito"] == false) {
            // utente non loggato, redirect al login
            axios.get('template/login_form.php').then(file => {
                document.querySelector("main").innerHTML = file.data;
                var tag = document.createElement("script");
                tag.src = "js/login.js";
                document.querySelector("body").appendChild(tag);
            });
        } else {
            printComment(r.data["id"], comments, true);
        }
    });
    form.reset();
    return false;
}
function postReply(idComment, form) {
    const formData = new FormData();
    formData.append('q', "postReply");
    formData.append('id', id);
    formData.append('idComment', idComment);
    formData.append('comment', form["comment"].value);
    axios.post('utils/api.php', formData).then(r => {
        console.log(r.data);
        if (r.data["esito"] == false) {
            // utente non loggato, redirect al login
            axios.get('template/login_form.php').then(file => {
                document.querySelector("main").innerHTML = file.data;
                var tag = document.createElement("script");
                tag.src = "js/login.js";
                document.querySelector("body").appendChild(tag);
            });
        } else {
            let replies = document.querySelector("#comment" + idComment + "Replies>div");
            printComment(r.data["id"], replies, true);
        }
    });
    return false;
}

// on page load
document.getElementById("collapseAddComment").addEventListener("submit", function (event) {
    event.preventDefault();
    postComment(document.getElementById("collapseAddComment"));
});

axios.get("utils/api.php?q=getComments&id=" + id).then(r => {
    r.data.forEach(element => {
        arrayComment.push(element.IDcomment);
    });
    loadComments(5);
});