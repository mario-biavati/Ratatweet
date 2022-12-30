var comments = document.getElementById("comments");
var cur_lastComment = 0;
var canPrintComment = true;
var arrayComment = [];
var arrayReply = [];

document.addEventListener("scroll", () => reloadComments());

function printComment(idComment) {
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
            <span>
                ${comment.text}
            </span>
            <div class="d-flex mt-1">
                <button style="border: none; background: none; margin-right: 5px;"><img src="img/like-icon.png" class="like liked"/>${comment.likes}</button>
                <button id="replyButton${comment.IDcomment}" class="ms-1 btn btn-info" style="max-height: 40px;" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="comment${comment.IDcomment}Replies">
                    Replies ▼
                </button>
                <button class="ms-1 fw-bold" style="float: right; border: none; background: none; padding-right: 10px;" data-bs-toggle="collapse" data-bs-target="#collapseAddComment${comment.IDcomment}" aria-expanded="false" aria-controls="collapseAddComment${comment.IDcomment}"><img src="img/comment-icon.png" style="max-width: 35px"/></button>
            </div>
        </div>
        <form id="collapseAddComment${comment.IDcomment}" class="collapse col-11 offset-1" onsubmit="" target="#">
            <input type="text" class="mt-1 form-control" placeholder="Reply">
            <button type="submit" class="btn btn-info mb-2 mt-1">Post Reply</button>
            <button class="btn btn-secondary mb-2 mt-1" data-bs-toggle="collapse" data-bs-target="#collapseAddComment${comment.IDcomment}" aria-expanded="false" aria-controls="collapseAddComment${comment.IDcomment}">Cancel</button>
        </form>
        <div class="offset-1 collapse col-11" id="comment${comment.IDcomment}Replies">
    
        </div>
    </div>`;
        main.innerHTML += htmlContent;
    }).then(r1 => {
        //get replies
        axios.get('utils/api.php?q=getReplies&id=' + idComment).then(r => {
            arrayReply[idComment] = [];
            r.data.forEach(element => {
                arrayReply[idComment].push(element.IDcomment);
            });
            if (arrayReply[idComment].length == 0) {
                document.getElementById("replyButton"+idComment).style.display = "none";
            }
        });
    });
}

function loadReplies(n_replies) {

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