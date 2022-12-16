<?php
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }        
    }
    //Query inserimento nuovo utente (con ID autoincrement)
    public function insertUser($username, $password, $bio, $pic){
        $stmt = $this->db->prepare("INSERT INTO USER (username, password, bio, pic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss',$username, $password, $bio, $pic);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento nuovo utente (con ID specificato)
    public function insertUser($id, $username, $password, $bio, $pic){
        $stmt = $this->db->prepare("INSERT INTO USER (IDuser, username, password, bio, pic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('issss',$id, $username, $password, $bio, $pic);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento nuova ricetta
    public function insertRecipe($idPost, $ingredients, $method){
        $stmt = $this->db->prepare("INSERT INTO RECIPE (IDpost, ingredients, method) VALUES (?, ?, ?)");
        $stmt->bind_param('iss',$idPost, $ingredients, $method);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento categorie di una ricetta
    public function insertRecipeCategory($idcategory, $idrecipe){
        $stmt = $this->db->prepare("INSERT INTO CATEGORY_RECIPE (IDcategory, IDrecipe) VALUES (?, ?)");
        $stmt->bind_param('ii',$idcategory, $idrecipe);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento nuova categoria (con pic)
    public function insertCategory($description, $pic){
        $stmt = $this->db->prepare("INSERT INTO CATEGORY (description, pic) VALUES (?, ?)");
        $stmt->bind_param('ss',$description, $pic);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento nuova categoria (senza pic)
    public function insertCategory($description){
        $stmt = $this->db->prepare("INSERT INTO CATEGORY (description) VALUES (?)");
        $stmt->bind_param('i',$description);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento rating ad un post
    public function insertRating($idUser, $idPost, $rating){
        $stmt = $this->db->prepare("INSERT INTO RATING (IDuser, IDpost, rating) VALUES (?, ?, ?)");
        $stmt->bind_param('iii',$idUser, $idPost, $rating);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query inserimento notifica
    public function insertNotification($type, $idUser, $notifier, $idPost){
        $stmt = $this->db->prepare("INSERT INTO RATING (type, IDuser, notifier, IDpost) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('siii',$type, $idUser, $notifier, $idPost);
        $stmt->execute();

        return $stmt->insert_id;
    }
    //Query ottenimento post di un utente (limit n, se n=-1: no limit)
    public function getUserPosts($idUser, $n=-1){
        query = "SELECT IDPost, pic, title, description, date, IDuser, IDrecipe FROM POST ORDER BY date DESC";
        if($n > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        if($n > 0){
            $stmt->bind_param('i',$n);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento post di utenti seguiti da User 
    //-limit "n", se n=-1: no limit
    //-idUser "User", se id=-1: no user -> random
    public function getFollowedRandomPosts($idUser=-1, $n=-1){
        if($idUser!=-1) {
            query = "SELECT IDPost, pic, title, description, date, IDuser, IDrecipe FROM POST, FOLLOWER 
            WHERE FOLLOWER.IDfollower=? AND POST.IDuser=FOLLOWER.IDfollowed
            ORDER BY date DESC";
        }
        else {
            query = "SELECT IDPost, pic, title, description, date, IDuser, IDrecipe FROM POST
            ORDER BY RAND()";
        }
        if($n > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        if($idUser!=-1) {
            $stmt->bind_param('i',$idUser);
        }
        if($n > 0){
            $stmt->bind_param('i',$n);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento notifiche di un utente
    public function getNotifications($idUser){
        query = "SELECT * FROM NOTIFICATION WHERE IDuser=? ORDER BY date DESC";
        if($n > 0){
            $query .= " LIMIT ?";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$idUser);
        if($n > 0){
            $stmt->bind_param('i',$n);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento di una ricetta
    public function getRecipe($idPost){
        query = "SELECT * FROM RECIPE WHERE IDpost=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$idPost);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento di un utente (dal suo ID)
    public function getUser($idUser){
        query = "SELECT * FROM USER WHERE IDuser=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$idUser);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    //Query ottenimento di un utente (dal suo username)
    public function getUser($username){
        query = "SELECT * FROM USER WHERE username=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>