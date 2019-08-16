<div class="s-storeLogo">
    <img src="<?=PROOT?>public/images/storeLogos/<?= $this->store_logo ?>">
    <a href="<?=PROOT?><?= str_replace(' ', '-', $this->store_name) ?>"><?= $this->store_name ?></a>
</div>

<div class="s-columnOne-sections">
    <p class="s-columnOne-title">Edit Store</p>
    <div class="s-columnOne-subSections">
        <a href="<?=PROOT?>stores/admin/editLogo">Upload Logo</a>
        <a href="<?=PROOT?>stores/admin/editAbout">About Store</a>
        <a href="<?=PROOT?>stores/admin/editSocial">Add Social Page</a>
        <a href="<?=PROOT?>stores/admin/editContact">Edit Contact</a>
        <a href="<?=PROOT?>stores/admin/storeCategories">Add Categories</a>
        <a href="<?=PROOT?>stores/admin/editDelivery">Update Delivery and Return Policy</a>
    </div>
</div>

<div class="s-columnOne-sections">
    <p class="s-storeVisit-title">Store visit for the last 7 days</p>
    <div class="s-storeVisit">
        <div class="s-storeVisit-barWrapper">
            <?php 
                $totalVisit = 0;
                foreach($this->store_visit as $k => $v) {
                    $totalVisit += $v->visit_count;
                }
            ?>
            <?php foreach($this->store_visit as $k => $v): ?>
                <div class="s-storeVisit-bar"  
                     style="height: <?= $v->visit_count/$totalVisit * 145 ?>px; left: <?= ($k * 14) ?>%">
                    <span><?= $v->visit_count ?></span>
                </div>
            <?php endforeach ?>
        </div>

        <div class="s-storeVisit-axis"></div>


        <?php foreach($this->store_visit as $k => $v): ?>
            <div class="s-storeVisit-day">
                <?= $v->visit_day ?> <span><?= explode('-', $v->visited_at)[2] ?></span>
                <?= date('M', strtotime($v->visited_at)) ?>
            </div>
            
        <?php endforeach ?>
    </div>
    
    <div class="s-columnOne-subSections">
        <span title="<?= $this->store_baskets ?> products is currently in buyers' basket">
            Total carted products : <b><?= $this->store_baskets ?></b>
        </span>
        <span title="Your store is bookmarked by <?= $this->store_bookmarks ?> buyers">
            Bookmarks : <b><?= $this->store_bookmarks ?></b>
        </span>
        <span>Store rating : <b><?= $this->store_rating ?></b></span>
    </div>
</div>

<a href="<?=PROOT?>logout" class="s-logoutBtn">Logout</a>