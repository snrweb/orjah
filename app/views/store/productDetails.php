
<?php $this->setSiteTitle($this->product_name) ?>
    <?php $this->start('head') ?>
    <link rel="stylesheet" type="text/css" media="screen" href="<?=PROOT?>public/css/details.css" />
    <?php $this->end() ?>

    <?php $this->start('body') ?>

        <section class="mainCategories">
            <div class="mainCategories-s">
                <?php foreach($this->categories as $key => $value): ?>
                    <a href="<?=PROOT?>category/<?= str_replace(' ', '-', $key) ?>"><?= $key ?></a>
                <?php endforeach ?>
            </div>
        </section>

        <div class="d-Body">
            <?php if($this->urlParams === 'details'): ?>

                <section class="pageMap">
                    <a href="<?=PROOT?>">Home</a>
                    <?php foreach($this->categoryMaps as $maps): ?>
                        <span>></span>
                        <a href="
                            <?=PROOT?>category<?php foreach($maps as $map): ?>/<?= str_replace(' ', '-', $map) ?><?php endforeach ?>">
                            <?= array_pop($maps) ?>
                        </a>
                    <?php endforeach ?>
                </section>
                
                <?php $this->components('productDetails/columnOne'); ?>

                <?php $this->components('productDetails/columnTwo'); ?>

                <?php $this->components('productDetails/columnThree'); ?>
                
                <div class="clear-float"></div>

                <div class="d-SimilarProducts">
                    <p>Similar Products</p>
                    <div class="d-SimilarProducts-static">
                        <div class="d-SimilarProducts-moving">
                            <?php foreach($this->similarProducts as $pr) { ?>
                                <div class="d-ProductDIV">
                                    <a href="<?=PROOT?>details/<?=$pr->product_id.'/'.
                                                str_replace(' ', '-', $pr->product_name)?>">
                                        <div class="d-ProductImg">
                                            <img src="<?=PROOT?>public/images/products/<?= $pr->product_image_one ?>"/>
                                        </div>
                                        <div class="productName-Price">
                                            <p><?= $pr->product_name ?></p>
                                            <p><?= $this->rate($pr->product_rating) ?></p>
                                            <p>NGN <?= $pr->product_price ?></p>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                            <div class="clear-float"></div>
                            
                        </div>
                    </div>
                </div>

                <?php $this->components('productDetails/reviewSection'); ?>

                <div class="d-SimilarStores">
                    <p>Similar Stores</p>
                    <div class="d-SimilarStores-static">
                        <div class="d-SimilarStores-moving">
                            <?php foreach($this->similarStores as $pr) { ?>
                                <div class="d-SimilarStoreDIV">
                                    <a href="<?=PROOT?><?=str_replace(' ', '-', $pr->store_name)?>">
                                        <div class="d-SimilarStore-Img">
                                            <img src="<?=PROOT?>public/images/storeLogos/<?= $pr->store_logo ?>"/>
                                        </div>
                                        <div class="d-SimilarStore-Price">
                                            <p><?= $pr->store_name ?></p>
                                            <p><?= $this->rate($pr->store_rating) ?></p>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                            <div class="clear-float"></div>
                        </div>
                    </div>
                </div>

            <?php endif ?>

            <?php 
                if($this->urlParams === 'review') {
                    $this->components('productDetails/writeReview');
                }   
            ?>
        </div>
        
    <?php $this->end() ?>