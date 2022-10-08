<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $maxWidth = 1080;
    $maxHeight = 1080;
    $array = [
        'error' => ''
    ];

    $postDao = new PostDAOMySQL($pdo);

    if(isset($_FILES['photo']) && $_FILES['photo']['tmp_name']){

        $photo = $_FILES['photo'];

        $acceptFiles = ['image/png', 'image/jpg', 'image/jpeg'];

        if(in_array($photo['type'], $acceptFiles)){

            list($widthOrig, $heightOrig ) = getimagesize($photo['tmp_name']);
            $ratio = $widthOrig / $heightOrig;

            $newWidth = $maxWidth;
            $newHeight = $maxHeight;
            $ratioMax = $maxWidth / $newHeight;

            if($ratioMax > $ratio){
                $newWidth = $newHeight * $ratio;

            }else{
                $newHeight = $newWidth / $ratio;
            }

            $finalImage = imagecreatetruecolor($newWidth, $newHeight);

            switch($photo['type']){
                case 'image/jpg' :
                case 'image/jpeg' :
                    $image = imagecreatefromjpeg($photo['tmp_name']);
                break;
                case 'image/png':
                    $image = imagecreatefrompng($photo['tmp_name']);
                break;
            }

            imagecopyresampled(
                $finalImage, $image,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $widthOrig, $heightOrig
            );

            $photoName = md5(time().rand(0, 9999)).".jpg";

            imagejpeg($finalImage, './media/uploads/'.$photoName, 100);

            $newPost = new Post();
            $newPost->id_user = $userinfo->id;
            $newPost->type = 'photo';
            $newPost->created_at = date('Y/m/d H:i:s');
            $newPost->body = $photoName;

            $postDao->insert($newPost);

        }else{
            $array['error'] = 'Arquivo não permitido! (jpg ou png)';
        }

    }else{
        $array['error'] = 'Nenhuma imagem enviada!';
    }

    header('Content-Type: application/json');

    echo json_encode($array);
    exit;

?>