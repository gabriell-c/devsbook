<?php

    require_once './config.php';
    require_once './models/User.php';
    require_once './DAO/UserRelationDAO.php';
    require_once './DAO/PostDAO.php';

    class UserDAOMySQL implements UserDAO{
        private $pdo;

        private function generateUser($array, $full = false){
            $u = new User;
            $u->id = $array['id'] ?? 0;
            $u->password = $array['password'] ?? '';
            $u->email = $array['email'] ?? '';
            $u->name = $array['name'] ?? '';
            $u->birthdate = $array['birthdate'] ?? '';
            $u->city = $array['city'] ?? '';
            $u->work = $array['work'] ?? '';
            $u->avatar = $array['avatar'] ?? '';
            $u->cover = $array['cover'] ?? '';
            $u->token = $array['token'] ?? '';

            if($full){
                $urDaoMySQL = new UserRealtionDAOMySQL($this->pdo);
                $postDaoMySQL = new PostDAOMySQL($this->pdo);

                $u->followers = $urDaoMySQL->getFollowers($u->id);

                foreach($u->followers as $key => $follower_id){
                    $newUser = $this->findById($follower_id);
                    $u->followers[$key] = $newUser;
                }

                $u->following = $urDaoMySQL->getFollowing($u->id);

                foreach($u->following as $key => $follower_id){
                    $newUser = $this->findById($follower_id);
                    $u->following[$key] = $newUser;
                }

                $u->photos = $postDaoMySQL->getPhotosFrom($u->id);
            }

            return $u;
        }

        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }

        public function findByToken($token){
            if(!empty($token)){
                $sql = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
                $sql->bindValue(":token", $token);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $data = $sql->fetch(PDO::FETCH_ASSOC);

                    $user = $this->generateUser($data);

                    return $user;
                }
            }

            return false;
        }

        public function findByEmail($email){
            if(!empty($email)){
                $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
                $sql->bindValue(":email", $email);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $data = $sql->fetch(PDO::FETCH_ASSOC);
                    $user = $this->generateUser($data);
                    return $user;
                }
            }

            return false;
        }

        public function update(User $u){
            $sql = $this->pdo->prepare("UPDATE users SET 
                email = :email,
                password = :psw,
                name = :name,
                birthdate = :birthdate,
                city = :city,
                work = :work,
                avatar = :avatar,
                cover = :cover,
                token = :token
            WHERE id = :id" );

            $sql->bindValue(":email", $u->email);
            $sql->bindValue(":psw", $u->password);
            $sql->bindValue(":name", $u->name);
            $sql->bindValue(":birthdate", $u->birthdate);
            $sql->bindValue(":city", $u->city);
            $sql->bindValue(":work", $u->work);
            $sql->bindValue(":avatar", $u->avatar);
            $sql->bindValue(":cover", $u->cover);
            $sql->bindValue(":token", $u->token);
            $sql->bindValue(":id", $u->id);
            $sql->execute();

            return true;
        }

        public function insert(User $u){
            $sql = $this->pdo->prepare("INSERT INTO users (
                email, password, name, birthdate, token
            ) VALUE (
                :email, :password, :name, :birthdate, :token
            )");
            $sql->bindValue(":email", $u->email);
            $sql->bindValue(":password", $u->password);
            $sql->bindValue(":name", $u->name);
            $sql->bindValue(":birthdate", $u->birthdate);
            $sql->bindValue(":token", $u->token);
            $sql->execute();

            return true;
        }

        public function findById($id, $full = false){
            if(!empty($id)){
                $sql = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
                $sql->bindValue(":id", $id);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $data = $sql->fetch(PDO::FETCH_ASSOC);
                    $user = $this->generateUser($data, $full);
                    return $user;
                }
            }

            return false;
        }

        public function findByName($name, $page = 1){

            $array = [];
            $pPage = 70;

            
            $offSet = ($page - 1) * $pPage;

            if(!empty($name)){
                $sql = $this->pdo->prepare("SELECT * FROM users WHERE name LIKE :name ORDER BY name DESC LIMIT $offSet,$pPage");
                $sql->bindValue(":name", "%".$name."%");
                $sql->execute();

                if($sql->rowCount() > 0){
                    $data = $sql->fetchAll(PDO::FETCH_ASSOC);

                    $array['list'] = $data;
                }
            }

            $sql = $this->pdo->prepare("SELECT COUNT(*) as c FROM users
            WHERE name LIKE :name");
            $sql->bindValue(":name", "%".$name."%");
            $sql->execute();

            $totalData = $sql->fetch();
            $total = $totalData['c'];

            $array['pages'] = ceil( $total / $pPage);
            $array['currentPage'] = $page;

            return $array;
        }

        public function deleteUser($id_user){

            $sqlLike = $this->pdo->prepare("DELETE FROM postlikes WHERE id_user = :id_user");
            $sqlLike->bindValue(":id_user", $id_user);
            $sqlLike->execute();

            $sqlComment = $this->pdo->prepare("DELETE FROM postcomments WHERE id_user = :id_user");
            $sqlComment->bindValue(":id_user", $id_user);
            $sqlComment->execute();

            $sqlPostPhoto = $this->pdo->prepare("SELECT * FROM posts WHERE id_user = :id_user");
            $sqlPostPhoto->bindValue(":id_user", $id_user);
            $sqlPostPhoto->execute();

            if($sqlPostPhoto->rowCount() > 0){
                $post = $sqlPostPhoto->fetchAll(PDO::FETCH_ASSOC);
                
                for($i = 0; $i < count($post); $i++){
                    if($post[$i]['type'] === 'photo'){
                        $img = '../media/uploads/'.$post[$i]['body'];

                        if($img){
                            echo $img."<br>";
                            unlink($img);
                        }
                    }
                }
            }

            $sqlPost = $this->pdo->prepare("DELETE FROM posts WHERE id_user = :id_user");
            $sqlPost->bindValue(":id_user", $id_user);
            $sqlPost->execute();

            $sqlPost = $this->pdo->prepare("DELETE FROM userrelations WHERE user_to = :id_user");
            $sqlPost->bindValue(":id_user", $id_user);
            $sqlPost->execute();

            $sqlPost = $this->pdo->prepare("DELETE FROM userrelations WHERE user_from = :id_user");
            $sqlPost->bindValue(":id_user", $id_user);
            $sqlPost->execute();

            $sqlUser = $this->pdo->prepare("DELETE FROM users WHERE id = :id_user");
            $sqlUser->bindValue(":id_user", $id_user);
            $sqlUser->execute();
        }
    }

?>