<?php

    session_start();

    $base = 'http://localhost/devsbook';

    date_default_timezone_set('America/Sao_Paulo');

    $pdo = new PDO("mysql:dbname=devsbook;host=localhost", "root");

?>