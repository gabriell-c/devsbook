<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/UserDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();

    $userDao = new UserDAOMySQL($pdo);

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $birthdate = filter_input(INPUT_POST, 'birthdate');
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_SPECIAL_CHARS);
    $work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_SPECIAL_CHARS);
    $psw = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $confirm_psw = filter_input(INPUT_POST, 'password_confirm', FILTER_SANITIZE_SPECIAL_CHARS);

    if($name && $email){

        $userinfo->name = $name;
        $userinfo->city = $city;
        $userinfo->work = $work;

        if($userinfo->email != $email){
            if($userDao->findByEmail($email) === false){
                $userinfo->email = $email;
            }else{
                $_SESSION['flash'] = 'Email já existente!';
                header('location: '.$base."/configuracoes.php");
                exit;
            }
        }

        $birthdate = explode('/', $birthdate);
        if(count($birthdate) != 3){
            $_SESSION['flash'] = 'Data de nascimento inválida!';
            header('location: '.$base.'/configuracoes.php');
            exit;
        }

        $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

        if(strtotime($birthdate) === false){
            $_SESSION['flash'] = 'Data de nascimento inválida!';
            header('location: '.$base.'/configuracoes.php');
        }

        $userinfo->birthdate = $birthdate;

        if(!empty($psw)){
            $hash = password_hash($psw, PASSWORD_DEFAULT);
            if($confirm_psw === $psw){
                $userinfo->password = $hash;
            }else{
                $_SESSION['flash'] = 'Senhas não conferem!';
                header('location: '.$base."/configuracoes.php");
                exit;
            }
        }


        if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['tmp_name'])){

            $newAvatar = $_FILES['avatar'];

            $typesFile = ['image/png', 'image/jpeg', 'image/jpg'];

            if(in_array($newAvatar['type'], $typesFile)){

                $avatarWidth = 200;
                $avatarHeight = 200;

                list($widthOrig, $heightOrig) = getimagesize($newAvatar['tmp_name']);
                
                $ratio = $widthOrig / $heightOrig;

                $newWidth = $avatarWidth;
                $newHeight = $newWidth / $ratio;

                if($newHeight < $avatarHeight){
                    $newHeight = $avatarHeight;
                    $newWidth = $newHeight * $ratio;
                }

                $x = $avatarWidth - $newWidth;
                $y = $avatarHeight - $newHeight;
                $x = $x < 0 ? $x /2 : $x;
                $y = $y < 0 ? $y /2 : $y;

                $finalImage = imagecreatetruecolor($avatarWidth, $avatarHeight);

                switch($newAvatar['type']){
                    case 'image/jpeg':
                    case 'image/jpg';
                        $image = imagecreatefromjpeg($newAvatar['tmp_name']);
                    break;
                        case 'image/png';
                        $image = imagecreatefrompng($newAvatar['tmp_name']);
                    break;
                }

                imagecopyresampled(
                    $finalImage, $image,
                    $x, $y, 0, 0,
                    $newWidth, $newHeight,
                    $widthOrig, $heightOrig
                );

                $avatarName = md5(time().rand(0, 99999)).".jpg";

                if($userinfo->avatar != 'default.jpg'){
                    unlink('./media/avatars/'.$userinfo->avatar);
                }

                imagejpeg($finalImage, './media/avatars/'.$avatarName, 100);

                $userinfo->avatar = $avatarName;
            }
        }



        if(isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'])){

            $newCover = $_FILES['cover'];

            $typesFile = ['image/png', 'image/jpeg', 'image/jpg'];

            if(in_array($newCover['type'], $typesFile)){

                $coverWidth = 850;
                $coverHeight = 313;

                list($widthOrig, $heightOrig) = getimagesize($newCover['tmp_name']);
                
                $ratio = $widthOrig / $heightOrig;

                $newWidth = $coverWidth;
                $newHeight = $newWidth / $ratio;

                if($newHeight < $coverHeight){
                    $newHeight = $coverHeight;
                    $newWidth = $newHeight * $ratio;
                }

                $x = $coverWidth - $newWidth;
                $y = $coverHeight - $newHeight;
                $x = $x < 0 ? $x /2 : $x;
                $y = $y < 0 ? $y /2 : $y;

                $finalImage = imagecreatetruecolor($coverWidth, $coverHeight);

                switch($newCover['type']){
                    case 'image/jpeg':
                    case 'image/jpg';
                        $image = imagecreatefromjpeg($newCover['tmp_name']);
                    break;
                        case 'image/png';
                        $image = imagecreatefrompng($newCover['tmp_name']);
                    break;
                }

                imagecopyresampled(
                    $finalImage, $image,
                    $x, $y, 0, 0,
                    $newWidth, $newHeight,
                    $widthOrig, $heightOrig
                );

                $coverName = md5(time().rand(0, 99999)).".jpg";

                if($userinfo->cover != 'cover.jpg'){
                    unlink('../media/covers/'.$userinfo->cover);
                }

                imagejpeg($finalImage, '../media/covers/'.$coverName, 100);

                $userinfo->cover = $coverName;
            }
        }

        $userDao->update($userinfo);

    }

    header('location: '.$base."/perfil.php");
    exit;

?>