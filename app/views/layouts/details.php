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
    <?= $this->content('head'); ?>
</head>
<body>
    <?php $this->gComponents('NavBar') ?>

    <?= $this->content('body'); ?>

    <?php $this->gComponents('footer') ?>
    <script type="text/javascript" src="<?=PROOT?>public/js/dist/details_bundle.js"></script>
</body>
</html>