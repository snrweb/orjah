<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" enctype="multipart/form-data" 
        action="<?=PROOT?>stores/admin/modifyProduct/<?=$this->product_id?>">
    <p class="s-formTitle">Update Product</p>

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
                <option <?php echo ($this->category_one === $this->store_menu) ? 'selected' : ''?> >
                    <?=$this->category_one?>
                </option>
            <?php endif ?>
            <?php if(!empty($this->category_two)): ?>
                <option <?php echo ($this->category_two === $this->store_menu) ? 'selected' : ''?> >
                    <?=$this->category_two?>
                </option>
            <?php endif ?>
            <?php if(!empty($this->category_three)): ?>
                <option <?php echo ($this->category_three === $this->store_menu) ? 'selected' : ''?> >
                    <?=$this->category_three?>
                </option>
            <?php endif ?>
            <?php if(!empty($this->category_four)): ?>
                <option <?php echo ($this->category_four === $this->store_menu) ? 'selected' : ''?> >
                    <?=$this->category_four?>
                </option>
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
                            <option <?php 
                                        if($this->product_category == $key.'|'.$sc) echo 'selected'; ?>>
                                <?= $key.' | '.$sc ?>
                            </option>
                        <?php endforeach ?>
                    <?php } else { ?>
                        <option <?php 
                            if($this->product_category == $value) echo 'selected'; ?>>
                            <?= $value ?>
                         </option> <?php } ?>
                <?php endforeach ?>
            <?php } ?>
        <?php endforeach ?>
        </select>
    </div>

    <div class="input-file">
        <small class="errorDisplay"><?=$this->product_image_one_error?></small>
        <span style="background-image: url(<?=PROOT?>public/images/products/<?=$this->product_image_one?>);">
            <?php if(empty($this->product_image_one)) echo 'No image selected'; ?>
        </span>
        <input type="file" class="input" name="product_image_one" 
                title="<?php echo (empty($this->product_image_one)) ? 'Select an image': $this->product_image_one; ?>">
        <input type="hidden" name="product_image_one_o" value="<?= $this->product_image_one; ?>">
    </div>
    <div class="input-file">
        <small class="errorDisplay"><?=$this->product_image_two_error?></small>
        <span style="background-image: url(<?=PROOT?>public/images/products/<?=$this->product_image_two?>);">
            <?php if(empty($this->product_image_two)) echo 'No image selected'; ?>
        </span>
        <input type="file" class="input" id="files" name="product_image_two" 
                title="<?php echo (empty($this->product_image_two)) ? 'Select an image': $this->product_image_two; ?>">
        <input type="hidden" name="product_image_two_o" value="<?= $this->product_image_two; ?>">
    </div>
    <div class="input-file">
        <small class="errorDisplay"><?=$this->product_image_three_error?></small>
        <span style="background-image: url(<?=PROOT?>public/images/products/<?=$this->product_image_three?>);">
            <?php if(empty($this->product_image_three)) echo 'No image selected'; ?>
        </span>
        <input type="file" class="input" name="product_image_three" 
                title="<?php echo (empty($this->product_image_three)) ? 'Select an image': $this->product_image_three; ?>">
        <input type="hidden" name="product_image_three_o" value="<?= $this->product_image_three; ?>">
    </div>
    <div class="ClearFloat"></div>

    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->product_details_error?></small>
        <textarea type="text" class="textarea" rows="8" 
                  name="product_details" ><?=$this->product_details?></textarea>
    </div>
    <div class="input-wrapper">
        <input type="submit" value="Submit" class="btn s-submitbtn">
    </div>
</form>