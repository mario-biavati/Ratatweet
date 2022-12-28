function seenNotification(id) {
    const formData = new FormData();
    formData.append('q', "seenNotification");
    formData.append('idNotification', id);
    axios.post('utils/api.php', formData);
    location.reload();
}