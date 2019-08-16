<?php $this->setSiteTitle('Orjah - Buyer Login') ?>

<?php $this->start('body') ?>

    <form class="LoginFormWrapper" action="<?=PROOT?>login/buyer" method="post">
        <div class="LoginFormForBuyerWrapper">
            <a href="<?=PROOT?>login">
                <p>Click To Login To Your Store</p>
            </a>
        </div>

        <div class="FormTitle">
            <span>Login To Your Account</span>
        </div>
        
        <div class="LoginFormInputWrapper">
            <small class="errorDisplay"><?=$this->email_error?></small>
            <input type="email" placeholder="Email" name="email" value="<?=$this->email?>"/>
        </div>

        <div class="LoginFormInputWrapper">
            <small class="errorDisplay"><?=$this->password_error?></small>
            <input type="password" placeholder="Password" name="password"/>
        </div>

        <div class="LoginFormCB-FPWrapper">
            <div class="LoginFormCheckboxWrapper">
                <label>Remember me</label>
                <input type="checkbox" value="true" name="remember_me"/>
            </div>
            <div class="LoginFormForgotPwdWrapper">
                <a href="<?=PROOT?>password/forgot/buyer">Forgot Password?</a>
            </div>
            <div class="Clear-Float"></div>
        </div>

        <div class="LoginFormButtonWrapper">
            <button type="submit" class="LoginFormSubmitButton">Login</button>
        </div>

        <div class="LoginFormRegisterWrapper">
            <a href="<?=PROOT?>register/buyer">Register as buyer</a>
        </div>
    </form>

<?php $this->end() ?>