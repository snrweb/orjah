<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Store Admin Area') ?>

    <?php $this->start('body') ?>

        <section class="s-secondNavBar">
            <div class="s-secondNavBar-menu">
                <?php if($this->urlParams == '') : ?>
                    <div>
                        <a href="#<?= str_replace(' ', '-', $this->category_one) ?>">
                            <span><?= $this->category_one?></span>
                        </a>
                        <a href="#<?= str_replace(' ', '-', $this->category_two) ?>">
                            <span><?= $this->category_two?></span>
                        </a>
                        <a href="#<?= str_replace(' ', '-', $this->category_three) ?>">
                            <span><?= $this->category_three?></span>
                        </a>
                        <a href="#<?= str_replace(' ', '-', $this->category_four) ?>">
                            <span><?= $this->category_four?></span>
                        </a>
                    </div>
                <?php endif ?>
                <div>
                    <span>
                        <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/about">About us</a>
                    </span>
                    <span>
                        <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/contact">Contact</a>
                    </span>
                </div>
                <div class="s-columnTwo-SocialContactBtn">
                    <span>Follow us on:</span>
                    
                    <?php if(!empty($this->facebook)) : ?>
                        <a href="<?= $this->facebook ?>">
                        <button class="s-columnTwo-FacebookPage btn">
                            <?php echo file_get_contents(ROOT.'/public/images/svg/facebook.svg') ?>
                        </button></a>
                    <?php endif ?>
                    
                    <?php if(!empty($this->twitter)) : ?>
                        <a href="<?= $this->twitter ?>"><button class="s-columnTwo-TwitterPage btn">
                            <?php echo file_get_contents(ROOT.'/public/images/svg/twitter.svg') ?>
                        </button></a>
                    <?php endif ?>
                    
                    <?php if(!empty($this->instagram)) : ?>
                        <a href="<?= $this->instagram ?>"><button class="s-columnTwo-InstagramPage btn">
                            <?php echo file_get_contents(ROOT.'/public/images/svg/instagram.svg') ?>
                        </button></a>
                    <?php endif ?>
                </div>
            </div>
        </section>
        
        <section class="s-container"> 
            <div class="s-columnOne pull-left">
                <?php $this->components('storeAdmin/columnOne');  ?>
            </div>
            
            <div class="s-columnTwo pull-left">
                <?php if($this->urlParams === '') { ?>
                    <div class="s-coverPhoto">
                        <img src="<?=PROOT?>public/images/storeCoverPhoto/<?= $this->store_coverPhoto ?>">
                        <div class="s-addCoverBtn">
                            <a href="<?=PROOT?>stores/admin/addCover">
                                <?php if(empty($this->store_coverPhoto)) { ?>
                                    <button class="btn">Add Cover Photo</button>
                                <?php } else { ?>
                                    <button class="btn">Change Cover Photo</button>
                                <?php } ?>
                            </a>
                        </div>
                    </div>

                    <style>
                        .s-deliveryPolicy div svg { fill: #544c74; }
                        .s-deliveryPolicy div a{ color: #544c74; }
                    </style>

                    <div class="s-deliveryPolicy">
                        <div>
                            <?php echo file_get_contents(ROOT.'/public/images/svg/truck-1.svg') ?>
                            <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/delivery">Goods Delivery</a>
                        </div>
                        <div>
                            <?php echo file_get_contents(ROOT.'/public/images/svg/truck-4.svg') ?>
                            <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/return">Our Return Policy</a>
                        </div>
                    </div>

                    <div class="s-productSection">
                        <?php foreach($this->store_menus as $store_menu) : ?>
                            <div class="s-productCategory">
                                <h4 class="s-productCategoryTitle" 
                                    id="<?=str_replace(' ', '-', $store_menu)?>"><?= $store_menu ?></h4>
                                
                                <?php foreach($this->products as $pr) : ?>
                                    <?php if($pr->store_menu == $store_menu) : ?>
                                        <div class="s-product">
                                            <a href="<?=PROOT?>details/<?=$pr->product_id.'/'.
                                                                str_replace(' ', '-', $pr->product_name)?>">
                                                <div class="s-productImage">
                                                    <img src="<?=PROOT?>public/images/products/<?= $pr->product_image_one ?>">
                                                </div>
                                                <span class="s-productName"><?= $pr->product_name ?></span>
                                                <span class="s-productPrice">NGN <?= $pr->product_price ?></span>
                                            </a>
                                            <a href="<?=PROOT?>stores/admin/modifyProduct/<?= $pr->product_id ?>">
                                                <button class="s-productEdit btn">Edit</button>
                                            </a>
                                            <a href="<?=PROOT?>stores/admin/deleteProduct/<?= $pr->product_id ?>">
                                                <button class="s-productDelete btn">Delete</button>
                                            </a>
                                        </div>
                                    <?php endif ?>
                                <?php endforeach ?>
                                <a href="<?=PROOT?>stores/admin/addProduct">
                                    <div class="s-product s-addProduct">
                                        <h1>+</h1>
                                    </div>
                                </a>
                                <div class="clear-float"></div>
                            </div>
                        <?php endforeach ?>
                        <div class="clear-float"></div>
                    </div>
                <?php } ?>
                <?php if($this->urlParams === 'details') {  $this->components('storeAdmin/storeAdminForms/contactForm'); } ?>

                <?php if($this->urlParams === 'socials') { $this->components('storeAdmin/storeAdminForms/socialLinkForm'); } ?>

                <?php if($this->urlParams === 'about') { $this->components('storeAdmin/storeAdminForms/aboutForm');} ?>

                <?php if($this->urlParams === 'delivery') { $this->components('storeAdmin/storeAdminForms/deliveryForm');} ?>

                <!-- form to upload store logo -->
                <?php if($this->urlParams === 'logo') { $this->components('storeAdmin/storeAdminForms/uploadLogoForm'); } ?>

                <!-- form to add product -->
                <?php if($this->urlParams === 'add') { $this->components('storeAdmin/storeAdminForms/addProductForm'); } ?>

                <?php if($this->urlParams === 'modify') { $this->components('storeAdmin/storeAdminForms/modifyProductForm'); } ?>

                <?php if($this->urlParams === 'category') { $this->components('storeAdmin/storeAdminForms/addCategoryForm'); } ?>

                <?php if($this->urlParams === 'coverPhoto') { $this->components('storeAdmin/storeAdminForms/coverPhotoForm'); } ?>

                <?php if($this->urlParams === 'orders') { $this->components('storeAdmin/orders'); } ?>
            </div>
            
            <div class="clear-float"></div>
        </section>
        
    <?php $this->end() ?>