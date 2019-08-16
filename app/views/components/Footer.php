<footer class="Footer">

    <div>
        <p>Buy</p>
        <p><a href="<?=PROOT?>register/buyer">Register</a></p>
        <p><a href="<?=PROOT?>login/buyer">Login</a></p>
    </div>

    <div>
        <p>Sell on Orjah</p>
        <p><a href="<?=PROOT?>register">Register</a></p>
        <p><a href="<?=PROOT?>login">Login</p></a>
    </div>

    <div>
        <p>Orjah</p>
        <p><a href="<?=PROOT?>ta/terms">Terms and Condition</a></p>
        <p><a href="<?=PROOT?>ta/about">About</a></p>
    </div>

    <div class="footer-social">
        <p>Stay Connected</p>
        <p class="footer-social-fb">
            <a href="">
                <button class="a-FacebookPage btn">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/facebook.svg') ?>
                </button>
            </a> facebook
        </p>

        <p class="footer-social-tw">
            <a href=""><button class="d-TwitterPage btn">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/twitter.svg') ?>
                </button>
            </a>twitter
        </p>

        <p class="footer-social-int">
            <a href=""><button class="d-InstagramPage btn">
                    <?php echo file_get_contents(ROOT.'/public/images/svg/instagram.svg') ?>
                </button>
            </a>instagram
        </p>
        <p><a href="<?=PROOT?>message/contactAdmin">Contact Us</a></p>
    </div>

    <p class="clear-float"></p>

    <a href="<?=PROOT?>">
        <p style="text-align: center; color: white; font-size: 15px;">Orjah &copy 2019</p>
    </a>

</footer>
