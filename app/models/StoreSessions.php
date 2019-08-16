<?php 
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;

    class StoreSessions extends Model {

        public function __construct() {
            $table = 'store_sessions';
            parent::__construct($table);
        }

        /*****
         * Checks if the cookie name exists on the store owner's device and
         * if the cookie value exists in the database.
         * 
         * Returns the result to the Stores Model.
         */
        public static function getStoreCookie() {
            $storeSessionModel = new self();
            if(Cookie::exists(STORE_COOKIE_NAME)) {
                $storeSession = $storeSessionModel->findFirst(['conditions'=>'user_agent = ? AND session = ?', 
                                        'bind'=>[Session::getUserAgent(), Cookie::get(STORE_COOKIE_NAME)]]);
                if(!$storeSession) return false;
                return $storeSession;
            }
        }

    }

?>