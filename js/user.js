function followunfollow(id) {
    const button = document.querySelector("#FollowUnfollow-Button");
    const followState = button.className;
    if(followState=="Follow") {
        follow(id);
        button.value="Unfollow";
        button.alt="Unfollow user";
        button.className="Unfollow";
        document.querySelector("#NumFollowers").value++;
    }
    else {
        unfollow(id);
        button.value="Follow";
        button.alt="Follow user";
        button.className="Follow";
        document.querySelector("#NumFollowers").value--;
    }
}
function follow(id) {
    const formData = new FormData();
    formData.append('q', "addFollowed");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
}
function unfollow(id) {
    const formData = new FormData();
    formData.append('q', "removeFollowed");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
}
function modifyNotifications(id) {
    const button = document.querySelector("#Notifications-Button");
    const notState = button.className;
    if(notState=="Enable") {
        enableNotifications(id);
        button.value="Disable";
        button.alt="Disable notifications";
        button.className="Disable";
    }
    else {
        disableNotifications(id);
        button.value="Enable";
        button.alt="Enable notifications";
        button.className="Enable";
    }
}
function enableNotifications(id) {
    const formData = new FormData();
    formData.append('q', "enableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
}
function disableNotifications(id) {
    const formData = new FormData();
    formData.append('q', "disableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
}