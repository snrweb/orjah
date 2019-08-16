<?php 
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;

    class BuyerSessions extends Model {

        public function __construct() {
            $table = 'buyer_sessions';
            parent::__construct($table);
        }

        /*****
         * Checks if the cookie name exists on the buyers device and
         * if the cookie value in the database.
         * 
         * Returns the result to the Buyers model
         */
        public static function getBuyerCookie() {
            if(Cookie::exists(BUYER_COOKIE_NAME)) {
                $buyerSessionModel = new self();
                $buyerSession = $buyerSessionModel->findFirst(['conditions'=>'user_agent = ? AND session = ?', 
                                        'bind'=>[Session::getUserAgent(), Cookie::get(BUYER_COOKIE_NAME)]]);
                if(!$buyerSession) return false;
                return $buyerSession;
            }
        }
    }

?>