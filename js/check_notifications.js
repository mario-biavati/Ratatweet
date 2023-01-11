let timer = 30000; // periodo in ms di refresh delle notifiche
let notifNum = document.querySelectorAll("span.notification");
notifNum.forEach(num => {
    num.setAttribute("hidden", "hidden");
});
function check() {
    axios.get('utils/api.php?q=getNotificationNumber').then(response => {
        let n = response.data['count'];
        notifNum.forEach(num => {
            if (n == 0)
                num.setAttribute("hidden", "hidden");
            else
                num.removeAttribute("hidden");

            num.firstChild.textContent = (n > 99) ? '99+' : n;
        });
    });
}

setInterval(() => check(), timer);

check();