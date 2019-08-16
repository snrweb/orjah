<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Store Admin Area') ?>
    <?php $this->start('head') ?>
        <link rel="stylesheet" type="text/css" media="screen" href="<?=PROOT?>public/css/category.css" />
    <?php $this->end() ?>

    <?php $this->start('body') ?>
        <section class="mainCategories">
            <div class="mainCategories-s">
                <?php foreach($this->categories as $key => $value): ?>
                    <a href="<?=PROOT?>category/<?= str_replace(' ', '-', $key) ?>"><?= $key ?></a>
                <?php endforeach ?>
            </div>
        </section>

        <section class="pageMap">
            <a href="<?=PROOT?>">Home</a>
            <?php foreach($this->categoryMaps as $steps): ?>
                <span>></span>
                <a href="
                    <?=PROOT?>category<?php foreach($steps as $step): ?>/<?= str_replace(' ', '-', $step) ?><?php endforeach ?>">
                    <?= array_pop($steps) ?>
                </a>
            <?php endforeach ?>
        </section>

        <section class="c-categories">
            <div class="c-categories-s">
                <?php foreach($this->cat as $c):?>
                    <a href="<?=PROOT?>category<?= $this->mcat ?>/<?= str_replace(' ', '-', $c) ?>" >
                        <div class="c-category">
                            <div class="c-categoryImg">
                                <img src="<?=PROOT?>public/images/categories/<?= strtolower(str_replace(' ', '_', $c)).'.jpg' ?>" >
                            </div>
                            <p class="c-categoryName"><?= $c ?></p>
                        </div>
                    </a>
                <?php endforeach ?>
                <div class="clear-float"></div>
            </div>
        </section>

        <section class="c-container">
            <div class="c-products">
                <?php if(count($this->products) > 0): ?>
                    <?php foreach($this->products as $pr) { ?>
                        <div class="c-product">
                            <a href="<?=PROOT?>details/<?=$pr->product_id.'/'.
                                        str_replace(' ', '-', $pr->product_name)?>">
                                <div class="c-productImg">
                                    <img src="<?=PROOT?>public/images/products/<?= $pr->product_image_one ?>" />
                                </div>
                                <span class="c-productName"><?= $pr->product_name ?></span>
                                <span class="c-productRating"><?= $this->rate($pr->product_rating) ?></span>
                                <span class="c-productPrice">NGN <?= $pr->product_price ?></span>
                            </a>
                        </div>
                    <?php } ?>
                <?php endif ?>
                <div class="clear-float"></div>
            </div>
        </section>

        <?php if(count($this->products) > 0) { ?>
            <style>
                footer { margin-top: 15%; }
            </style>
        <?php } else { ?>
            <style>
                footer { margin-top: 30%; }
            </style>
        <?php } ?>

    <?php $this->end() ?>