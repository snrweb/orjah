<div class="d-ColumnOne pull-left">
    <div class="d-ProductImgLists pull-left">
        <span style="background-image: 
                                url(<?=PROOT?>public/images/products/<?= $this->product_image_one ?>);" 
                id="d-ProductImgLists-1"></span>

        <?php if(!empty($this->product_image_two)): ?>
        <span style="background-image: 
                                url(<?=PROOT?>public/images/products/<?= $this->product_image_two ?>);" 
                id="d-ProductImgLists-2"></span>
        <?php endif ?>

        <?php if(!empty($this->product_image_three)): ?>
        <span style="background-image: 
                                url(<?=PROOT?>public/images/products/<?= $this->product_image_three ?>);" 
                id="d-ProductImgLists-3"></span>
        <?php endif ?>
    </div>
    
    <div class="d-ProductMainImg pull-left" id="d-ProductMainImg">
        <img src="<?=PROOT?>public/images/products/<?= $this->product_image_one ?>" alt="<?= $this->product_name ?>">
        
        <?php if(!empty($this->product_image_two)): ?>
            <img src="<?=PROOT?>public/images/products/<?= $this->product_image_two ?>" alt="<?= $this->product_name ?>">
        <?php endif ?>
        
        <?php if(!empty($this->product_image_three)): ?>
            <img src="<?=PROOT?>public/images/products/<?= $this->product_image_three ?>" alt="<?= $this->product_name ?>">
        <?php endif ?>
    </div>
    <div class="clear-float"></div>
    
    <div class="d-ProductImgLists-h">
        <span style="background-image: 
                                url(<?=PROOT?>public/images/products/<?= $this->product_image_one ?>);" 
                    id="d-ProductImgLists-h1"></span>
         
        <?php if(!empty($this->product_image_two)): ?>                       
        <span style="background-image: 
                                url(<?=PROOT?>public/images/products/<?= $this->product_image_two ?>);" 
                id="d-ProductImgLists-h2"></span>
        <?php endif ?>

        <?php if(!empty($this->product_image_three)): ?>
        <span style="background-image: 
                                url(<?=PROOT?>public/images/products/<?= $this->product_image_three ?>);" 
                id="d-ProductImgLists-h3"></span>
        <?php endif ?>
    </div>
</div>