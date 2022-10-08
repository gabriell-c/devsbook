<?php

    require_once './models/Post.php';
    require_once './DAO/UserRelationDAO.php';
    require_once './DAO/UserDAO.php';
    require_once './DAO/PostLikeDAO.php';
    require_once './DAO/PostCommentDAO.php';

    class PostDAOMySQL implements PostDAO{
        
        private $pdo;

        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }
        
        public function insert(Post $p){
            $sql = $this->pdo->prepare("INSERT INTO posts (
                id_user, type, created_at, body
            ) VALUES (
                :id_user, :type, :created_at, :body
            )");
            $sql->bindValue(":id_user", $p->id_user);
            $sql->bindValue(":type", $p->type);
            $sql->bindValue(":created_at", $p->created_at);
            $sql->bindValue(":body", $p->body);
            $sql->execute();
        }

        public function delete($id, $id_user){

            $likeDao = new PostLikeDaoMySQL($this->pdo);
            $commentDao = new PostCommentDaoMySQL($this->pdo);

            $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id
                AND id_user = :id_user
            ");
            $sql->bindValue(":id", $id);
            $sql->bindValue(":id_user", $id_user);
            $sql->execute();

            if($sql->rowCount() > 0){
                $post = $sql->fetchAll(PDO::FETCH_ASSOC);

                $likeDao->deleteFromPost($id);
                $commentDao->deleteFromPost($id);

                if($post['type'] === 'photo'){
                    $img = '../media/uploads/'.$post['body'];

                    if(file_exists($img)){
                        unlink($img);
                    }
                }

                $sql = $this->pdo->prepare("DELETE FROM posts WHERE id = :id
                    AND id_user = :id_user
                ");
                $sql->bindValue(":id", $id);
                $sql->bindValue(":id_user", $id_user);
                $sql->execute();
            }
        }

        public function getUserFeed($id_user){
            $array = [];
            $userDao = new UserDAOMySQL($this->pdo);

            $sql = $this->pdo->prepare("SELECT * FROM posts
            WHERE id_user = :id_user ORDER BY created_at DESC");
            $sql->bindValue(":id_user", $userDao->findById($id_user)->id);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);

                $array = $this->_postListToObject($data, $id_user);
            }

            return $array;

        }

        public function getHomeFeed($id_user, $page = 1){
            $array['feed'] = [];
            $pPage = 20;

            $offSet = ($page - 1) * $pPage;

            $urDao = new UserRealtionDAOMySQL($this->pdo);
            $userList = $urDao->getFollowing($id_user);
            $userList[] = $id_user;

            $sql = $this->pdo->query("SELECT * FROM posts
                WHERE id_user IN (".implode(',', $userList).") ORDER BY created_at DESC LIMIT $offSet,$pPage");

            if($sql){
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                $array['feed'] = $this->_postListToObject($data, $id_user);
            }

            $sql = $this->pdo->query("SELECT COUNT(*) as c FROM posts
            WHERE id_user IN (".implode(',', $userList).") ");

            $totalData =$sql->fetch();
            $total = $totalData['c'];

            $array['pages'] = ceil( $total / $pPage);

            $array['currentPage'] = $page;

            return $array;
        }

        private function _postListToObject($post_list, $id_user){
            $posts = [];
            $likeDao = new PostLikeDaoMySQL($this->pdo);
            $userDao = new UserDAOMySQL($this->pdo);
            $commentDao = new PostCommentDaoMySQL($this->pdo);


            foreach($post_list as $post_item){
                $newPost = new Post();
                $newPost->id = $post_item['id'];
                $newPost->type = $post_item['type'];
                $newPost->created_at = $post_item['created_at'];
                $newPost->body = $post_item['body'];
                $newPost->mine = false;
                $newPost->id_user = $id_user;

                $newPost->user = $userDao->findById($post_item['id_user']);

                $newPost->likecount = $likeDao->getLikeCount($newPost->id);
                $newPost->liked = $likeDao->isLiked($newPost->id, $id_user);

                $newPost->comments = $commentDao->getComments($newPost->id);

                $posts[] = $newPost;
            }

            return $posts;
        }

        public function getPhotosFrom($id_user){
            $array = [];

            $sql = $this->pdo->prepare("SELECT * FROM posts
                WHERE id_user = :id_user AND type = 'photo'
                ORDER BY created_at DESC
            ");
            $sql->bindValue(":id_user", $id_user);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);
                $array = $this->_postListToObject($data, $id_user);
            }

            return $array;
        }

        public function getPhotosByID($id){
            if(!empty($id)){
                $sql = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id");
                $sql->bindValue(":id", $id);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $data = $sql->fetch(PDO::FETCH_ASSOC);
                    return $data;
                }
            }

            return false;
        }
    }
?>