<?php

    require_once './models/PostLike.php';
    require_once './DAO/UserRelationDAO.php';
    require_once './DAO/UserDAO.php';

    class PostLikeDaoMySQL implements PostLikeDAO{

        private $pdo;

        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }

        public function getLikeCount($id_post){
            $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM postlikes
                WHERE id_post = :id_post;
            ");
            $sql->bindValue(":id_post", $id_post);
            $sql->execute();

            $data = $sql->fetch(PDO::FETCH_ASSOC);

            return $data['c'];
        }

        public function isLiked($id_post, $id_user){
            $sql = $this->pdo->prepare("SELECT * FROM postlikes
                WHERE id_post = :id_post AND id_user = :id_user
            ");
            $sql->bindValue(":id_post", $id_post);
            $sql->bindValue(":id_user", $id_user);
            $sql->execute();

            return $sql->rowCount() ? true : false;
        }

        public function likeToggle($id_post, $id_user){
            if($this->isLiked($id_post, $id_user)){
                $sql = $this->pdo->prepare("DELETE FROM postlikes
                    WHERE id_post = :id_post AND id_user = :id_user");
            }else{
                $sql = $this->pdo->prepare("INSERT INTO postlikes
                (id_post, id_user, created_at) VALUES
                (:id_post, :id_user, NOW())");
            }

            $sql->bindValue(":id_post", $id_post);
            $sql->bindValue(":id_user", $id_user);
            $sql->execute();
        }

        public function deleteFromPost($id){
            $sql = $this->pdo->prepare("DELETE FROM	postlikes WHERE id_post = :id_post");
            $sql->bindValue(":id_post", $id);
            $sql->execute();
        }
    
    }

?>