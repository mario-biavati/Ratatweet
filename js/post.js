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
        `<div id="${comment.IDcomment}" class="mt-2 pb-2 border-bottom">
        <div class="d-flex flex-column col-10 col-lg-11">
            <div class="d-flex justify-content-start">
                <img src="data:image/png;base64,${comment.pic}" class="icon-small border rounded">
                <a class="fw-bold my-auto ms-3" href="user_page.php?id=${comment.IDuser}">
                    ${comment.username}
                </a>
            </div>
            <p class="my-2">
                ${comment.text}
            </p>
            <div class="d-flex">
                <button onclick="like(${comment.IDcomment}, ${comment.liked}, this)" class="border-0 bg-white mr-3"><img src="img/like-icon.png" class="my-auto like`;
        if (comment.liked == 1) htmlContent += ' liked';
        htmlContent += `"/><span>${comment.likes}</span></button>
                <button id="replyButton${comment.IDcomment}" class="ms-1 btn btn-secondary" data-bs-toggle="collapse" role="button" aria-expanded="false" data-bs-target="#comment${comment.IDcomment}Replies" aria-controls="comment${comment.IDcomment}Replies">
                    Replies ▼
                </button>
                <button class="ms-1 fw-bold" style="float: right; border: none; background: none; padding-right: 10px;" data-bs-toggle="collapse" data-bs-target="#collapseAddComment${comment.IDcomment}" aria-expanded="false" aria-controls="collapseAddComment${comment.IDcomment}"><img src="img/comment-icon.png" class="icon-tiny"/></button>
            </div>
        </div>
        <form id="collapseAddComment${comment.IDcomment}" class="collapse col-11 offset-1" onsubmit="postReply(${comment.IDcomment},this); return false;">
            <label for="addComment" hidden>Write Comment</label><input type="text" name="comment" class="mt-1 form-control" placeholder="Reply">
            <button type="submit" class="btn btn-info mt-1 border-dark">Post Reply</button>
            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-toggle="collapse" data-bs-target="#collapseAddComment${comment.IDcomment}" aria-expanded="false" aria-controls="collapseAddComment${comment.IDcomment}">Cancel</button>
        </form>
        <div class="offset-1 collapse col-11" id="comment${comment.IDcomment}Replies">
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
        `<div id="${comment.IDcomment}" class="mt-2 pb-2 border-bottom">
        <div class="d-flex flex-column col-10 col-lg-11">
            <div class="d-flex justify-content-start">
                <img src="data:image/png;base64,${comment.pic}" class="icon-small border rounded">
                <a class="fw-bold my-auto ms-3" href="user_page.php?id=${comment.IDuser}">
                    ${comment.username}
                </a>
            </div>
            <p class="my-2">
                ${comment.text}
            </p>
            <div class="d-flex">
            <button onclick="like(${comment.IDcomment}, ${comment.liked}, this)" class="border-0 bg-white mr-3"><img src="img/like-icon.png" class="like`;
            if (comment.liked == 1) htmlContent += ' liked';
            htmlContent += `"/><span>${comment.likes}</span></button>
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
    let replies = document.getElementById("comment" + idComment + "Replies");
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
    let comm = form["comment"].value.trim();

    if (comm.length == 0) return;
    const formData = new FormData();
    formData.append('q', "postComment");
    formData.append('id', id);
    formData.append('comment', comm);
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
            document.getElementById("addCommentButton").firstChild.innerText = +document.getElementById("addCommentButton").firstChild.innerText + 1;
        }
    });
    //close form
    form.reset();
    document.getElementById("addCommentButton").click();
}
function postReply(idComment, form) {
    let comm = form["comment"].value.trim();

    if (comm.length == 0) return;
    const formData = new FormData();
    formData.append('q', "postReply");
    formData.append('id', id);
    formData.append('idComment', idComment);
    formData.append('comment', comm);
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
            let replies = document.getElementById("comment" + idComment + "Replies");
            printReply(r.data["id"], replies, true);
            if (!replies.classList.contains("show")) btn.click(); //show replies
        }
    });
    form.reset();
    let btn = document.getElementById("replyButton"+idComment);
    btn.style.display = null;
    btn.nextElementSibling.click();
}
function like(idComment, liked, button) {
    const formData = new FormData();
    formData.append('id', idComment);
    if (liked == 0) {
        formData.append('q', "insertLike");
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
                button.childNodes[0].classList.add("liked");
                button.setAttribute("onclick", "like("+idComment+",1,this)");
                button.childNodes[1].textContent = +button.childNodes[1].textContent + 1;
            }
        });
    } else {
        formData.append('q', "deleteLike");
        axios.post('utils/api.php', formData).then(r => {
            button.childNodes[0].classList.remove("liked");
            button.setAttribute("onclick", "like("+idComment+",0,this)");
            button.childNodes[1].innerText = +button.childNodes[1].textContent - 1;
        });
    }
}

//-- on page load
document.getElementById("collapseAddComment").addEventListener("submit", function (event) {
    event.preventDefault();
    postComment(document.getElementById("collapseAddComment"));
});

axios.get("utils/api.php?q=getComments&id=" + id).then(r => {
    r.data.forEach(element => {
        arrayComment.push(element.IDcomment);
    });
    document.getElementById("addCommentButton").firstChild.innerText = arrayComment.length;
    loadComments(5);
});

//--

/* Insert rating functions */
document.addEventListener("load", updateAvgRating());
function insertRating(rating) {
    const formData = new FormData();
    formData.append('q', "insertRating");
    formData.append('idPost', id);
    formData.append('rating', rating);
    axios.post('utils/api.php', formData).then(r => {
        if (r.data["esito"] == true) {
            //aggiorno rating medio
            updateAvgRating();
        }
    });
}
function updateAvgRating() {
    axios.get("utils/api.php?q=getPost&id=" + id).then(r => {
        const rating = r.data.avgRating;
        let element;
        let i;
        for(i=1; i<=rating; i++) {
            element = document.getElementById("star"+i);
            element.checked = true;
        }
        let ratingRounded = Math.round(rating * 100) / 100;
        document.getElementById("avgRating").innerText = ratingRounded;
        /*
        for(i=rating+1; i<=5; i++) {
            element = document.getElementById("star"+i);
            element.checked = false;
        }
        */
    });
}
updateAvgRating();

//-- on page load: recipe button manager
var recipeButton = document.getElementById("recipe-button");
let saved = false;
if(typeof recipeButton!="undefined") var img = recipeButton.firstElementChild;

axios.get('utils/api.php?q=isRecipeSaved&id='+id).then(response => {
    if (response.data.isMyPost != 0) {
        // "Delete" invece di "Save recipe"
    } else {
       saved = response.data.isSaved != 0;
        if (saved) {
            img.classList.add("liked");
            img.setAttribute("src", "img/recipe-icon-saved.png");
            img.setAttribute("alt", "Remove recipe");
        } else {
            img.setAttribute("src", "img/recipe-icon-save.png");
            img.setAttribute("alt", "Save recipe");
        }
        recipeButton.addEventListener("click", event => {
            event.preventDefault();
            if (!saved)
                saveRecipe();
            else
                removeRecipe();
        }); 
    }
    
});

function saveRecipe() {
    const formData = new FormData();
    formData.append('q', "saveRecipe");
    formData.append('id', id);
    axios.post('utils/api.php', formData).then(r => {
        if (r.data["esito"] == true) {
            //aggiorna icona ricetta
            img.classList.add("liked");
            img.setAttribute("src", "img/recipe-icon-saved.png");
            img.setAttribute("alt", "Remove recipe");
            saved = true;
        } else if (r.data["errore"] == "Not Logged") {
            // utente non loggato, redirect al login
            axios.get('template/login_form.php').then(file => {
                document.querySelector("main").innerHTML = file.data;
                var tag = document.createElement("script");
                tag.src = "js/login.js";
                document.querySelector("body").appendChild(tag);
            });
        }
    });
}
function removeRecipe() {
    const formData = new FormData();
    formData.append('q', "deleteRecipe");
    formData.append('idPost', id);
    axios.post('utils/api.php', formData).then(r => {
        if (r.data["esito"] == true) {
            //aggiorna icona ricetta
            img.classList.remove("liked");
            img.setAttribute("src", "img/recipe-icon-save.png");
            img.setAttribute("alt", "Save recipe");
            saved = false;
        }
    });
}

//Eliminazione di un post 
function deletePost(idPost) {
    if (!confirm("Are you sure you want to delete this post?")) return;

    const formData = new FormData();
    formData.append('q', "deletePost");
    formData.append('idPost', idPost);
    axios.post('utils/api.php', formData).then(r => {
        let deleteButton = document.getElementById("deletePostButton");
        if (r.data["esito"] == true) {
            deleteButton.innerText="Deleted";
            location.href="user_page.php";
        }
        else deleteButton.innerText="Error";
    });
}