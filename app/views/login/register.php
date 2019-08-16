<?php 
    use Core\CSRF;
    $this->setSiteTitle('Orjah - Store Registration') ?>

<?php $this->start('body') ?>

    <form class="RegisterFormWrapper" action="<?=PROOT?>register" method="post">
        <?= CSRF::input($this->csrf_token_error); ?>
        <div class="RegisterFormForBuyerWrapper">
            <a href="<?=PROOT?>register/buyer">
                <p>Click To Register as Buyer</p>
            </a>
        </div>

        <div class="FormTitle">
            <small class="errorDisplay"><?=$this->registrationError?></small>
            <span>Create Your Store</span>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_email_error?></small>
            <input type="email" placeholder="Email" name="store_email" value="<?=$this->email?>"/>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_name_error?></small>
            <input type="text" placeholder="Store name" name="store_name" value="<?=$this->store_name?>"/>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_category_error?></small>
               
            <select class="input" name="store_category">
                <option>Select category</option>

                <?php foreach($this->category as $key => $value) : ?>
                    <?= '<option>'.$key.'</option>' ?>
                <?php endforeach ?>
                
            </select>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_country_error?></small>
            <input type="text" placeholder="Country" name="store_country" value="<?=$this->store_country?>"/>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_city_error?></small>
            <input type="text" placeholder="City" name="store_city" value="<?=$this->store_city?>"/>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_street_error?></small>
            <input type="text" placeholder="Street" name="store_street" value="<?=$this->store_street?>"/>
        </div>

        <div class="RegisterFormInputWrapper">
            <small class="errorDisplay"><?=$this->store_password_error?></small>
            <input type="password" placeholder="Password" name="store_password"/>
        </div>

        <div class="RegisterFormInputWrapper">
            <input type="password" placeholder="Confirm password" name="confirm_password"/>
        </div>

        <div class="RegisterFormButtonWrapper">
            <button type="submit" class="RegisterFormButton">Register</button>
        </div>

        <div class="RegisterFormLoginWrapper">
            <a href="<?=PROOT?>login">Click here to login</a>
        </div>
    </form>

<?php $this->end() ?>