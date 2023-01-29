let timer = 30000; // periodo in ms di refresh delle notifiche
let notifNum = document.querySelectorAll("span.notification");
let notificationNumber = 0;
notifNum.forEach(num => {
    num.setAttribute("hidden", "hidden");
});
function check() {
    return new Promise (resolve => {
        axios.get('utils/api.php?q=getNotificationNumber').then(response => {
            notificationNumber = response.data['count'];
            updateNotificationNum();
            resolve(notificationNumber);
        });
    });
}
function updateNotificationNum() {
    notifNum.forEach(num => {
        if (notificationNumber == 0)
            num.setAttribute("hidden", "hidden");
        else
            num.removeAttribute("hidden");

        num.firstChild.textContent = (notificationNumber > 99) ? '99+' : notificationNumber;
    });
}
setInterval(() => check(), timer);

check();