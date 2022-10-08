<?php

    require './config.php';
    require './models/Auth.php';

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $psw = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $birthdate = filter_input(INPUT_POST, 'birthdate');

    if($name && $email && $psw && $birthdate){

        $auth = new Auth($pdo, $base);

        $birthdate = explode('/', $birthdate);
        if(count($birthdate) != 3){
            $_SESSION['flash'] = 'Data de nascimento inválida!';
            header('location: '.$base.'/signup.php');
            exit;
        }

        $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];

        if(strtotime($birthdate) === false){
            $_SESSION['flash'] = 'Data de nascimento inválida!';
            header('location: '.$base.'/signup.php');
            exit;
        }

        if($auth->emailExists($email) === false){
            $auth->registerUser($name, $email, $psw, $birthdate);
            header('location: '.$base);
            exit;
        }else{
            $_SESSION['flash'] = 'Úsuario já existente!';
            header('location: '.$base.'/signup.php');
            exit;
        }
    }
    
    $_SESSION['flash'] = 'Preencha todos os campos corretamente!';
    header('location: '.$base.'/signup.php');
    exit;
?>