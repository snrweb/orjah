<div class="d-ColumnThree pull-left">
    <div class="d-StoreDetails shadow">

        <div class="d-storeLogo">
            <img src="<?=PROOT?>public/images/storeLogos/<?= $this->store_logo ?>">
            <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>"><?= $this->store_name ?></a>
        </div>

        <div class="d-StoreRatings">
            <span class=""><?= $this->store_rating ?>  out of 5 stars</span>
        </div>
        
        <div class="d-BookmarkBtn">
            <?php if($this->bookmark == false) { ?>
                <a href="<?=PROOT?>details/bookmarkStore/add/<?= $this->product_id ?>">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/heart_grey.svg') ?>
                    <span style="margin-top: -50px;">Add as favourite </span>
                </a>
            <?php } else { ?>
                <a href="<?=PROOT?>details/bookmarkStore/remove/<?= $this->product_id ?>">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/heart_red.svg') ?>
                    <span style="margin-top: -50px;">Remove as favourite </span>
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="d-StoreAbout shadow">
        <span>About <?= $this->store_name ?></span>
        <p><?= $this->store_about ?></p>
    </div>

    <div class="d-StoreContact shadow">
        <div class="d-Address">
            <span>Address</span>
            <p><?= $this->store_street . ', ' . $this->store_city . ', ' . $this->store_country ?></p>
        </div>

        <div class="d-SocialContactBtn">
            <span>Follow us on:</span>
            <div class="d-SocialContactPages">
                <a href="<?= $this->facebook ?>">
                <button class="d-FacebookPage btn">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/facebook.svg') ?>
                </button></a>
                <a href="<?= $this->twitter ?>"><button class="d-TwitterPage btn">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/twitter.svg') ?>
                </button></a>
                <a href="<?= $this->instagram ?>"><button class="d-InstagramPage btn">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/instagram.svg') ?>
                </button></a>
            </div>
        </div>

        <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/contact">
            <button class="d-SendMessageBtn btn">Send message to <?= $this->store_name ?></button>
        </a>

    </div>
</div>