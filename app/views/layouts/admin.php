<?php
    use Core\Session;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title> <?=$this->siteTitle(); ?> </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="<?=PROOT?>public/css/gen.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?=PROOT?>public/css/admin.css" />
        <?= $this->content('head'); ?>
    </head>
    <body>
        <div id="root">
            <?php $this->gComponents('NavBar2') ?>
            <section class="mainCategories">
                <?php 
                    $cat = ($this->urlParams == 'store') ? 'store' : 'product';
                    foreach($this->categories as $key => $value): ?>
                    <a href="<?=PROOT?>admin223/<?= $cat ?>/category/<?= str_replace(' ', '-', $key) ?>"><?= $key ?></a>
                <?php endforeach ?>
            </section>
            
            <section class="a-switchOptions">
                <a href="<?=PROOT?>admin223/store">
                    <button class="btn a-storeLink" 
                            style="<?php if($this->urlParams == 'store') {echo 'background: #433575';} ?>">Store</button>
                </a>
                <a href="<?=PROOT?>admin223/product">
                    <button class="btn a-productLink" 
                            style="<?php if($this->urlParams == 'product') {echo 'background: #433575';} ?>">Products</button>
                </a>
            </section>

            <?= $this->content('body'); ?>

        </div>

        <?php $this->gComponents('footer') ?>
        <style>
            .Footer {
                margin-top: 18%;
            }
        </style>
    </body>
<script type="text/javascript" src="<?=PROOT?>public/js/dist/admin_bundle.js"></script>
</html>