<?php 
    require_once './partials/feed-item-script.php';
    $actionPhrase = '';
    switch($f->type){
        case 'text';
            $actionPhrase = 'fez um post';
        break;
        case 'photo';
            $actionPhrase = 'postou uma foto';
        break;
    }
?>

<?php if($f->body) : ?>

    <div class="box feed-item" data-id="<?=$f->id;?>">
        <div class="box-body">
            <div class="feed-item-head row mt-20 m-width-20">
                <div class="feed-item-head-photo">
                    <a href="<?=$base?>/perfil.php?id=<?=$f->user->id?>"><img src="<?=$base?>/media/avatars/<?=$f->user->avatar?>" /></a>
                </div>
                <div class="feed-item-head-info">
                    <a href="<?=$base?>/perfil.php?id=<?=$f->user->id?>"><span class="fidi-name"><?=$f->user->name?></span></a>
                    <span class="fidi-action"><?=$actionPhrase?></span>
                    <br/>
                    <span class="fidi-date">Publicado em <?=date('d/m/Y', strtotime($f->created_at))?></span>
                </div>
                <?php if($userinfo->id == $f->user->id) : ?>
                    <div class="feed-item-head-btn">
                        <img src="<?=$base?>/assets/images/more.png" />
                        <div class="feed-item-more-window" >
                            <a href="<?=$base?>/deletePostController.php?id=<?=$f->id?>">Excluir post</a>
                        </div>
                    </div>
                <?php endif ?>
            </div>
            <div class="feed-item-body mt-10 m-width-20">

                <?php
                
                    switch($f->type){
                        case 'text';
                        echo str_replace('&#13;&#10;', '<br>', $f->body);
                        break;
                        case 'photo';
                            echo '<div class="ImageArea">
                                <img class="imageFeed" src="'.$base.'/media/uploads/'.$f->body.'" alt="foto" />
                            </div>';
                        break;
                    }

                ?>
            </div>
            <div class="feed-item-buttons row mt-20 m-width-20">
                <div class="like-btn <?=$f->liked ? 'on' : ''?>"><?=$f->likecount?></div>
                <div class="msg-btn"><?= count($f->comments) ?></div>
            </div>
            <div class="feed-item-comments">
                <div class="feed-item-comments-area">
                    <?php foreach($f->comments as $c) : ?>

                        <div class="fic-item row m-height-10 m-width-20">
                            <div class="fic-item-photo">
                                <a href="<?=$base?>/perfil.php?id=<?=$c->user->id?>"><img src="<?=$base?>/media/avatars/<?=$c->user->avatar?>" /></a>
                            </div>
                            <div class="fic-item-info">
                                <a href="<?=$base?>/perfil.php?id=<?=$c->user->id?>"><?=$c->user->name?></a>
                                <?=$c->body?>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
                
                <div class="fic-answer row m-height-10 m-width-20">
                    <div class="fic-item-photo">
                        <a href="<?=$base?>/perfil.php"><img src="<?=$base?>/media/avatars/<?=$userinfo->avatar?>" /></a>
                    </div>
                    <input type="text" class="fic-item-field" placeholder="Escreva um comentário" />
                </div>

                

            </div>
        </div>
    </div>

    <script>
        function closeFeedWindow() {
            document.querySelectorAll('.feed-item-more-window').forEach(item=>{
                item.style.display = 'none';
            });
            
            document.removeEventListener('click', closeFeedWindow);
        }

        document.querySelectorAll('.feed-item-head-btn').forEach(item=>{
            item.addEventListener('click', ()=>{
                closeFeedWindow();

                item.querySelector('.feed-item-more-window').style.display = 'block';
                setTimeout(()=>{
                    document.addEventListener('click', closeFeedWindow);
                }, 500);
            });
        });
    </script>

<?php endif ?>