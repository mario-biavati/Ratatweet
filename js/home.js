axios.get('utils/get_user_info.php').then(response => {
    console.log(response.data["IDuser"]);
});