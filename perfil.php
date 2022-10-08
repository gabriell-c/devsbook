<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';
    require_once './DAO/UserRelationDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $activeMenu = 'profile';
    $id = filter_input(INPUT_GET, 'id');
    $photo = filter_input(INPUT_GET, 'foto');
    $user = [];
    $feed = [];

    if(!$id){
        $id = $userinfo->id;
    }

    if($id != $userinfo->id){
        $activeMenu = '';
    }
    
    $postDao = new PostDAOMySQL($pdo);
    $userDao = new UserDAOMySQL($pdo);
    $ur = new UserRealtionDAOMySQL($pdo);

    $user = $userDao->findById($id, true);

    if(!$user){
        header('location: '.$base);
        exit;
    }    

    $dateFrom = new DateTime($user->birthdate);
    $dateTo = new DateTime('today');
    $user->ageYears = $dateFrom->diff($dateTo)->y;

    $feed = $postDao->getUserFeed($user->id);

    $isFollowing = $ur->isFollowing($userinfo->id, $id);

    $photosID = $postDao->getPhotosByID($photo);

    require './partials/header.php';
    require './partials/menu.php';
?>

<section class="feed">

    <div class="row">
        <div class="box flex-1 border-top-flat">
            <div class="box-body">
                <div class="profile-cover" style="background-image: url('<?=$base?>/media/covers/<?=$user->cover?>');"></div>
                <div class="profile-info m-20 row">
                    <div class="profile-info-avatar">
                        <img src="<?=$base?>/media/avatars/<?=$user->avatar?>" />
                    </div>
                    <div class="profile-info-name">
                        <div class="profile-info-name-text"><?=$user->name?></div>
                        <?php if(!empty($user->city)) : ?>
                            <div class="profile-info-location">
                                <i class="fa-solid fa-location-dot"></i>
                                <?=$user->city?>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="profile-info-data row">
                        <?php if($id != $userinfo->id) : ?>
                            <div class="profile-info-item m-width-20">
                                <a href="<?=$base?>/followController.php?id=<?=$id?>"><button class="button2"><?= $isFollowing ? 'Deixar de seguir' : 'Seguir+' ?></button></a>
                            </div>
                        <?php endif ?>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->followers)?></div>
                            <div class="profile-info-item-s">Seguidores</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->following)?></div>
                            <div class="profile-info-item-s">Seguindo</div>
                        </div>
                        <div class="profile-info-item m-width-20">
                            <div class="profile-info-item-n"><?=count($user->photos)?></div>
                            <div class="profile-info-item-s">Fotos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row reverse">

        <div class="column side pl-5">
            
            <div class="box">
                <div class="box-body">
                    
                    <div class="user-info-mini">
                        <i class="fa-regular fa-calendar"></i>
                        <?=date('d/m/Y', strtotime($user->birthdate))?> (<?=$user->ageYears?> anos)
                    </div>

                    <?php if(!empty($user->city)) : ?>
                        <div class="user-info-mini">
                            <i class="fa-solid fa-location-dot"></i>
                            <?=$user->city?>
                        </div>
                    <?php endif ?>

                    <?php if(!empty($user->work)) : ?>
                        <div class="user-info-mini">
                            <i class="fa-solid fa-briefcase"></i>
                            <?=$user->work?>
                        </div>
                    <?php endif ?>

                </div>
            </div>

            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Seguindo
                        <span>(<?=count($user->following)?>)</span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?=$base?>/amigos.php?id=<?=$user->id?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body friend-list">

                    <?php if(count($user->following) > 0) :?>
                        <?php foreach($user->following as $foll) : ?>
                            <div class="friend-icon">
                                <a href="<?=$base?>/perfil.php?id=<?=$foll->id?>">
                                    <div class="friend-icon-avatar">
                                        <img src="<?=$base?>/media/avatars/<?=$foll->avatar?>" />
                                    </div>
                                    <div class="friend-icon-name">
                                        <?=$foll->name?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>

                </div>
            </div>

        </div>

        
        <div class="column pl-5">

            <div class="box">
                <div class="box-header m-10">
                    <div class="box-header-text">
                        Fotos
                        <span>(<?=count($user->photos)?>)</span>
                    </div>
                    <div class="box-header-buttons">
                        <a href="<?=$base?>/fotos.php?id=<?=$user->id?>">ver todos</a>
                    </div>
                </div>
                <div class="box-body row m-20">

                <?php if(count($user->photos) > 0) :?> 

                    <?php foreach($user->photos as $key=>$ph) : ?>
                        <?php if($key < 4) : ?>
                            <div class="user-photo-item">
                                <?php if($user->id == $userinfo->id) : ?>
                                        <a href="<?=$base?>/perfil.php?foto=<?=$ph->id?>"><img class="fotoItem" src="<?=$base?>/media/uploads/<?=$ph->body?>" /></a>
                                    <?php else : ?>
                                        <a href="<?=$base?>/perfil.php?id=<?=$user->id?>&foto=<?=$ph->id?>"><img class="fotoItem" src="<?=$base?>/media/uploads/<?=$ph->body?>" /></a>
                                <?php endif ?>
                                <?php if($photo) : ?>
                                    <div class="modalArea" id="modalArea" style="display: flex;">
                                        <div class="BackModalPhoto"> </div>
                                        <div class="FrontModalPhoto">
                                            <?php
                                                if($user->id == $userinfo->id){
                                                    echo "<a href=".$base."/perfil.php><div class='closeModal'>x</div></a>";
                                                }else{
                                                    echo "<a href=".$base."/perfil.php?id=".$user->id."><div class='closeModal'>x</div></a>";
                                                }
                                            ?>
                                            <img id="image" src="<?=$base?>/media/uploads/<?=$photosID['body']?>" >
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>

                <?php endif ?>
                </div>
            </div>

            <?php if($id == $userinfo->id) : ?>
                <?php require './partials/feedEdit.php' ?>
            <?php endif ?>

            <?php if ($feed) : ?>
                <?php foreach($feed as $f) : ?>
                    <?php require './partials/feed-item.php' ?>
                <?php endforeach ?>
            <?php else : ?>
                Não há postagem deste úsuario.
            <?php endif ?>

        </div>
        
    </div>

</section>
<script>
    document.title = 'DevsBook - <?=$user->name;?>';
    window.onload=()=>{
        var modal = new VanillaModal().default();
    }

</script>
<?php
    require './partials/footer.php'
?>
