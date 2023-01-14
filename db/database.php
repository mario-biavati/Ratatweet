<?php
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }        
    }
    
    //database utility: mostra l'errore in caso di malfunzionamento.
    function prepare($query) {
        $stmt = $this->db->prepare($query);
        if($stmt == false) {
            $error = $this->db->errno . ' ' . $this->db->error;
            echo $error;
        }
        return $stmt;
    }

    //Query inserimento nuovo utente (con ID autoincrement)
    public function insertUser($username, $password, $bio, $pic){
        $stmt = $this->prepare("INSERT INTO USER (username, password, bio, pic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss',$username, $password, $bio, $pic);
        $stmt->execute();
        return $stmt->insert_id;
    }
    // Query creazione di un post
    public function createPost($title, $pic, $description, $IDuser, $IDrecipe){
        $query = "INSERT INTO POST(title, pic, description, IDuser, IDrecipe) VALUES (?,?,?,?,?)";
        $stmt = $this->prepare($query);
        $stmt->bind_param('sssii',$title, $pic, $description, $IDuser, $IDrecipe);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query creazione di un commento a un post
    public function addCommentOnPost($IDpost, $IDuser, $text){
        $query = "INSERT INTO COMMENT(text, IDpost, IDuser) VALUES (?,?,?)";
        $stmt = $this->prepare($query);
        $stmt->bind_param('sii',$text, $IDpost, $IDuser);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query creazione di una risposta a un commento
    public function addReplyOnComment($IDpost, $IDuser, $text, $IDcomment){
        $query = "INSERT INTO COMMENT(text, IDpost, IDuser, IDparent) VALUES (?,?,?,?)";
        $stmt = $this->prepare($query);
        $stmt->bind_param('siii',$text, $IDpost, $IDuser, $IDcomment);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query di aggiunta di un followed
    public function addFollowed($IDfollower, $IDfollowed){
        $query = "INSERT INTO FOLLOWER(IDfollower, IDfollowed) VALUES (?,?)";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDfollower, $IDfollowed);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query di aggiunta di una ricetta
    public function saveRecipe($IDuser, $IDpost){
        $query = "INSERT INTO SAVED_RECIPE(IDuser, IDpost) VALUES (?,?)";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDuser, $IDpost);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query di rimozione di una ricetta
    public function removeRecipe($IDuser, $IDpost){
        $query = "DELETE FROM SAVED_RECIPE WHERE IDuser=? AND IDpost=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDuser, $IDpost);
        return $stmt->execute();
    }

    // Query di rimozione di un followed
    public function removeFollowed($IDfollower, $IDfollowed){
        $query = "DELETE FROM FOLLOWER WHERE IDfollower=? AND IDfollowed=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDfollower, $IDfollowed);
        return $stmt->execute();
    }

    //Query per comprendere se un user segue già o meno un altro user
    public function getFollowerStatus($IDfollower, $IDfollowed){
        $query = "SELECT * FROM FOLLOWER WHERE IDfollower=? AND IDfollowed=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDfollower, $IDfollowed);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function notifyPost($IDnotifier, $IDpost) {
        $query = "INSERT INTO NOTIFICATION(notifier, IDuser, type, IDpost)
        SELECT ? AS notifier, FOLLOWER.IDfollower AS IDuser, 'Post' AS type, ? AS IDpost
        FROM FOLLOWER
        WHERE FOLLOWER.IDfollowed=? AND FOLLOWER.notification = TRUE";
        $stmt = $this->prepare($query);
        $stmt->bind_param('iii', $IDnotifier, $IDpost, $IDnotifier);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function notifyComment($IDnotifier, $IDpost) {
        $query = "INSERT INTO NOTIFICATION(notifier, IDuser, type, IDpost)
        SELECT ? AS notifier, POST.IDuser AS IDuser, 'Comment' AS type, ? AS IDpost
        FROM POST
        WHERE POST.IDPost=? AND POST.IDuser<>?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('iiii', $IDnotifier, $IDpost, $IDpost, $IDnotifier);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function notifyRecipe($IDnotifier, $IDpost) {
        $query = "INSERT INTO NOTIFICATION(notifier, IDuser, type, IDpost)
        SELECT ? AS notifier, RECIPE.IDuser AS IDuser, 'Recipe' AS type, POST.IDpost AS IDpost
        FROM RECIPE, POST
        WHERE RECIPE.IDrecipe=POST.IDrecipe AND POST.IDpost=? AND RECIPE.IDuser<>?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('iii', $IDnotifier, $IDpost, $IDnotifier);
        $stmt->execute();
        return $stmt->insert_id;
    }
    public function notifyFollow($IDnotifier, $IDfollowed) {
        $stmt = $this->prepare("INSERT INTO NOTIFICATION (type, IDuser, notifier, IDpost) VALUES ('Follow', ?, ?, NULL)");
        $stmt->bind_param('ii', $IDfollowed, $IDnotifier);
        $stmt->execute();

        return $stmt->insert_id;
    }
    public function getNotificationNumber($IDuser) {
        $query = "SELECT COUNT(*) AS count FROM NOTIFICATION WHERE IDuser=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$IDuser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Query di login
    public function login($username, $password){
        $query = "SELECT IDuser, username, password, bio, pic FROM USER WHERE username=? AND password=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ss',$username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Query di ottenimento dei follower
    public function getFollowers($IDuser){
        $query = "SELECT IDuser, username, pic FROM FOLLOWER, USER WHERE IDfollowed=? AND IDuser=IDfollower";
        $stmt = $this->prepare($query);
        $stmt->bind_param('s',$IDuser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Query di ottenimento statistiche di un utente (post, follower, followed, avg_rating)
    public function getUserStats($IDuser){
        $query = "SELECT post, follower, followed, avg_rating FROM (SELECT COUNT(IDpost) AS post FROM POST WHERE IDuser=?) AS A, (SELECT COUNT(IDfollower) AS follower FROM FOLLOWER WHERE IDfollowed=?) AS B, (SELECT COUNT(IDfollowed) AS followed FROM FOLLOWER WHERE IDfollower=?) AS C, (SELECT COALESCE(AVG(avgRating),0) AS avg_rating FROM INFOPOST, POST WHERE POST.IDuser=? AND INFOPOST.IDpost=POST.IDPost) AS D";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ssss',$IDuser,$IDuser,$IDuser,$IDuser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //Query inserimento nuova ricetta
    public function insertRecipe($ingredients, $method, $IDuser){
        $stmt = $this->prepare("INSERT INTO RECIPE (ingredients, method, IDuser) VALUES (?, ?, ?)");
        $stmt->bind_param('ssi', $ingredients, $method, $IDuser);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento categorie di una ricetta
    public function insertRecipeCategory($idcategory, $idrecipe){
        $stmt = $this->prepare("INSERT INTO CATEGORY_RECIPE (IDcategory, IDrecipe) VALUES (?, ?)");
        $stmt->bind_param('ii',$idcategory, $idrecipe);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento nuova categoria (con pic)
    public function insertCategory($description, $pic){
        $stmt = $this->prepare("INSERT INTO CATEGORY (description, pic) VALUES (?, ?)");
        $stmt->bind_param('ss',$description, $pic);
        $stmt->execute();

        return $stmt->insert_id;
    }

    //inserimento di like su un commento
    public function insertLike($IDcomment, $IDuser){
        $stmt = $this->prepare("INSERT INTO `LIKES` (IDcomment, IDuser) VALUES (?, ?)");
        $stmt->bind_param('ii',$IDcomment, $IDuser);
        $stmt->execute();

        return $stmt->insert_id;
    }
    // rimozione di like da un commento
    public function deleteLike($IDcomment, $IDuser){
        $query = "DELETE FROM `LIKES` WHERE IDcomment=? AND IDuser=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDcomment, $IDuser);

        return $stmt->execute();
    }

    //Query dichiarazione notifica visualizzata
    public function seenNotification($idNotification){
        $query = "DELETE FROM NOTIFICATION WHERE IDnotification=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i', $idNotification);

        return $stmt->execute();
    }
    public function setNotifications($IDfollower, $IDfollowed, $value){
        $query = "UPDATE FOLLOWER SET notification=? WHERE IDfollower=? AND IDfollowed=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('iii', $value, $IDfollower, $IDfollowed);

        return $stmt->execute();
    }

    //Query ottenimento post di un utente (limit n, se n=-1: no limit)
    public function getUserPosts($idUser, $n=-1){
        $query = "SELECT POST.IDpost FROM POST, INFOPOST WHERE POST.IDpost=INFOPOST.IDpost AND POST.IDuser=? ORDER BY date DESC";
        if($n > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->prepare($query);
        if($n > 0){
            $stmt->bind_param('ii',$idUser, $n);
        }
        else {
            $stmt->bind_param('i',$idUser);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Query che ritorna le info di un post usando il suo ID
    function getPostByID($IDPost) {
        $query = "SELECT POST.IDpost, POST.pic, title, avgRating, numComments, description, POST.date, POST.IDuser, username, IDrecipe FROM POST, INFOPOST, USER
        WHERE POST.IDpost=? AND INFOPOST.IDpost=POST.IDpost AND POST.IDuser=USER.IDuser";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$IDPost);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //Query che ritorna i commenti associati ad un post
    function getCommentsByPostID($IDPost) {
        $query = "SELECT COMMENT.IDcomment FROM COMMENT LEFT JOIN INFOCOMMENT ON COMMENT.IDcomment=INFOCOMMENT.IDcomment WHERE IDpost=? AND IDparent IS NULL ORDER BY numLikes DESC";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$IDPost);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query che ritorna il commento in base all'ID
    function getCommentByID($IDComment, $IDuser = -1) {
        $query = "SELECT COMMENT.IDcomment, COMMENT.text, COMMENT.date, U.IDuser, U.username, U.pic, COALESCE(numLikes, 0) AS likes, B.liked FROM COMMENT NATURAL LEFT JOIN INFOCOMMENT, USER AS U, (SELECT COUNT(*) AS liked FROM `LIKES` WHERE IDcomment=? AND IDuser=?) AS B
        WHERE COMMENT.IDuser=U.IDuser AND COMMENT.IDcomment=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('iii', $IDComment, $IDuser, $IDComment);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query che ritorna le risposte di un commento
    function getRepliesByCommentID($IDcomment) {
        $query = "SELECT IDcomment FROM COMMENT WHERE COMMENT.IDparent=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$IDcomment);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //Query ottenimento post di utenti seguiti da User 
    //-limit "n", se n=-1: no limit
    //-idUser "User", se id=-1: no user -> random
    public function getFollowedRandomPosts($idUser=-1, $n=50, $offset=0){
        if($idUser!=-1) {
            $query = "WITH A AS (SELECT IDpost FROM POST, FOLLOWER
            WHERE FOLLOWER.IDfollower=? AND POST.IDuser=FOLLOWER.IDfollowed
            ORDER BY date DESC)
            SELECT IDpost FROM A
            UNION 
            SELECT IDpost FROM POST WHERE IDpost NOT IN (SELECT IDpost FROM A) ORDER BY RAND()";
        }
        else {
            $query = "SELECT IDpost FROM POST ORDER BY RAND()";
        }
        if($n > 0){
            $query .= " LIMIT ?, ?";
        }
        $stmt = $this->prepare($query);
        if($idUser!=-1 && $n > 0) {
            $stmt->bind_param('iii',$idUser,$offset,$n);
        } else if ($n > 0) {
            $stmt->bind_param('ii',$offset,$n);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento delle notifiche non visualizzate di un utente
    public function getNotifications($idUser){
        $query = "SELECT * FROM NOTIFICATION WHERE IDuser=? ORDER BY date DESC";

        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento di una ricetta
    public function getRecipe($IDrecipe){
        $query = "SELECT * FROM RECIPE WHERE IDrecipe=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$IDrecipe);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento ricette salvate da un utente
    public function getSavedRecipes($idUser){
        $query = "SELECT RECIPE.IDrecipe, RECIPE.ingredients, RECIPE.method, USER.username, POST.IDpost, POST.title FROM SAVED_RECIPE, RECIPE, POST, USER 
                  WHERE SAVED_RECIPE.IDuser=? AND SAVED_RECIPE.IDpost=POST.IDpost
                  AND RECIPE.IDrecipe=POST.IDrecipe AND POST.IDuser=USER.IDuser";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    // Prendi i post dell'utente che usa le ricette da lui create
    public function getUserRecipes($idUser){
        $query = "SELECT RECIPE.IDrecipe, RECIPE.ingredients, RECIPE.method, USER.username, USER.IDuser, POST.IDpost, POST.title FROM RECIPE, POST, USER 
                  WHERE USER.IDuser=? AND RECIPE.IDuser=USER.IDuser
                  AND RECIPE.IDrecipe=POST.IDrecipe AND POST.IDuser=USER.IDuser";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //Query ottenimento di un utente (dal suo ID)
    public function getUserById($idUser){
        $query = "SELECT * FROM USER WHERE IDuser=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento di un utente (dal suo username)
    public function getUserByUsername($username){
        $query = "SELECT * FROM USER WHERE username=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ricerca post, categorie, utenti 
    public function search($string, $n=50){
        $searchWords = explode(" ", $string);
        $categories = array();
        foreach ($searchWords as $word)
        {
            if ($word[0] == "#") {
                array_push($categories, substr($word, 1));
            }
        }

        $query = "SELECT POST.IDpost FROM POST";
        if (count($categories) > 0) {
            $query .= ", CATEGORY, CATEGORY_RECIPE, RECIPE";
        }
        $query .= " WHERE MATCH(title,description) AGAINST(?) > 0";

        if (count($categories) > 0) {
            $query .= " AND CATEGORY_RECIPE.IDrecipe=RECIPE.IDrecipe AND CATEGORY.IDcategory=CATEGORY_RECIPE.IDcategory AND POST.IDrecipe=RECIPE.IDrecipe 
             AND CATEGORY.name IN (".implode(",",$categories).")";
        }

        $query .= " ORDER BY MATCH(title,description) AGAINST(?) DESC LIMIT ?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ssi', $string, $string, $n);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>