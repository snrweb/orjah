<?php $this->setSiteTitle('Orjah - Home') ?>

<?php $this->start('body') ?>

<section class="container">
  <div class="Body-Slider">
    <?php $this->gComponents('Slider') ?>
  </div>

  <?php $this->gComponents('BodyCategories') ?>

  <?php //$this->gComponents('ProductCardOne') ?>

  <div>
    <?php //$this->gComponents('ProductCardTwo') ?>
  </div>
</section>

<?php $this->gComponents('Footer') ?>


<?php $this->end() ?>
