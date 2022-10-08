<?php $firstName = explode(' ', $userinfo->name)[0]; ?>

<div class="box feed-new">
    <div class="box-body">
        <div class="feed-new-editor m-10 row">
            <div class="feed-new-avatar">
                <img src="<?=$base?>/media/avatars/<?=$userinfo->avatar?>" />
            </div>
            <div class="feed-new-input-placeholder">O que você está pensando, <?=$firstName?>?</div>
            <div class="feed-new-input" contenteditable="true"></div>
            <div class="sendArea">
                <div class="feed-new-photo">
                    <i class='bx bxs-camera'></i>
                    <input class="feed-new-file" type="file" name="photo" accept="image/png,image/jpeg,image/jpg" />
                </div>
                <div class="feed-new-send">
                    <i class='bx bxs-send'></i>
                </div>
            </div>
        </div>
        <form class="feed_new_from" action="<?=$base?>/feedEditController.php" method="post">
            <input type="hidden" id="bodyInput" name="body" />
        </form>
    </div>
</div>

<script>
    let feedInput = document.querySelector(".feed-new-input");
    let feedButton = document.querySelector(".feed-new-send");
    let feedform = document.querySelector(".feed_new_from")
    let feedPhoto = document.querySelector('.feed-new-photo');
    let feedFile = document.querySelector('.feed-new-file');


    feedButton.addEventListener("click", ()=>{
        let value = feedInput.innerText.trim();

        feedform.querySelector('input[name=body]').value = value;
        feedform.submit();
    })


    feedPhoto.addEventListener('click', function(){
        feedFile.click();
    });
    feedFile.addEventListener('change', async function(){
        let photo = feedFile.files[0];
        let formData = new FormData();

        formData.append('photo', photo);
        let req = await fetch('ajax_upload.php', {
            method: 'POST',
            body: formData
        });
        let json = await req.json();

        if(json.error != '') {
            alert(json.error);
        }

        window.location.href = window.location.href;
    });
</script>