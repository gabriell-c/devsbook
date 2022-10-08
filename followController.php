<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/UserRelationDAO.php';
    require_once './DAO/UserDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();

    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

    if($id){
        $urDao = new UserRealtionDAOMySQL($pdo);
        $userDao = new UserDAOMySQL($pdo);

        if($userDao->findById($id)){
            $relation = new UserRelation();
            $relation->user_from = $userinfo->id;
            $relation->user_to = $id;

            if($urDao->isFollowing($userinfo->id, $id)){
                
                $urDao->delete($relation);
            }else{
                $urDao->insert($relation);
            }
        }

    };

    header("location: ".$base."/perfil.php?id=".$id);
    exit;


?>