fetch('http://localhost/Ratatweet/utils/get_user_info.php')
.then(function (r) {
    const userInfo = JSON.parse(r);
    console.log("Username: " + userInfo.username);
});