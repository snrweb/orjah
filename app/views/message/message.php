
<?php 
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Message') ?>
<?php $this->start('head') ?>
<?php $this->end() ?>

<?php $this->start('body') ?>

    <?php if($this->urlParams === 'messages') { ?>
        <?php $this->gComponents('messages'); ?>
    <?php } ?>
        
    <?php if($this->urlParams === 'chats') { ?>
        <?php $this->gComponents('chats'); ?>
    <?php } ?>
    
<?php $this->end() ?>