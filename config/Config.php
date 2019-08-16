<?php 

    define('DEBUG', true); //controls error reporting

    define('DBHOST', '127.0.0.1');
    define('DBNAME', 'dbname');
    define('DBUSER', 'root');
    define('DBPASSWORD', '');
    
    define('DEFAULT_CONTROLLER', 'Home'); //default controller if no controller is set
    define('DEFAULT_LAYOUT', 'default'); //default layout if no layout is specified

    define('PROOT', '/orjah/'); //set this to '/' during production

    define('SITE_TITLE', 'The real market place'); //the default site title if the site title is not set

    define('BUYER_SESSION_NAME', 'djoiei87654e4tgko48478esldlsl3losi3');
    define('BUYER_NAME', 'ddh83j3j38987shskekfoiwlklass');
    define('ADMIN_SESSION_NAME', 'SLKJskjfslakhiwehnkj393l2nak3j2190j23');
    define('STORE_SESSION_NAME', '98dfje0sjasdnknkaeeediw00dfjaoijfa');
    define('BUYER_COOKIE_NAME', 'ljioelslaljwi9032dfsh388nmd3ls3rw35');
    define('STORE_COOKIE_NAME', 'kdf939u83lkdkahhh23dl23jlsf3f7ys');
    define('VISIT_COOKIE_NAME', 'kdf939u83lljslsdokf979kd38u73a6d5we');
    define('USER_COOKIE_EXPIRY', time() + (86400 * 30));

    //This is for users that are not logged in and are adding products to basket
    define('USER_COOKIE_NAME', '84ii0kjdjlsad88gfa9fq3');
?>