<?php
use Core\Session;
?>

<nav class="navBar2">
    <a href="<?=PROOT?>">
        <div class="navBarLogo" style="background-image: url(<?=PROOT?>public/images/logo/logo.jpg);"></div>
    </a>

    <div class="navBarSearch">
        <input type="text" placeholder="Search categories, products, stores..." />
    </div>
    
    <div class="navBarSearch-icon">
            <?php echo file_get_contents(ROOT.'/public/images/svg/search.svg') ?> 
    </div>

    <?php if(Session::exists(STORE_SESSION_NAME)): ?>
        <div class="navBarMenu-icon">
                <?php echo file_get_contents(ROOT.'/public/images/svg/menu.svg') ?> 
        </div>
    <?php endif ?>

    <?php if(Session::exists(STORE_SESSION_NAME)): ?>
        <a href="<?=PROOT?>stores/admin">
            <div class="navBarAdmin">
                <?php echo file_get_contents(ROOT.'/public/images/svg/shop.svg') ?>  </div>
        </a>
        <a href="<?=PROOT?>stores/admin/orders">
            <div class="navBarOrders">
                <?php echo file_get_contents(ROOT.'/public/images/svg/list.svg') ?>  
                <span> <?= $this->newOrders ?> </span>
            </div>
        </a>
    <?php endif ?>

    <?php if(Session::exists(BUYER_SESSION_NAME) || Session::exists(STORE_SESSION_NAME)): ?>
        <a href="<?=PROOT?>message">
            <div class="navBarMsg">
                <?php echo file_get_contents(ROOT.'/public/images/svg/message.svg') ?> 
                <span><?= $this->msgCount ?></span>
            </div>
        </a>
    <?php endif ?>

    <?php if(Session::exists(BUYER_SESSION_NAME)): ?>
        <a href="<?=PROOT?>order">
            <div class="navBarOrders">
                <?php echo file_get_contents(ROOT.'/public/images/svg/list.svg') ?>  
                <span id="navBarOrders-span"><?= $this->totalOrders ?></span></div>
        </a>

        <?php if($this->totalProductInBasket != ''): ?>
            <a href="<?=PROOT?>basket">
                <div class="navBarBasket">
                <?php echo file_get_contents(ROOT.'/public/images/svg/cart.svg') ?> 
                <span id="navBarBasket-span"><?= $this->totalProductInBasket ?></span></div>
            </a>
        <?php endif ?>
    <?php endif ?>

    
    <?php if(!Session::exists(BUYER_SESSION_NAME) && !Session::exists(STORE_SESSION_NAME)): ?>
        <a href="<?=PROOT?>register">
            <div class="navBarRegister">
                <button class="btn">Sign up</button>
            </div>
        </a>
    <?php endif ?>

    <?php if(!Session::exists(BUYER_SESSION_NAME) && !Session::exists(STORE_SESSION_NAME)): ?>
        <a href="<?=PROOT?>login">
            <div class="navBarLogin">
                <button class="btn">Login</button>
            </div>
        </a>
    <?php endif ?>
</nav>