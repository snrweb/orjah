<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" enctype="multipart/form-data"
    action="<?=PROOT?>stores/admin/addCover">
    <p class="s-formTitle">Upload A Cover Photo</p>
    <?= CSRF::input($this->csrf_token_error); ?>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_coverPhoto_error?></small>
        <input type="file" class="input" name="store_coverPhoto">
        
        <small class="errorDisplay">
            <i>We advice you use a landscape image dimension (preferrably around 1000 x 450 pixels)</i>
        </small>
    </div>
    <div class="input-wrapper">
        <input type="submit" value="Upload" class="btn s-submitbtn">
    </div>
</form>

<style>
    footer {
        margin-top: 16%;
    }
</style>