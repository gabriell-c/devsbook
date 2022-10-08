<?php

    require './config.php';
    
    $_SESSION['token'] = '';
    header('location: '.$base);
    exit;

?>