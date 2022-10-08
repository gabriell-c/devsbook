<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();

    $id = filter_input(INPUT_GET, 'id');

    if($id){
        $postDao = new PostDAOMySQL($pdo);

        $postDao->delete($id, $userinfo->id);
    };

    header("location: ".$base);
    exit;
?>