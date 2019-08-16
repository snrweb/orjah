<div>
    <div>
        <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>">
            <span class="s-columnTwo-storeLogo pull-left" 
                style="background-image: url(<?=PROOT?>public/images/storeLogos/<?= $this->store_logo?>);"></span>
        </a>
        <p class="s-columnTwo-storeName pull-left"><?= $this->store_name?></p>
        <div class="clear-float"></div>
    </div>

    <p class="s-columnTwo-storeRating"> <?= $this->store_rating?> out of 5 stars</p>
    <p class="s-columnTwo-storeBookmark">
        <?php if($this->bookmark == false) { ?>
            <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/bookmarkStore/add/<?= $this->store_id ?>">
                <?php echo file_get_contents(ROOT.'/public/images/svg/heart_grey.svg') ?>
                <span style="margin-top: -50px;">Add as favourite </span>
            </a>
        <?php } else { ?>
            <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>/bookmarkStore/remove/<?= $this->store_id ?>">
                <?php echo file_get_contents(ROOT.'/public/images/svg/heart_red.svg') ?>
                <span style="margin-top: -50px;">Remove as favourite </span>
            </a>
        <?php } ?>
    </p>
</div>

<hr>
<div>
    <p class="s-columnTwo-title">Delivery Terms</p>
    <p><?= $this->delivery_terms ?></p>
</div>

<hr>
<div>
    <p class="s-columnTwo-title">Return Policy</p>
    <p><?= $this->return_policy ?></p>
</div>

<hr>
<div>
    <div class="s-columnTwo-ShareBtn">
        <span>Share on</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=&t=" target="_blank" title="Share on Facebook"
            onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + 
            encodeURIComponent(document.URL)); return false;">
            <button class="s-FacebookShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/facebook.svg') ?>
            </button>
        </a>
        
        <a href="https://twitter.com/intent/tweet?" target="_blank" title="Tweet"
            onclick="window.open('https://twitter.com/intent/tweet?text= ' + encodeURIComponent(document.title) + 
            ':%20 ' + encodeURIComponent(document.URL)); return false;">
            <button class="s-TwitterShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/twitter.svg') ?>
            </button>
        </a>
            
        <a href="whatsapp://send?text=" title="share on whatsapp"
            onclick="window.open('whatsapp://send?text= ' + encodeURIComponent(document.title) + encodeURIComponent(document.URL));
                    return false;">
            <button class="s-InstagramShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/instagram.svg') ?>
            </button>
        </a>
            
        <a href="whatsapp://send?text=" title="share on whatsapp"
            onclick="window.open('whatsapp://send?text= ' + encodeURIComponent(document.title) + encodeURIComponent(document.URL));
                    return false;">
            <button class="s-WhatsappShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/whatsapp.svg') ?>
            </button>
        </a>
    </div>
</div>