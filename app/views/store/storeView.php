<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle($this->store_name) ?>

    <?php $this->start('body') ?>

        <section class="s-secondNavBar">
            <div class="s-secondNavBar-menu">
                <?php if($this->urlParams === 'product') : ?>
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
        
        <?php if($this->urlParams === 'product') : ?>
        
            <section class="s-container">
                <div class="s-coverPhoto">
                    <img src="<?=PROOT?>public/images/storeCoverPhoto/<?= $this->store_coverPhoto ?>">
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
                            <?php foreach($this->products as $pr) {
                                if($pr->store_menu == $store_menu) { ?>
                                    <div class="s-product">
                                        <a href="<?=PROOT?>details/<?=$pr->product_id.'/'.
                                                    str_replace(' ', '-', $pr->product_name)?>">
                                            <div class="s-productImage">
                                                <img src="<?=PROOT?>public/images/products/<?= $pr->product_image_one ?>" />
                                            </div>
                                            <span class="s-productName"><?= $pr->product_name ?></span>
                                            <span class="s-productRating"><?= $this->rate($pr->product_rating) ?></span>
                                            <span class="s-productPrice">NGN <?= $pr->product_price ?></span>
                                        </a>
                                    </div>
                            <?php } } ?>
                            <div class="clear-float"></div>
                        </div>
                    <?php endforeach ?>
                </div>
            </section>
        <?php endif ?>

        <?php if($this->urlParams === 'delivery') : ?>
            <section class="s-aboutPage">
                <p class="s-aboutPageTitle">Delivery Terms</p>
                <p><?= $this->delivery_terms ?></p>
            </section>
            <style>
                footer {
                    margin-top: 37%;
                }
            </style>
        <?php endif ?>

        <?php if($this->urlParams === 'return') : ?>
            <section class="s-aboutPage">
                <p class="s-aboutPageTitle">Return Policy</p>
                <p><?= $this->return_policy ?></p>
            </section>
            <style>
                footer {
                    margin-top: 37%;
                }
            </style>
        <?php endif ?>

        <?php if($this->urlParams === 'about') : ?>
            <section class="s-aboutPage">
                <p class="s-aboutPageTitle">About <?= $this->store_name ?></p>
                <p><?= $this->store_about ?></p>
            </section>
            <style>
                footer {
                    margin-top: 37%;
                }
            </style>
        <?php endif ?>

        <?php if($this->urlParams === 'contact') : ?>
            <section class="s-contactPage">

                <form method="post" action="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/contact">
                    <p class="s-contactPageTitle">Get in Touch</p>
                    <div>
                        <small class="errorDisplay"><?=$this->message_error?></small>
                        <small><?= $this->successMsg() ?></small>
                        <textarea placeholder="Type your message here..." rows="7" name="message" 
                                maxlength="1000" required></textarea>
                    </div>
                    <button type="submit" class="btn">Send Message</button>
                </form>

                <div class="s-contactPage-details">
                    <p class="s-contactPageTitle">Connect with us: </p>
                    <div class="s-contactPage-detail">
                        <p>For support or any question</p>
                        <p>Email us at <span style="color: #5d49a3;"><?= $this->store_email?></span></p>
                    </div>

                    <div class="s-contactPage-detail">
                        <p>You can also give us a call</p>
                        <p><span><?= $this->store_phone?></span></p>
                    </div>

                    <div class="s-contactPage-detail">
                        <p><?= $this->store_name.' '.$this->store_country?></p>
                        <p><?= $this->store_street . ', ' . $this->store_city ?></p>
                    </div>
                </div>
                <div class="clear-float"></div>
            </section>
            <style>
                footer {
                    margin-top: 18%;
                }
            </style>
        <?php endif?>
        
    <?php $this->end() ?>