function follow(id) {
    const formData = new FormData();
    formData.append('q', "addFollowed");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
    location.reload();
}
function unfollow(id) {
    const formData = new FormData();
    formData.append('q', "removeFollowed");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
    location.reload();
}
function enableNotifications(id) {
    const formData = new FormData();
    formData.append('q', "enableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
    location.reload();
}
function disableNotifications(id) {
    const formData = new FormData();
    formData.append('q', "disableNotifications");
    formData.append('idFollowed', id);
    axios.post('utils/api.php', formData);
    location.reload();
}