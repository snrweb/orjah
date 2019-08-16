
<?php $this->setSiteTitle('Baskets') ?>
<?php $this->start('head') ?>
<?php $this->end() ?>

<?php $this->start('body') ?>

    <div class="basketContainer">
        <div class="basketItems">
            <?php 
                $totalPrice = 0;
                foreach($this->basketItems as $b) : 
                $totalPrice += $b->product_price; 
            ?>
                <div class="basketItem">
                    <div class="basketItem-img">
                        <img src="<?=PROOT?>public/images/products/<?= $b->product_image_one ?>">
                    </div>
                    <div class="basketItem-name">
                        <a href="<?=PROOT?>details/<?=$b->product_id?>/<?= str_replace(' ', '-', $b->product_name) ?>">
                            <span><?= $b->product_name ?></span>
                        </a>
                        <p>
                            <a href="<?=PROOT?><?= str_replace(' ', '-', $b->store_name) ?>">
                                <span>Store: <?= $b->store_name ?></span>
                            </a>
                            <small><?= timeFormatter($b->created_at) ?> ago</small>
                        </p>
                    </div>
                    <div class="basketItem-price">
                        <span>NGN <?= $b->product_price ?></span>
                    </div>
                    <div class="basketItem-remove">
                        <a href="<?=PROOT?>basket/remove/<?= $b->basket_id?>">
                            <button class="btn">Remove</button>
                        </a>
                    </div>
                    <div class="clear-float"></div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="basketCost">
            <div>
                <p class="pull-left">Total Items in Basket</p>    
                <p class="pull-right"><?= count($this->basketItems) ?></p>
                <div class="clear-float"></div>
            </div>
            <div>
                <p class="pull-left">Total Cost</p>  
                <p class="pull-right">NGN <?= $totalPrice ?></p>
                <div class="clear-float"></div>
            </div>
        </div>
        <div class="clear-float"></div>
    </div>
    
    <?php if(count($this->basketItems) > 2) { ?>
        <style> footer { margin-top: 15%; } </style>
    <?php } else { ?>
        <style> footer { margin-top: 32%; } </style>
    <?php } ?>
<?php $this->end() ?>