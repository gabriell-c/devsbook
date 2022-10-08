<?php

    require_once './config.php';
    require_once './models/Auth.php';
    require_once './DAO/UserDAO.php';

    $auth = new Auth($pdo, $base);
    $userinfo = $auth->chkToken();
    $activeMenu = 'config';

    $postDao = new UserDAOMySQL($pdo);

    require_once './partials/header.php';
    require_once './partials/menu.php';
    
?>

<section class="feed mt-10">
    <h1><i class="fa-solid fa-gear"></i> Configurações</h1>

    <form action="<?=$base?>/configController.php" method="post" enctype="multipart/form-data" class="config-form">
        <p class="flash">
            <?php if(!empty($_SESSION['flash'])) : ?>
                <?=$_SESSION['flash'];?>
                <?php $_SESSION['flash'] = ''?>
            <?php endif ?>
        </p>

        <div class="AvatarArea" >
            <h4>Foto do perfil</h4>
            <div class="drop-zone" id="imgPhoto" style="background-image: url(<?= $base?>/media/avatars/<?=$userinfo->avatar?>) ;">
                <span class="drop-zone__prompt"><i class="fa-solid fa-cloud-arrow-up"></i></span>

                <input type="file" id="flImage" name="avatar" class="drop-zone__input" value="<?=$userinfo->avatar?>" />
            </div>
        </div>

        <div class="CoverArea">
            <h4>Foto da capa</h4>
            <div class="drop-zone-cover" id="imgPhoto2" style="background-image: url(<?=$base?>/media/covers/<?=$userinfo->cover?>) ;">
                <span class="drop-zone__prompt-cover"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                <input type="file" id="flImage2" name="cover" class="drop-zone__input-cover" value="<?=$userinfo->cover ? $userinfo->cover : ''?>" />
            </div>
        </div>

        <hr>

        <div class="input">
            <input required type="text" placeholder=" " name="name" value="<?=$userinfo->name?>" />
            <label>Nome completo</label>
        </div>

        <div class="input">
            <input required placeholder=" " type="email" name="email" value="<?=$userinfo->email?>" />
            <label>E-mail</label>
        </div>

        <div class="input">
            <input required placeholder=" " type="text" id="birthdate" name="birthdate" value="<?=date("d/m/Y", strtotime($userinfo->birthdate))?>" />
            <label>Data de nascimento</label>
        </div>

        <div class="input2">
            <input placeholder=" " type="text" name="city" value="<?=$userinfo->city?>" />
            <label>Cidade</label>
        </div>

        <div class="input2">
            <input placeholder=" " type="text" name="work" value="<?=$userinfo->work?>" />
            <label>Trabalho</label>
        </div>

        <hr>

        <div class="input2">
            <input placeholder=" " type="text" name="password" />
            <label>Nova senha</label>
        </div>

        <div class="input2">
            <input placeholder=" " type="text" name="password_confirm" />
            <label>Confirmar nova senha</label>
        </div>


        <div class="buttonsFooter">
            <button type="submit" class="button">Salvar</button>
            <div class="button3">Deletar conta</div>
        </div>
    </form>

    <form action="<?=$base?>/deleteUserController.php?id=<?=$userinfo->id?>" method="post">
        <div id="modalAreaDelete" class="modalAreaDelete">
            <div class="modalBackDelete"></div>
            <div class="modalDelete">
                
                    <?php if(!empty($_SESSION['flash'])) : ?>
                        <p class="flash">
                            <?=$_SESSION['flash'];?>
                            <?php $_SESSION['flash'] = ''?>
                        </p>    
                    <?php endif ?>
                
                <p>Tem certeza que deseja deletar sua conta permanentemente?</p>
                <div class="input2" style="margin: 0 auto;">
                    <input placeholder=" " required type="password" autofocus name="psw" />
                    <label>Senha</label>
                </div>
                <div class="footerModal">
                    <div class="cancelButton">Cancelar</div>
                    <button type="submit" class="deleteButton">Deletar</button>
                </div>
            </div>
        </div>
    </form>
</section>

<script src="https://unpkg.com/imask" ></script>
<script>
    IMask(
        document.getElementById("birthdate"),
        {mask: '00/00/0000'}
    );

    let photo = document.getElementById('imgPhoto');
    let file = document.getElementById('flImage');

    photo.addEventListener('click', () => {
        file.click();
    });

    file.addEventListener('change', () => {

        if (file.files.length <= 0) {
            return;

        }else{
            photo.style.backgroundImage = 'url(<?= $base?>/media/avatars/<?=$userinfo->avatar?>)';
        }

        let reader = new FileReader();

        reader.onload = () => {
            photo.style.backgroundImage = 'url('+reader.result+')';
        }

        reader.readAsDataURL(file.files[0]);
    });


    let photo2 = document.getElementById('imgPhoto2');
    let file2 = document.getElementById('flImage2');

    photo2.addEventListener('click', () => {
        file2.click();
    });

    file2.addEventListener('change', () => {

        if (file2.files.length <= 0) {
            return;

        }else{
            photo2.style.backgroundImage = 'url(<?= $base?>/media/avatars/<?=$userinfo->cover?>)';
        }

        let reader2 = new FileReader();

        reader2.onload = () => {
            photo2.style.backgroundImage = 'url('+reader2.result+')';
        }

        reader2.readAsDataURL(file2.files[0]);
    });

    let modal = document.getElementById("modalAreaDelete");
    let backModal = document.querySelector(".modalBackDelete");
    let buttonCancel = document.querySelector(".cancelButton");
    let deleteButton = document.querySelector(".button3");

    buttonCancel.addEventListener("click", ()=>{
        modal.style.top = '150vh';
    })

    backModal.addEventListener("click", ()=>{
        modal.style.top = '150vh';
    })

    deleteButton.addEventListener("click", ()=>{
        modal.style.top = '0';
    })

    document.title = 'DevsBook - configurações';
</script>

<?php
    require './partials/footer.php'
?>
