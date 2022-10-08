<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';
    require_once './DAO/UserRelationDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $activeMenu = 'friends';
    $id = filter_input(INPUT_GET, 'id');
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

    $isFollowing = $ur->isFollowing($userinfo->id, $id);


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
                            <div class="profile-info-location"><?=$user->city?></div>
                        <?php endif ?>
                    </div>
                    <div class="profile-info-data row">
                        <?php if($id != $userinfo->id) : ?>
                            <div class="profile-info-item m-width-20">
                                <a href="<?=$base?>/follow_action.php?id=<?=$id?>"><button class="button2"><?= $isFollowing ? 'Deixar de seguir' : 'Seguir+' ?></button></a>
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
    <div class="row">

        <div class="column">
            
            <div class="box">
                <div class="box-body">

                    <div class="tabs">
                        <div class="tab-item" data-for="followers">
                            Seguidores
                        </div>
                        <div class="tab-item active" data-for="following">
                            Seguindo
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-body" data-item="followers">

                            <?php if(count($user->followers) > 0) : ?>
                                <div class="full-friend-list">
                                    <?php foreach($user->followers as $f) : ?>
                                        
                                        <div class="friend-icon">
                                            <a href="<?=$base?>/perfil.php?id=<?=$f->id?>">
                                                <div class="friend-icon-avatar">
                                                    <img src="<?=$base?>/media/avatars/<?=$f->avatar?>" />
                                                </div>
                                                <div class="friend-icon-name">
                                                    <?=$f->name?>
                                                </div>
                                            </a>
                                        </div>        
                                        
                                    <?php endforeach ?>
                                </div>
                            <?php endif ?>

                        </div>
                        <div class="tab-body" data-item="following">
                            
                                <?php if(count($user->following) > 0) : ?>
                                    <div class="full-friend-list">  
                                    <?php foreach($user->following as $f) : ?>
                                            
                                        <div class="friend-icon">
                                            <a href="<?=$base?>/perfil.php?id=<?=$f->id?>">
                                                <div class="friend-icon-avatar">
                                                    <img src="<?=$base?>/media/avatars/<?=$f->avatar?>" />
                                                </div>
                                                <div class="friend-icon-name">
                                                    <?=$f->name?>
                                                </div>
                                            </a>
                                        </div>
                                        
                                    <?php endforeach ?>
                                    </div>
                                <?php endif ?>
                           

                        </div>
                    </div>

                </div>
            </div>

        </div>
        
    </div>

</section>
<?php
    require './partials/footer.php'
?>
<script>
    document.title = 'DevsBook - amigos';
</script>