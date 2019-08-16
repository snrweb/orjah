<?php
    use Core\CSRF;
?>
<?php $this->setSiteTitle('Forgot Password') ?>

    <?php $this->start('body') ?>
        
        <?php if($this->urlParams == 'store'): ?>
            <form class="form" method="post" action="<?=PROOT?>password/forgot/<?= $this->urlParams ?>">
                <?= $this->errorMsg() ?>
                <?= $this->successMsg() ?>
                <p class="formTitle">Enter Your Store Name For Reset Link</p>
                <div style="margin-bottom: 10px;">
                    <input type="text" placeholder="Enter your store name" name="store_name">
                </div>
                <button class="submitbtn btn" type="submit">Submit</button>
            </form>
        <?php endif ?>

        <?php if($this->urlParams == 'buyer'): ?>
            <form class="form" method="post" action="<?=PROOT?>password/forgot/<?= $this->urlParams ?>">
                <?= $this->errorMsg() ?>
                <?= $this->successMsg() ?>
                <p class="formTitle">Enter Email Address For Reset Link</p>
                <div style="margin-bottom: 10px;">
                    <input type="email" placeholder="Enter your email address" name="email" style="width: 100%;">
                </div>
                <button class="submitbtn btn" type="submit">Submit</button>
            </form>
        <?php endif ?>

        <style>
            .form {width: 50%; margin-top: 100px;}
            .Footer{display: none;}
        </style>

    <?php $this->end() ?>