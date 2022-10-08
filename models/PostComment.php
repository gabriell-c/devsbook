<?php
    class PostComment{
        public $id;
        public $id_post;
        public $id_user;
        public $body;
        public $created_at;
    }

    interface PostcommentDAO{
        public function getComments($id_post);
        public function addcomment(PostComment $pc);
        public function deleteFromPost($id);
    }
?>