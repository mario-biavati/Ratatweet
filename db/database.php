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
    public function saveRecipe($IDuser, $IDrecipe){
        $query = "INSERT INTO SAVED_RECIPE(IDuser, IDrecipe) VALUES (?,?)";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDuser, $IDrecipe);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query di rimozione di una ricetta
    public function removeRecipe($IDuser, $IDrecipe){
        $query = "DELETE FROM SAVED_RECIPE WHERE IDUser=? AND IDrecipe=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDuser, $IDrecipe);
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

    // Query di attivazione/deattivazione notifiche
    public function enableNotifications($IDuser, $IDfollowed, $value){
        $query = "UPDATE FOLLOWER SET notification=? WHERE IDfollower=? AND IDfollowed=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('iii', $value, $IDuser, $IDfollowed);
        return $stmt->execute();
    }

    //Query per comprendere se le notifiche per un determinato user sono attive/disattive
    public function getNotificationStatus($IDuser, $IDfollowed){
        $query = "SELECT notification FROM FOLLOWER WHERE IDfollower=? AND IDfollowed=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii', $IDuser, $IDfollowed);
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
    public function insertRecipe($idPost, $ingredients, $method){
        $stmt = $this->prepare("INSERT INTO RECIPE (IDpost, ingredients, method) VALUES (?, ?, ?)");
        $stmt->bind_param('iss',$idPost, $ingredients, $method);
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

    // //Query inserimento rating ad un post
    // public function insertRating($idUser, $idPost, $rating){
    //     $stmt = $this->prepare("INSERT INTO RATING (IDuser, IDpost, rating) VALUES (?, ?, ?)");
    //     $stmt->bind_param('iii',$idUser, $idPost, $rating);
    //     $stmt->execute();

    //     return $stmt->insert_id;
    // }
    //Query inserimento notifica
    public function insertNotification($type, $idUser, $notifier, $idPost){
        $stmt = $this->prepare("INSERT INTO RATING (type, IDuser, notifier, IDpost) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('siii',$type, $idUser, $notifier, $idPost);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query dichiarazione notifica visualizzata
    public function seenNotification($idNotification){
        $query = "DELETE FROM NOTIFICATION WHERE IDnotification=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i', $idNotification);

        return $stmt->execute();
    }
    //Query ottenimento post di un utente (limit n, se n=-1: no limit)
    public function getUserPosts($idUser, $n=-1){
        $query = "SELECT POST.IDpost, pic, title, description, date, IDuser, IDrecipe, avgRating FROM POST, INFOPOST WHERE POST.IDPost=INFOPOST.IDpost AND POST.IDuser=? ORDER BY date DESC";
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
        $query = "SELECT IDcomment FROM COMMENT WHERE IDpost=? AND IDparent IS NULL";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$IDPost);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query che ritorna il commento in base all'ID
    function getCommentByID($IDComment) {
        $query = "SELECT C.IDcomment, C.text, C.date, U.IDuser, U.username, U.pic, A.likes FROM COMMENT AS C, USER AS U, (SELECT COUNT(IDuser) AS likes FROM `LIKE` WHERE IDcomment=?) AS A
        WHERE C.IDuser=U.IDuser AND C.IDcomment=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('ii',$IDComment, $IDComment);
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
            $query = "SELECT IDpost FROM POST, FOLLOWER
            WHERE FOLLOWER.IDfollower=? AND POST.IDuser=FOLLOWER.IDfollowed
            ORDER BY date DESC";
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
    public function getRecipe($idPost){
        $query = "SELECT * FROM RECIPE WHERE IDpost=?";
        $stmt = $this->prepare($query);
        $stmt->bind_param('i',$idPost);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento ricette salvate da un utente
    public function getSavedRecipes($idUser){
        $query = "SELECT RECIPE.ingredients, RECIPE.method, USER.username, POST.IDpost, POST.title FROM SAVED_RECIPE, RECIPE, POST, USER 
                  WHERE SAVED_RECIPE.IDuser=? AND SAVED_RECIPE.IDrecipe=RECIPE.IDpost 
                  AND RECIPE.IDpost=POST.IDPost AND POST.IDuser=USER.IDuser";
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
            $query .= " AND CATEGORY_RECIPE.IDrecipe=RECIPE.IDpost AND CATEGORY.IDcategory=CATEGORY_RECIPE.IDcategory AND POST.IDrecipe=RECIPE.IDpost 
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