<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostCommentDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();

    $id = filter_input(INPUT_POST, 'id');
    $txt = filter_input(INPUT_POST, 'txt');

    $array = [];

    if($id && $txt){
        $postCommentDao = new PostCommentDaoMySQL($pdo);

        $newComment = new PostComment();
        $newComment->id_post = $id;
        $newComment->id_user = $userinfo->id;
        $newComment->body = $txt;
        $newComment->created_at = date('Y-m-d H:i:s');

        $postCommentDao->addcomment($newComment);

        $array = [
            'error' => '',
            'link' => $base.'/views/perfil.php?id='.$userinfo->id ,
            'avatar' => $base.'/media/avatars/'.$userinfo->avatar,
            'name' => $userinfo->name,
            'body' => $txt
        ];
    }

    header('Content-Type: application/json');

    echo json_encode($array);
    exit;

?>