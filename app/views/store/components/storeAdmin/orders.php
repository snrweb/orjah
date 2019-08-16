<div class="s-ordersWrapper">
    

    <?php if(count($this->orders) == 0) { ?>
        <p style="text-align: center; border: 1px dashed #ccc; font-size: 25px;
                    margin: 80px auto 180px; padding: 40px 10px;">Order list Empty</p>
    <?php } else { ?>
        
        <h3>List Of Orders</h3>
        <?= $this->successMsg(); ?>

        <?php foreach($this->orders as $o) : ?>
            <div class="s-orders">
                <div class="pull-left s-order">
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
                        <div><?= $o->product_cat ?></div>
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
                <form class="pull-left" method="post" action="<?=PROOT?>stores/admin/orders">
                    <textarea rows="5" placeholder="Reply <?= $o->buyer_name ?>..." name="message"></textarea>
                    <input type="hidden" name="buyer_id" value="<?= $o->buyer_id ?>">
                    <input type="hidden" name="store_name" value="<?= $this->store_name ?>">
                    <button type="submit" class="btn" style="margin-top: 5px;">Send Reply</button>
                </form>
                <div class="clear-float"></div>
                <a href="<?=PROOT?>stores/admin/deleteOrder/<?=  $o->order_id ?>">
                    <button class="btn s-deleteOrder">Delete</button>
                </a>
            </div>
            
        <?php endforeach ?>
    <?php } ?>
</div>

<style>
    footer {
        margin-top: 17%;
    }
</style>