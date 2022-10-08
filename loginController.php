<?php

    require './config.php';
    require './models/Auth.php';

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $psw = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

    if($email && $psw){
        $auth = new Auth($pdo, $base);

        if($auth->validateLogin($email, $psw)){
            header('location: '.$base);
            exit;
        }
    }
    
    $_SESSION['flash'] = 'E-mail e/ou senha incorreta!';
    header('location: '.$base.'/login.php');
    exit;

?>