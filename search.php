<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/UserDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $activeMenu = 'search';

    $searchValue = filter_input(INPUT_GET, 's', FILTER_SANITIZE_SPECIAL_CHARS);
    $userDao = new UserDAOMySQL($pdo);

    if(empty($searchValue)){
        header('location: '.$base);
        exit;
    }

    $page = intval(filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT));
    if($page < 1){
        $page = 1;
    }

    $info = $userDao->findByName($searchValue, $page);
    $list = $info['list'];
    $pages = $info['pages'];
    $currentPage = $info['currentPage'];

    require_once './partials/header.php';
    require_once './partials/menu.php';
    
?>

<section class="feed mt-10">
    <div class="row">
        <div class="column pr-5">
            
            <h2>Pesquisa por: <?=$searchValue?></h2>

            <div class="full-friend-list" >
                <?php for($q = 0; $q < count($list); $q++) : ?>
                    <div class="friend-icon">
                        <a href="<?=$base?>/perfil.php?id=<?=$list[$q]['id']?>">
                            <div class="friend-icon-avatar">
                                <img src="<?=$base?>/media/avatars/<?=$list[$q]['avatar']?>" />
                            </div>
                            <div class="friend-icon-name">
                                <?=$list[$q]['name']?>
                            </div>
                        </a>
                    </div>
                <?php endfor ?>
            </div>

            <div class="feed-pagination" >
                <?php for($q = 0; $q<$pages; $q++) :?>
                    <a href="<?=$base?>/search.php?s=<?=$searchValue?>&page=<?=$q+1?>"><button class="button-pagination <?=$q+1 == $currentPage ? 'active2' : ''?>" ><?=$q+1?></button></a>
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
                <div class="box-body">
                    <a href=""><img src="https://alunos.b7web.com.br/media/courses/php-nivel-1.jpg" /></a>
                    <a href=""><img src="https://alunos.b7web.com.br/media/courses/laravel-nivel-1.jpg" /></a>
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
