<?php

require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/PostDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $activeMenu = 'photos';
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

    $user = $userDao->findById($id, true);

    if(!$user){
        header('location: '.$base);
        exit;
    }    

    $dateFrom = new DateTime($user->birthdate);
    $dateTo = new DateTime('today');
    $user->ageYears = $dateFrom->diff($dateTo)->y;
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
                            <div class="profile-info-location"><?=$user->city?></div>
                        <?php endif ?>
                    </div>
                    <div class="profile-info-data row">
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
            <div class="box-body row m-20">

                <?php if(count($user->photos) > 0) :?> 

                    <?php foreach($user->photos as $key=>$ph) : ?>
                        <?php if($key < 4) : ?>
                            <div class="user-photo-item">
                                <?php if($user->id == $userinfo->id) : ?>
                                        <a href="<?=$base?>/fotos.php?foto=<?=$ph->id?>"><img class="fotoItem" src="<?=$base?>/media/uploads/<?=$ph->body?>" /></a>
                                    <?php else : ?>
                                        <a href="<?=$base?>/fotos.php?id=<?=$user->id?>&foto=<?=$ph->id?>"><img class="fotoItem" src="<?=$base?>/media/uploads/<?=$ph->body?>" /></a>
                                <?php endif ?>
                                <?php if($photo) : ?>
                                    <div class="modalArea" id="modalArea" style="display: flex;">
                                        <div class="BackModalPhoto"> </div>
                                        <div class="FrontModalPhoto">
                                            <?php
                                                if($user->id == $userinfo->id){
                                                    echo "<a href=".$base."/fotos.php><div class='closeModal'>x</div></a>";
                                                }else{
                                                    echo "<a href=".$base."/fotos.php?id=".$user->id."><div class='closeModal'>x</div></a>";
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

                    <?php if(count($user->photos) === 0) : ?>
                        Não há fotos desse úsuario.
                    <?php endif ?>

                </div>
            </div>

        </div>
        
    </div>

</section>

<script>
    document.title = 'DevsBook - fotos';
    window.onload=()=>{
        var modal = new VanillaModal();
    }
</script>

<?php
    require './partials/footer.php'
?>
