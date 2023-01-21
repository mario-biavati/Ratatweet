function seenNotification(id) {
    const formData = new FormData();
    formData.append('q', "seenNotification");
    formData.append('idNotification', id);
    axios.post('utils/api.php', formData).then(r => {
        document.getElementById("notification"+id).remove();
        notificationNumber--;
        updateNotificationNum();
        notifCheck();
    });
    //location.reload();
}
function notifCheck() {
    if (notificationNumber == 0) {
        document.querySelector("main").innerHTML =
        `<div class="container">
            <p class="fs-2 text-muted text-center p-5 m-0">No new notifications</p>
        </div>`;
    }
}
notifCheck();