<?php
class DatabaseHelper{
    private $db;

    public function __construct($servername, $username, $password, $dbname, $port){
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }        
    }

    // Query creazione di un post
    public function createPost($title, $pic, $description, $IDuser, $IDrecipe){
        $query = "INSERT INTO POST(title, pic, description, IDuser, IDrecipe) VALUES (?,?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssii',$title, $pic, $description, $IDuser, $IDrecipe);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query creazione di un commento a un post
    public function addCommentOnPost($IDpost, $IDuser, $text){
        $query = "INSERT INTO COMMENT(text, IDpost, IDuser) VALUES (?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sii',$text, $IDpost, $IDuser);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query creazione di una risposta a un commento
    public function addReplyOnComment($IDpost, $IDuser, $text, $IDcomment){
        $query = "INSERT INTO COMMENT(text, IDpost, IDuser, IDcomment) VALUES (?,?,?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('siii',$text, $IDpost, $IDuser, $IDcomment);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query di aggiunta di un follower
    public function addFollower($IDfollower, $IDfollowed){
        $query = "INSERT INTO FOLLOWER(IDfollower, IDfollowed) VALUES (?,?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $IDfollower, $IDfollowed);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Query di attivazione/deattivazione notifiche
    public function enableNotifications($IDuser, $IDfollowed, $value){
        $query = "UPDATE FOLLOWER SET notification=? WHERE IDfollower=? AND IDfollowed=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iii', $value, $IDuser, $IDfollowed);
        return $stmt->execute();
    }

    // Query di login
    public function login($username, $password){
        $query = "SELECT IDuser, username, password, bio, pic FROM USER WHERE username=? AND password=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss',$username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Query di ottenimento dei follower
    public function getFollowers($IDuser){
        $query = "SELECT IDuser, username, pic FROM FOLLOWER, USER WHERE IDfollowed=? AND IDuser=IDfollower";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s',$IDuser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Query di ottenimento statistiche di un utente (post, follower, followed, avg_rating)
    public function getFollowers($IDuser){
        $query = "SELECT post, follower, followed, avg_rating FROM (SELECT COUNT(IDpost) AS post FROM POST WHERE IDuser=?), (SELECT COUNT(IDfollower) AS follower FROM FOLLOWER WHERE IDfollowed=?), (SELECT COUNT(IDfollowed) AS followed FROM FOLLOWER WHERE IDfollower=?), (SELECT AVG(rating) AS avg_rating FROM POST WHERE IDuser=?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssss',$IDuser,$IDuser,$IDuser,$IDuser);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRandomPosts($n){
        $stmt = $this->db->prepare("SELECT idarticolo, titoloarticolo, imgarticolo FROM articolo ORDER BY RAND() LIMIT ?");
        $stmt->bind_param('i',$n);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategories(){
        $stmt = $this->db->prepare("SELECT * FROM categoria");
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById($idcategory){
        $stmt = $this->db->prepare("SELECT nomecategoria FROM categoria WHERE idcategoria=?");
        $stmt->bind_param('i',$idcategory);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPosts($n=-1){
        $query = "SELECT idarticolo, titoloarticolo, imgarticolo, anteprimaarticolo, dataarticolo, nome FROM articolo, autore WHERE autore=idautore ORDER BY dataarticolo DESC";
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

    public function getPostById($id){
        $query = "SELECT idarticolo, titoloarticolo, imgarticolo, testoarticolo, dataarticolo, nome FROM articolo, autore WHERE idarticolo=? AND autore=idautore";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostByCategory($idcategory){
        $query = "SELECT idarticolo, titoloarticolo, imgarticolo, anteprimaarticolo, dataarticolo, nome FROM articolo, autore, articolo_ha_categoria WHERE categoria=? AND autore=idautore AND idarticolo=articolo";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$idcategory);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostByIdAndAuthor($id, $idauthor){
        $query = "SELECT idarticolo, anteprimaarticolo, titoloarticolo, imgarticolo, testoarticolo, dataarticolo, (SELECT GROUP_CONCAT(categoria) FROM articolo_ha_categoria WHERE articolo=idarticolo GROUP BY articolo) as categorie FROM articolo WHERE idarticolo=? AND autore=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii',$id, $idauthor);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostByAuthorId($id){
        $query = "SELECT idarticolo, titoloarticolo, imgarticolo FROM articolo WHERE autore=?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function insertArticle($titoloarticolo, $testoarticolo, $anteprimaarticolo, $dataarticolo, $imgarticolo, $autore){
        $query = "INSERT INTO articolo (titoloarticolo, testoarticolo, anteprimaarticolo, dataarticolo, imgarticolo, autore) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('sssssi',$titoloarticolo, $testoarticolo, $anteprimaarticolo, $dataarticolo, $imgarticolo, $autore);
        $stmt->execute();
        
        return $stmt->insert_id;
    }

    public function updateArticleOfAuthor($idarticolo, $titoloarticolo, $testoarticolo, $anteprimaarticolo, $imgarticolo, $autore){
        $query = "UPDATE articolo SET titoloarticolo = ?, testoarticolo = ?, anteprimaarticolo = ?, imgarticolo = ? WHERE idarticolo = ? AND autore = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssssii',$titoloarticolo, $testoarticolo, $anteprimaarticolo, $imgarticolo, $idarticolo, $autore);
        
        return $stmt->execute();
    }

    public function deleteArticleOfAuthor($idarticolo, $autore){
        $query = "DELETE FROM articolo WHERE idarticolo = ? AND autore = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii',$idarticolo, $autore);
        $stmt->execute();
        var_dump($stmt->error);
        return true;
    }

    public function insertCategoryOfArticle($articolo, $categoria){
        $query = "INSERT INTO articolo_ha_categoria (articolo, categoria) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii',$articolo, $categoria);
        return $stmt->execute();
    }

    public function deleteCategoryOfArticle($articolo, $categoria){
        $query = "DELETE FROM articolo_ha_categoria WHERE articolo = ? AND categoria = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii',$articolo, $categoria);
        return $stmt->execute();
    }

    public function deleteCategoriesOfArticle($articolo){
        $query = "DELETE FROM articolo_ha_categoria WHERE articolo = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i',$articolo);
        return $stmt->execute();
    }

    public function getAuthors(){
        $query = "SELECT username, nome, GROUP_CONCAT(DISTINCT nomecategoria) as argomenti FROM categoria, articolo, autore, articolo_ha_categoria WHERE idarticolo=articolo AND categoria=idcategoria AND autore=idautore AND attivo=1 GROUP BY username, nome";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function checkLogin($username, $password){
        $query = "SELECT idautore, username, nome FROM autore WHERE attivo=1 AND username = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ss',$username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }    

}
?>