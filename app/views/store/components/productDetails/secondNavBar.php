<div class="d-SecondNav">
    <div class="d-SecondNavCatName pull-left">
        <a href="">
            <span><?= $this->store_category ?><span>
        </a>
    </div>

    <div class="d-SecondNavSubCatNames pull-left">
        <?php foreach($this->categories as $cat => $sub_cat): ?>
            <?php if($cat == $this->store_category && is_array($sub_cat)) { ?>
                <?php foreach($sub_cat as $key => $value): ?>
                    <a href="<?=PROOT?>category/<?= str_replace(' ', '-', $key)?>"><?= $key ?></a>
                <?php endforeach ?>
            <?php } ?>
        <?php endforeach ?>
    </div>
    <div class="clear-float"></div>
</div>