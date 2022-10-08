<?php
    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $id = filter_input(INPUT_GET, 'id');
    if(!$id){
        $id = $userinfo->id;
    }
    $ur = new UserRealtionDAOMySQL($pdo);
    $amigos = $ur->getFollowers($userinfo->id, $id);

?>

<aside class="mt-10">
    <nav>
        <a href="<?=$base?>">
            <div class="menu-item <?=$activeMenu == 'home' ? 'active' : ''?>">
                <div class="menu-item-icon">
                    <i class="fa-solid fa-house"></i>
                </div>
                <div class="menu-item-text">
                    Home
                </div>
            </div>
        </a>
        <a href="<?=$base?>/perfil.php">
            <div class="menu-item <?=$activeMenu == 'profile' ? 'active' : ''?>">
                <div class="menu-item-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="menu-item-text">
                    Meu Perfil
                </div>
            </div>
        </a>
        <a href="<?=$base?>/amigos.php">
            <div class="menu-item <?=$activeMenu == 'friends' ? 'active' : ''?>">
                <div class="menu-item-icon">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div class="menu-item-text">
                    Amigos
                </div>
                <div class="menu-item-badge">
                    <?=count($amigos)?>
                </div>
            </div>
        </a>
        <a href="<?=$base?>/fotos.php">
            <div class="menu-item <?=$activeMenu == 'photos' ? 'active' : ''?>">
                <div class="menu-item-icon">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <div class="menu-item-text">
                    Fotos
                </div>
            </div>
        </a>
        <div class="menu-splitter"></div>
        <a href="<?=$base?>/configuracoes.php">
            <div class="menu-item <?=$activeMenu == 'config' ? 'active' : ''?>">
                <div class="menu-item-icon">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <div class="menu-item-text">
                    Configurações
                </div>
            </div>
        </a>
        <a href="<?=$base?>/logoutController.php">
            <div class="menu-item <?=$activeMenu == 'logout' ? 'active' : ''?>">
                <div class="menu-item-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                <div class="menu-item-text">
                    Sair
                </div>
            </div>
        </a>
    </nav>
</aside>