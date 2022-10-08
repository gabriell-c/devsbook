<?php
    $firstName = explode(' ', $userinfo->name)[0];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>DevsBook</title>
    <link rel="icon" href="<?=$base?>/assets/images/logo icon.png" />
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
    <link rel="stylesheet" href="<?=$base?>/assets/css/style.css" />
    <script src="https://kit.fontawesome.com/b5afd26a30.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" />
</head>
<body>
    <header>
        <div class="nav">
            <div class="logo">
                <a href="<?=$base?>"><img src="<?=$base?>/assets/images/devsbook_logo.png" /></a>
            </div>
            <div class="head-side">
                <div class="head-side-left">
                    <div class="search-area">
                        <form method="GET" action="<?=$base?>/search.php">
                            <input type="search" placeholder="Pesquisar" name="s" />
                        </form>
                    </div>
                </div>
                <div class="head-side-right">
                    <a href="<?=$base?>/perfil.php" class="user-area">
                        <div class="user-area-text"><?=$firstName?></div>
                        <div class="user-area-icon">
                            <img src="<?=$base?>/media/avatars/<?=$userinfo->avatar?>" />
                        </div>
                    </a>
                    <a href="<?=$base?>/logoutController.php" class="user-logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <section class="container main">