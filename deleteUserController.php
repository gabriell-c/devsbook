<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';
    require_once './DAO/UserDAO.php';

    $auth = new Auth($pdo, $base);
    $userDao = new UserDAOMySQL($pdo);
    $userinfo = $auth->chkToken();

    $id = filter_input(INPUT_GET, 'id');
    $psw = filter_input(INPUT_POST, 'psw', FILTER_SANITIZE_SPECIAL_CHARS);

    if(!empty($psw)){
        $hash = password_hash($psw, PASSWORD_DEFAULT);
        $verify = password_verify($psw, $userinfo->password);
        if($verify == false){
            $_SESSION['flash'] = 'Senha incorreta!';
            header('location: '.$base."/configuracoes.php");
            exit;
        }
    }else{
        $_SESSION['flash'] = 'Preencha o campo de senha corretamente!';
        header('location: '.$base."/configuracoes.php");
        exit;
    }

    if($id == $userinfo->id){
        $userDao->deleteUser($userinfo->id);
    };

    header("location: ".$base);
    exit;
?>