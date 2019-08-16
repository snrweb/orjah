<?php
    use Core\Session;
?>

<p class="page-messageTitle">MESSAGES</p>
<ul class="page-message">
    <?php if(Session::exists(STORE_SESSION_NAME) || Session::exists(BUYER_SESSION_NAME) ) { ?>
        <?php foreach($this->messages as $msg) : ?>
            <a href="<?=PROOT?>message/read/<?= $msg->unique_id ?>"> 
                <li>
                    <span><?= $msg->buyer_name ?></span>
                    <p><?= $msg->message ?></p>
                    <span><?= timeFormatter($msg->created_at) ?></span>
                </li>
            </a>
        <?php endforeach ?>
    <?php } ?>
</ul>


<?php if(count($this->messages) > 2) { ?>
    <style>
        footer {
            margin-top: 15%;
        }
    </style>
<?php } else { ?>
    <style>
        footer {
            margin-top: 34%;
        }
    </style>
<?php } ?>