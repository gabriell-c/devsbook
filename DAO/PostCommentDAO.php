<?php

    require_once './models/PostComment.php';
    require_once './DAO/UserDAO.php';

    class PostCommentDaoMySQL implements PostCommentDAO{

        private $pdo;

        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }

        public function getComments($id_post){
            $array = [];

            $sql = $this->pdo->prepare("SELECT * FROM postcomments
            WHERE id_post = :id_post");
            $sql->bindValue(":id_post", $id_post);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                $userDao = new UserDAOMySQL($this->pdo);


                foreach($data as $c){
                    $commentItem = new PostComment();
                    $commentItem->id = $c['id'];
                    $commentItem->id_post = $c['id_post'];
                    $commentItem->id_user = $c['id_user'];
                    $commentItem->created_at = $c['created_at'];
                    $commentItem->body = $c['body'];
                    $commentItem->user = $userDao->findById($c['id_user']);

                    $array[] = $commentItem;
                }
            }

            return $array;
        }

        public function addcomment(PostComment $pc){
            $sql = $this->pdo->prepare("INSERT INTO postcomments
            (id_post, id_user, created_at, body) VALUES
            (:id_post, :id_user, :created_at, :body)");
            $sql->bindValue(":id_post", $pc->id_post);
            $sql->bindValue(":id_user", $pc->id_user);
            $sql->bindValue(":created_at", $pc->created_at);
            $sql->bindValue(":body", $pc->body);
            $sql->execute();
        }

        public function deleteFromPost($id){
            $sql = $this->pdo->prepare("DELETE FROM	postcomments WHERE id_post = :id_post");
            $sql->bindValue(":id_post", $id);
            $sql->execute();
        }
    }

?>