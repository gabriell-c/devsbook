<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostLikeDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();

    $id = filter_input(INPUT_GET, 'id');

    if(!empty($id)){
        $postLikeDao = new PostLikeDaoMySQL($pdo);
        $postLikeDao->likeToggle($id, $userinfo->id);
    }

?>