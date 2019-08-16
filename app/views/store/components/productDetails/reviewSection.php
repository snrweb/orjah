<div class="d-ReviewsWrapper">
    
    <p class="d-ReviewsTitle">
        <?php echo ($this->product_reviews <= 1) ? 
            $this->product_reviews.' customer review' : 
            $this->product_reviews.' customer reviews'?>
    </p>
    <div class="pull-left d-RatingGraph">
        <div class="d-RatingGraphBody">
            <div class="pull-left d-RatingGraphBody-Left">
                <div class="d-RatingGraphBody-Bar">
                    <div class="pull-left">5 star</div>
                    <div class="pull-left">
                        <div style="width: <?= $this->percent_five_rating ?>%;"></div>
                    </div>
                    <div class="pull-left"><?= $this->five_rating ?></div>
                    <div class="clear-float"></div>
                </div>
                
                <div class="d-RatingGraphBody-Bar">
                    <div class="pull-left">4 star</div>
                    <div class="pull-left">
                        <div style="width: <?= $this->percent_four_rating ?>%;"></div>
                    </div>
                    <div class="pull-left"><?= $this->four_rating ?></div>
                    <div class="clear-float"></div>
                </div>
                
                <div class="d-RatingGraphBody-Bar">
                    <div class="pull-left">3 star</div>
                    <div class="pull-left">
                        <div style="width: <?= $this->percent_three_rating ?>%;"></div>
                    </div>
                    <div class="pull-left"><?= $this->three_rating ?></div>
                    <div class="clear-float"></div>
                </div>
                
                <div class="d-RatingGraphBody-Bar">
                    <div class="pull-left">2 star</div>
                    <div class="pull-left">
                        <div style="width: <?= $this->percent_two_rating ?>%;"></div>
                    </div>
                    <div class="pull-left"><?= $this->two_rating ?></div>
                    <div class="clear-float"></div>
                </div>
                
                <div class="d-RatingGraphBody-Bar">
                    <div class="pull-left">1 star</div>
                    <div class="pull-left">
                        <div style="width: <?= $this->percent_one_rating ?>%;"></div>
                    </div>
                    <div class="pull-left"><?= $this->one_rating ?></div>
                    <div class="clear-float"></div>
                </div>
            </div>

            <div class="pull-left d-RatingGraphBody-Right">
                <div><?= $this->product_rating_value ?>/5</div>
            </div>
            <div class="clear-float"></div>
        </div>
    
        <a href="<?=PROOT?>details/writeReview/<?= $this->product_id ?>">
            <button class="btn">Write a review</button>
        </a>
    </div>

    <div class="pull-left d-Reviews">

        <?php if($this->product_reviews != 0) { ?>
            <?php foreach($this->productReviews as $prvs) : ?>
                <div class="d-Review">
                    <p><?=$prvs->buyer_name?></p>
                    <p><?=date('j M Y', strtotime($prvs->created_at))?></p>
                    <p><?=$this->rate($prvs->product_rating)?></p>
                    <p><?=$prvs->product_review?></p>
                </div>
            <?php endforeach ?>
        <?php } else { ?>
            <div class="d-Review">
                <p style="font-size: 20px; font-weight: 600; margin-top: 40px;">
                    There is no review for <?= $this->product_name ?>
                </p>
            </div>
        <?php } ?>
    </div>
    <div class="clear-float"></div>
</div>