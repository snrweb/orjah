<?php 
    use Core\CSRF;
    $this->setSiteTitle('Orjah - Buyer Registration') ?>

<?php $this->start('body') ?>

    <form class="RegisterFormWrapper" action="<?=PROOT?>register/buyer" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>
        <div class="RegisterFormForBuyerWrapper">
            <a href="<?=PROOT?>register">
                <p>Click To Create Store</p>
            </a>
        </div>

        <div class="FormTitle">
            <small class="errorDisplay"><?=$this->registrationError?></small>
            <span>Buyer Registration Form</span>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->buyer_name_error?></small>
            <input type="text" placeholder="Firstname and Lastname" name="buyer_name" 
                    value="<?=$this->buyer_name?>" required maxlength="50" />
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->email_error?></small>
            <input type="email" placeholder="Email" name="email" value="<?=$this->email?>" required/>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->password_error?></small>
            <input type="password" placeholder="Password" name="password" required/>
        </div>

        <div class="RegisterFormInputWrapper">
            <input type="password" placeholder="Confirm password" name="confirm_password" required/>
        </div>

        <div class="RegisterFormButtonWrapper">
            <button type="submit" class="RegisterFormButton">Register</button>
        </div>

    </form>

<?php $this->end() ?>