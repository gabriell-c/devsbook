<?php
    require './config.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" />
        <title>DevsBook - Login</title>
        <link rel="icon" href="<?=$base?>/assets/images/logo icon.png" />
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1"/>
        <link rel="stylesheet" href="<?=$base;?>/assets/css/login.css" />
    </head>
    <body>
        <header>
            <div class="container">
                <a href="<?=$base?>" style="height: 80%; align-items: center;"><img style="height: 70%;" src="<?=$base?>/assets/images/devsbook_logo.png" /></a>
            </div>
        </header>
        <section class="container main">
            
            <form method="POST" action="<?=$base?>/loginController.php">
                <p class="aviso">
                    <?php if(!empty($_SESSION['flash'])) : ?>
                        <?=$_SESSION['flash'];?>
                        <?php $_SESSION['flash'] = '';?>
                    <?php endif ?>
                </p>
                <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

                <input placeholder="Digite sua senha" class="input" type="password" name="password" />

                <input class="button" type="submit" value="Entrar" />

                <p>Ainda não tem conta? <a href="<?=$base?>/signup.php">Cadastre-se</a></p>
            </form>
        </section>
    </body>
</html>