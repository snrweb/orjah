<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" enctype="multipart/form-data" 
        action="<?=PROOT?>stores/admin/addProduct">
    <p class="s-formTitle">Add Product</p>

    <?= CSRF::input($this->csrf_token_error); ?>

    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->product_name_error?></small>
        <input type="text" placeholder="Product name" class="input" name="product_name" value="<?=$this->product_name?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->product_price_error?></small>
        <input type="text" placeholder="Price" class="input" name="product_price" value="<?=$this->product_price?>">
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->store_menu_error?></small>
        <label>Preferred store menu</label>
        <select class="input" name="store_menu">
            <?php if(!empty($this->category_one)): ?>
                <option><?=$this->category_one?></option>
            <?php endif ?>
            <?php if(!empty($this->category_two)): ?>
                <option><?=$this->category_two?></option>
            <?php endif ?>
            <?php if(!empty($this->category_three)): ?>
                <option><?=$this->category_three?></option>
            <?php endif ?>
            <?php if(!empty($this->category_four)): ?>
                <option><?=$this->category_four?></option>
            <?php endif ?>
        </select>
    </div>

    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->product_cat_error?></small>
        <label>Product category</label>
        <select class="input" name="product_cat">
            <option>Select category</option>
            <?php foreach($this->categories as $cat => $sub_cat): ?>
            <?php if($cat == $this->store_category && is_array($sub_cat)) { ?>
                <?php foreach($sub_cat as $key => $value): ?>
                    <?php if(is_array($value)) { ?>
                        <?php foreach($value as $sc): ?>
                            <option><?= $key.' | '.$sc ?></option>
                        <?php endforeach ?>
                    <?php } ?>
                <?php endforeach ?>
            <?php } ?>
        <?php endforeach ?>
        </select>
    </div>

    <div class="input-wrapper input-file">
        <small class="errorDisplay"><?=$this->product_image_one_error?></small>
        <input type="file" class="input" name="product_image_one">
    </div>
    <div class="input-wrapper input-file">
        <small class="errorDisplay"><?=$this->product_image_two_error?></small>
        <input type="file" class="input" name="product_image_two">
    </div>
    <div class="input-wrapper input-file">
        <small class="errorDisplay"><?=$this->product_image_three_error?></small>
        <input type="file" class="input" name="product_image_three">
    </div>

    <div class="clear-float"></div>

    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->product_details_error?></small>
        <textarea type="text" class="textarea" rows="8" placeholder="Product details"
                  name="product_details" ><?=$this->product_details?></textarea>
    </div>
    <div class="input-wrapper">
        <input type="submit" value="Submit" class="btn s-submitbtn">
    </div>
</form>