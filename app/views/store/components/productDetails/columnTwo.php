<div class="d-ColumnTwo pull-left">
    <?= $this->errorMsg() ?>
    <small id="alert"></small>
    <div class="d-ProductDetailsHeader">
        <span class="d-ProductName"><?= $this->product_name ?></span>
        <div class="d-ProductDetailSubHeader">
            <span><?= $this->product_views ?> views since upload</span> 
            <span>|</span>
            <a href="#productReviews"><span><?php echo ($this->product_reviews <= 1) ? 
                                        $this->product_reviews.' product review' : 
                                        $this->product_reviews.' product reviews'?> 
            </span></a>
            <span>|</span>
            <a href="<?=PROOT?>details/writeReview/<?= $this->product_id ?>"><span>Write a review</span></a>
            <span class="d-ProductRatings"><?= $this->product_rating ?> out of 5 stars</span> 
        </div>
    </div>

    <p class="d-ProductPrice"><span>Price: </span>NGN <?= $this->product_price ?></p>
    <form method="post" class="d-OrderForm">
        <?= $this->successMsg() ?>
        <div class="d-OrderNumber">
            <label>Quantity</label>
            <input type="number" name="quantity" id="d-orderQuantity">
        </div>
        <button type="submit" class="d-SendOrderBtn btn" 
                id="d-SendOrderBtn" data-pid="<?= $this->product_id ?>">Send Order</button>
    </form>

    <?php if($this->isProductInBasket) { ?>
        <a href="<?=PROOT?>details/removeFromBasket/<?= $this->product_id ?>">
            <button class="d-AddToCartBtn btn" id="d-ToggleCartBtn" 
            data-status="remove" data-pid="<?= $this->product_id ?>">Remove From Cart </button>
        </a>
    <?php } else { ?>
        <a href="<?=PROOT?>details/addToBasket/<?= $this->product_id ?>">
            <button class="d-AddToCartBtn btn" id="d-ToggleCartBtn" 
            data-status="add" data-pid="<?= $this->product_id ?>">Add To Cart</button>
        </a>
    <?php } ?>

    <div class="d-ProductDescription">
        <span>Product description</span>
        <p><?= $this->product_details ?> the house is not for sale. Tell the man that I will be around tomorrow.</p>
    </div>

    <div class="d-ProductDeliveryTerms">
        <span>Delivery</span>
        <p><?= $this->product_details ?></p>
    </div>

    <div class="d-ProductReturnPolicy">
        <span>Return Policy</span>
        <p><?= $this->return_policy ?> </p>
    </div>

    <div class="d-ShareBtn">
        <span>Share on</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u=&t=" target="_blank" title="Share on Facebook"
            onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + 
            encodeURIComponent(document.URL)); return false;">
            <button class="d-FacebookShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/facebook.svg') ?>
            </button>
        </a>
        
        <a href="https://twitter.com/intent/tweet?" target="_blank" title="Tweet"
            onclick="window.open('https://twitter.com/intent/tweet?text= ' + encodeURIComponent(document.title) + 
            ':%20 ' + encodeURIComponent(document.URL)); return false;">
            <button class="d-TwitterShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/twitter.svg') ?>
            </button>
        </a>
            
        <a href="whatsapp://send?text=" title="share on whatsapp"
            onclick="window.open('whatsapp://send?text= ' + encodeURIComponent(document.title) + encodeURIComponent(document.URL));
                    return false;">
            <button class="d-InstagramShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/instagram.svg') ?>
            </button>
        </a>
            
        <a href="whatsapp://send?text=" title="share on whatsapp"
            onclick="window.open('whatsapp://send?text= ' + encodeURIComponent(document.title) + encodeURIComponent(document.URL));
                    return false;">
            <button class="d-WhatsappShareBtn btn">
                <?php echo file_get_contents(ROOT.'/public/images/svg/whatsapp.svg') ?>
            </button>
        </a>
    </div>
</div>