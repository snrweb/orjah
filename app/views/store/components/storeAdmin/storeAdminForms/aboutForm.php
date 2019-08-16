<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" action="<?=PROOT?>stores/admin/editAbout">
    <p class="s-formTitle">About Store, Delivery and Return Policy</p>
    <?= CSRF::input($this->csrf_token_error); ?>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_about_error ?></small>
        <textarea type="url" class="textarea" placeholder="About store"
                name="store_about" rows="10"><?=$this->store_about?></textarea>
    </div>
    <div class="input-wrapper">
        <input type="submit" value="Submit" class="btn s-submitbtn">
    </div>
</form>

<style>
    footer {
        margin-top: 13%;
    }
</style>