<?php

    require_once './models/UserRelations.php';
    

    class UserRealtionDAOMySQL implements UserRelationDAO{
        
        private $pdo;

        public function __construct(PDO $driver){
            $this->pdo = $driver;
        }
        
        public function insert(UserRelation $ur){
            $sql = $this->pdo->prepare("INSERT INTO userrelations
                (user_from, user_to) VALUES
                (:user_from, :user_to)
            ");
            $sql->bindValue(":user_from", $ur->user_from);
            $sql->bindValue(":user_to", $ur->user_to);
            $sql->execute();
        }

        public function delete(UserRelation $ur){
            $sql = $this->pdo->prepare("DELETE FROM userrelations
                WHERE user_from = :user_from AND user_to = :user_to 
            ");
            $sql->bindValue(":user_from", $ur->user_from);
            $sql->bindValue(":user_to", $ur->user_to);
            $sql->execute();
        }

        public function getFollowing($id_user){
            $user = [];

            $sql = $this->pdo->prepare("SELECT user_to FROM userrelations WHERE user_from = :user_from ");
            $sql->bindValue(":user_from", $id_user);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($data as $d){
                    $user[] = $d['user_to'];
                }
            }

            return $user;
        }

        public function getFollowers($id_user){

            $user = [];

            $sql = $this->pdo->prepare("SELECT user_from FROM userrelations WHERE user_to = :user_to ");
            $sql->bindValue(":user_to", $id_user);
            $sql->execute();

            if($sql->rowCount() > 0){
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($data as $d){
                    $user[] = $d['user_from'];
                }
            }

            return $user;
        }

        public function isFollowing($id1, $id2){
            $sql = $this->pdo->prepare("SELECT * FROM userrelations
                WHERE user_from = :user_from AND user_to = :user_to
            ");
            $sql->bindValue(":user_from", $id1);
            $sql->bindValue(":user_to", $id2);
            $sql->execute();

            return $sql->rowCount() > 0 ? true : false;
        }
    }

?>