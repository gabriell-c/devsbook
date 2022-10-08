<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();

    $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_SPECIAL_CHARS);

    if($body){
        $postDao = new PostDAOMySQL($pdo);

        $newPost = new Post();
        $newPost->id_user = $userinfo->id;
        $newPost->type = "text";
        $newPost->created_at = date('Y-m-d H:i:s');
        $newPost->body = $body;


        $postDao->insert($newPost);
    };

    header("location: ".$base);
    exit;


?>