<?php
    use Core\CSRF;
?>
<div class="d-ProductReviewForm shadow">
    <form action="<?=PROOT?>details/writeReview/<?= $this->product_id ?>" method="post">
        <p class="d-ReviewFormTitle">Review this product</p>
        <?= CSRF::input($this->csrf_token_error); ?>
        <div class="form-control" style="margin-bottom: 25px;">
            <small class="errorDisplay"><?=$this->product_review_error?></small>
            <textarea class="textarea" rows="5" name="product_review" 
                      placeholder="Type your review here..."><?=$this->product_review?></textarea>
        </div>

        <small class="errorDisplay"><?=$this->product_rating_error?></small>
        <div class="d-ProductRatingInput" style="margin-top: -25px;">
            <span>5 star</span>
            <?php echo file_get_contents(ROOT.'/public/images/svg/5_star.svg') ?>
            <input type="radio" value="5" name="product_rating">
        </div>

        <div class="d-ProductRatingInput">
            <span>4 star</span>
            <?php echo file_get_contents(ROOT.'/public/images/svg/4_star.svg') ?>
            <input type="radio" value="4" name="product_rating">
        </div>

        <div class="d-ProductRatingInput">
            <span>3 star</span>
            <?php echo file_get_contents(ROOT.'/public/images/svg/3_star.svg') ?>
            <input type="radio" value="3" name="product_rating">
        </div>

        <div class="d-ProductRatingInput">
            <span>2 star</span>
            <?php echo file_get_contents(ROOT.'/public/images/svg/2_star.svg') ?>
            <input type="radio" value="2" name="product_rating">
        </div>

        <div class="d-ProductRatingInput">
            <span style="margin-right: 7px;">1 star </span>
            <?php echo file_get_contents(ROOT.'/public/images/svg/1_star.svg') ?>
            <input type="radio" value="1" name="product_rating">
        </div>

        <div>
            <button type="submit" class="btn bg-blue">Submit review</button>
        </div>
    </form>
</div>