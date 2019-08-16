<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" action="<?=PROOT?>stores/admin/storeCategories">
    <?= CSRF::input($this->csrf_token_error); ?>
    <p class="s-formTitle">Modify Categories</p>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->category_one_error?></small>
        <label>Category one</label>
        <input type="text" maxlength="20" name="category_one" value="<?= $this->category_one ?>" placeholder="e.g New Arrivals"> 
    </div>

    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->category_two_error?></small>
        <label>Category two</label>
        <input type="text" maxlength="20" name="category_two" value="<?= $this->category_two ?>"> 
    </div>

    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->category_three_error?></small>
        <label>Category three</label>
        <input type="text" maxlength="20" name="category_three" value="<?= $this->category_three ?>"> 
    </div>

    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->category_four_error?></small>
        <label>Category four</label>
        <input type="text" maxlength="20" name="category_four" value="<?= $this->category_four ?>"> 
    </div>

    <div class="input-wrapper">
        <input type="submit" value="Submit" class="btn s-submitbtn" name="submit_category">
    </div>
</form>
<style>
    footer {
        margin-top: 5%;
    }
</style>