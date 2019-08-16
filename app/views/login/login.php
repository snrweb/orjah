<?php $this->setSiteTitle('Orjah - Store Login') ?>

<?php $this->start('body') ?>

    <form class="LoginFormWrapper" action="<?=PROOT?>login" method="post">
        <div class="LoginFormForBuyerWrapper">
            <a href="<?=PROOT?>login/buyer">
                <p>Click To Login as Buyer</p>
            </a>
        </div>

        <div class="FormTitle">
            <span>Login To Your Store</span>
        </div>
        
        <div class="LoginFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_name_error?></small>
            <input type="text" placeholder="Store name" name="store_name" value="<?=$this->store_name?>"/>
        </div>

        <div class="LoginFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_password_error?></small>
            <input type="password" placeholder="Password" name="store_password"/>
        </div>

        <div class="LoginFormCB-FPWrapper">
            <div class="LoginFormCheckboxWrapper">
                <label>Remember me</label>
                <input type="checkbox" value="true" name="remember_me"/>
            </div>
            <div class="LoginFormForgotPwdWrapper">
                <a href="<?=PROOT?>password/forgot/store">Forgot Password?</a>
            </div>
            <div class="Clear-Float"></div>
        </div>

        <div class="LoginFormButtonWrapper">
            <button type="submit" class="LoginFormSubmitButton">Login</button>
        </div>

        <div class="LoginFormRegisterWrapper">
            <a href="<?=PROOT?>register">Click here to register</a>
        </div>
    </form>

<?php $this->end() ?>