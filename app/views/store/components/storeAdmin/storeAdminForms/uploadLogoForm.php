<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" enctype="multipart/form-data"
    action="<?=PROOT?>stores/admin/editLogo">
    <p class="s-formTitle">Upload your store logo</p>
    <?= CSRF::input($this->csrf_token_error); ?>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_logo_error?></small>
        <input type="file" class="input" name="store_logo">
        
        <small class="errorDisplay">
            <i>We advice you use a landscape image dimension (preferrably less than 900 x 600)</i>
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