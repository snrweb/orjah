
<?php $this->setSiteTitle('Orders') ?>

<?php $this->start('body') ?>

<div class="s-ordersWrapper-buyer">

    <?php if(count($this->orders) == 0) { ?>
        <p style="text-align: center; border: 1px dashed #ccc; font-size: 25px;
                    margin: 80px auto 100px; padding: 40px 10px;">Order list Empty</p>
    <?php } else { ?>
        
        <h3>List Of Orders</h3>
        <?= $this->successMsg(); ?>
 
        <?php foreach($this->orders as $o) : ?>
            <div class="s-orders">
                <div class="s-order">
                    <p>Order ID: <small><?= $o->order_id ?> </small> 
                                <small> | </small> 
                                <small><?= timeFormatter($o->created_at) ?> ago</small></p>
                    <div class="s-orderSection">
                        <div>Product Name</div>
                        <div><?= $o->product_name ?></div>
                        <div class="clear-float"></div>
                    </div>
                    
                    <div class="s-orderSection">
                        <div>Category</div>
                        <div><?= $o->product_cat ?>, <?= $o->product_sub_cat ?></div>
                        <div class="clear-float"></div>
                    </div>

                    <div class="s-orderSection">
                        <div>Quantity</div>
                        <div><?= $o->quantity ?></div>
                        <div class="clear-float"></div>
                    </div>

                    <div class="s-orderSection">
                        <div>Buyer Name</div>
                        <div><?= $o->buyer_name ?></div>
                        <div class="clear-float"></div>
                    </div>
                </div>
                <div class="clear-float"></div>
                <a href="<?=PROOT?>order/cancel/<?=  $o->order_id ?>">
                    <button class="btn s-deleteOrder">Cancel Order</button>
                </a>
            </div>
            
        <?php endforeach ?>
    <?php } ?>
</div>


<?php if(count($this->orders) > 2) { ?>
    <style> footer { margin-top: 15%; } </style>
<?php } else { ?>
    <style> footer { margin-top: 25%; } </style>
<?php } ?>


<?php $this->end() ?>