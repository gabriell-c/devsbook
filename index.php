<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $activeMenu = 'home';

    $page = intval(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
    if($page < 1){
        $page = 1;
    }

    $postDao = new PostDAOMySQL($pdo);
    $info = $postDao->getHomeFeed($userinfo->id, $page);
    $feed = $info['feed'];
    $pages = $info['pages'];
    $currentPage = $info['currentPage'];

    require_once './partials/header.php';
    require_once './partials/menu.php';
    
?>

<section class="feed mt-10">
    <div class="row">
        <div class="column pr-5">
            <?php require './partials/feedEdit.php'?>

            <?php if($feed) : ?>
                <?php foreach($feed as $f) : ?>
                    <?php require './partials/feed-item.php' ?>
                <?php endforeach ?>
            <?php endif ?>

            <div class="feed-pagination" >
                <?php for($q = 0; $q<$pages; $q++) :?>
                    <a href="<?=$base?>/?page=<?=$q+1?>"><button class="button-pagination <?=$q+1 == $currentPage ? 'active2' : ''?>" ><?=$q+1?></button></a>
                <?php endfor ?>
            </div>

        </div>
        <div class="column side pl-5">
        <div class="box banners">
            <div class="box-header">
                    <div class="box-header-text">Patrocinios</div>
                    <div class="box-header-buttons">
                        
                    </div>
                </div>
                <div class="anuncios">
                    <a href=""><img src="https://66764.cdn.simplo7.net/static/66764/sku/166015806287699.jpeg" /></a>
                    <a href=""><img src="https://c4.wallpaperflare.com/wallpaper/738/677/156/brasil-brazil-flag-wallpaper-preview.jpg" /></a>
                </div>
            </div>
            <div class="box">
                <div class="box-body m-10">
                    Criado com ❤️ por B7Web
                </div>
            </div>
        </div>
    </div>
</section>

<?php
    require './partials/footer.php'
?>
