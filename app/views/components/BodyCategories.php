
<section class="Body-Categories-s">
    <p class="Body-Categories-Title">Categories</p>
    <div class="Body-Categories-m">
        <?php foreach($this->categories as $c):?>
            <a href="<?=PROOT?>category/<?= str_replace(' ', '-', $c) ?>" >
                <div class="Body-Categories-Item-Wrapper">
                    <div class="Body-Categories-Item-Image">
                        <img src="<?=PROOT?>public/images/categories/<?= strtolower(str_replace(' ', '_', $c)).'.jpg' ?>" >
                    </div>
                    <p class="Body-Categories-Item-Name"><?= $c ?></p>
                </div>
            </a>
        <?php endforeach ?>
        <div class="clear-float"></div>
    </div>
</section>