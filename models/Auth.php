<?php

    require_once './DAO/UserDAO.php';

    class Auth{

        private $pdo;
        private $base;
        private $dao;

        public function __construct(PDO $pdo, $base){
            $this->pdo = $pdo;
            $this->base = $base;
            $this->dao = new UserDAOMySQL($this->pdo);
        }
        
        public function chkToken(){
            if(!empty($_SESSION['token'])){
                $token = $_SESSION['token'];
                
                $this->dao;
                $user = $this->dao->findByToken($token);

                if($user){
                    return $user;
                }
            }

            header('location: '.$this->base."/login.php");
            exit;
        }

        public function validateLogin($email, $psw){
            $this->dao;

            $user = $this->dao->findByEmail($email);

            if($user){
                if(password_verify($psw, $user->password)){
                    $token = md5(time().rand(0, 9999));

                    $_SESSION['token'] = $token;
                    $user->token = $token;
                    $this->dao->update($user);

                    return true;
                }
            }

            return false;
        }

        public function emailExists($email){
            $this->dao;
            return ($this->dao->findByEmail($email)) ? true : false;
        }

        public function registerUser($name, $email, $psw, $birthdate){
            $this->dao;

            $hash = password_hash($psw, PASSWORD_DEFAULT);
            $token = md5(time().rand(0, 9999));

            $newUser = new User();
            $newUser->name = $name;
            $newUser->email = $email;
            $newUser->password = $hash;
            $newUser->birthdate = $birthdate;
            $newUser->token = $token;

            $this->dao->insert($newUser);

            $_SESSION['token'] = $token;
        }

    }

?>