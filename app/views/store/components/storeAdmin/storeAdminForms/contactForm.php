<?php
    use Core\CSRF;
?>
<form class="s-form bg-white" method="post" action="<?=PROOT?>stores/admin/editContact">
    <p class="s-formTitle">Store Details</p>
    <?= CSRF::input($this->csrf_token_error); ?>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_name_error?></small>
        <input type="text" placeholder="Store name" class="input" name="store_name" value="<?=$this->store_name?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_email_error?></small>
        <input type="email" placeholder="Email" class="input" name="store_email" value="<?=$this->store_email?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_category_error?></small>
            
        <select class="input" name="store_category">
            <option>Select category</option>

            <?php foreach($this->category as $key => $value) : 
                $selected = ($key === $this->store_category) ? 'selected' : '';    
            ?>
                <?= '<option '.$selected.'>'.$key.'</option>' ?>
            <?php endforeach ?>
            
        </select>
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_country_error?></small>
        <input type="text" placeholder="Country" class="input" name="store_country" value="<?=$this->store_country?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_city_error?></small>
        <input type="text" placeholder="City" class="input" name="store_city" value="<?=$this->store_city?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_street_error?></small>
        <input type="text" placeholder="Street address" class="input" name="store_street" value="<?=$this->store_street?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_phone_error?></small>
        <input type="tel" placeholder="Telephone" class="input" name="store_phone" value="<?=$this->store_phone?>">
    </div>
    <div class="input-wrapper">
        <input type="submit" value="Submit" class="btn s-submitbtn">
    </div>
</form>