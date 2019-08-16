<?php
    use Core\CSRF;
?>
<form class="s-form" method="post" action="<?=PROOT?>stores/admin/editDelivery">
    <p class="s-formTitle">Delivery and Return Policy</p>
    <?= CSRF::input($this->csrf_token_error); ?>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->delivery_terms_error ?></small>
        <textarea type="url" class="textarea" placeholder="Delivery terms"
                name="delivery_terms" rows="5"><?=$this->delivery_terms?></textarea>
    </div>
    <div class="input-wrapper">
        <small class="errorDisplay"><?=$this->return_policy_error ?></small>
        <textarea type="url" class="textarea" placeholder="Return policy"
                name="return_policy" rows="5"><?=$this->return_policy?></textarea>
    </div>
    <div class="input-wrapper">
        <input type="submit" value="Submit" class="btn s-submitbtn">
    </div>
</form>