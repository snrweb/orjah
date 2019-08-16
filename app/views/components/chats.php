<?php
    use Core\Session;
?>

<div class="ChatBox">
    <div class="ChatBody">
        <?php $receiver_id = '' ?>

        <?php if(Session::exists(BUYER_SESSION_NAME)) {  ?>
            <?php foreach($this->messageLists as $msg):
                if($msg->sender_type == 'store') {$receiver_id = $msg->sender_id;} ?>
                <?php if($msg->sender_id == Session::get(BUYER_SESSION_NAME) && $msg->sender_type == 'buyer') { ?>
                    <div class="pull-right ChatOnlineUser">
                        <span>You</span>
                        <p><?= $msg->message ?></p>
                        <span class="pull-right"><?= timeFormatter($msg->created_at) ?></span>
                    </div>
                    <div class="clear-float"></div>
                <?php } else { ?>
                    <div class="pull-left ChatOfflineUser" >
                        <span><?= $msg->sender_name ?></span>
                        <p><?= $msg->message ?></p>
                        <span class="pull-right"><?= timeFormatter($msg->created_at) ?></span>
                    </div>
                    <div class="clear-float"></div>
                <?php } ?>
            <?php endforeach ?>

        <?php } elseif(Session::exists(STORE_SESSION_NAME)) {  ?>
            <?php foreach($this->messageLists as $msg):
                if($msg->sender_type == 'buyer') {$receiver_id = $msg->sender_id;} ?>
                <?php if($msg->sender_id == Session::get(STORE_SESSION_NAME) && $msg->sender_type == 'store') { ?>
                    <div class="pull-right ChatOnlineUser">
                        <span>You</span>
                        <p><?= $msg->message ?></p>
                        <span class="pull-right"><?= timeFormatter($msg->created_at) ?></span>
                    </div>
                    <div class="clear-float"></div>
                <?php } else { ?>
                    <div class="pull-left ChatOfflineUser" >
                        <span><?= $msg->sender_name ?></span>
                        <p><?= $msg->message ?></p>
                        <span class="pull-right"><?= timeFormatter($msg->created_at) ?></span>
                    </div>
                    <div class="clear-float"></div>
                <?php } ?>
            <?php endforeach ?>
        <?php }  ?>

    </div>
    <form class="ChatAction" action="<?=PROOT?>message/send/<?= $receiver_id ?>" method="post">
        <textarea placeholder="Type your message here..." rows="3" name="message"></textarea>
        <button class="btn" type="submit">Send</button>
    </form>
    <div class="clear-float"></div>
</div>