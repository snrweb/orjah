<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" action="<?=PROOT?>stores/admin/editSocial">
    <p class="s-formTitle">Social Media Links</p>
    <?= CSRF::input($this->csrf_token_error); ?>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->facebook_error?></small>
        <input type="url" placeholder="Facebook page link" class="input" name="facebook" value="<?=$this->facebook?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->twitter_error?></small>
        <input type="url" placeholder="Twitter account link" class="input" name="twitter" value="<?=$this->twitter?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->instagram_error?></small>
        <input type="url" placeholder="Instagram account link" class="input" name="instagram" value="<?=$this->instagram?>">
    </div>
    <div class="input-wrapper">
        <input type="submit" value="Submit" class="btn s-submitbtn">
    </div>
</form>
<style>
    footer {
        margin-top: 16%;
    }
</style>