<?php 
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;
    use App\Models\BuyerSessions;

    class Buyers extends Model {
        private static $isLoggedIn;
        public $email, $password, $confirm_password, $pwd_retrieve, $buyer_id, $buyer_name, $deleted = 0;

        /***
         * Calls the parent construct function and pass the
         * @table name to be used by the parent's methods
         */
        public function __construct($buyer = '') {
            $table = 'buyers';
            parent::__construct($table);
            
            if($buyer != '') {
                if(is_int($buyer)) {
                    $b = $this->db->findFirst('buyers', ['conditions'=>'buyer_id = ?', 'bind'=>[$buyer]]);
                    if($b) {
                        foreach($b as $key => $value) {
                            $this->$key = $value;
                        }
                    }
                }
            }
        }

        /*****
         * This login function is called from the Login controller when
         * the user/buyer login form is submitted
         * 
         * @var $userAgent stores the browser details
         * @var $hash creates a random alphanumeric characters
         */
        public function login($rememberMe = false) {
            //check to see if this buyer has logged in as a store owner.
            //if true, any stored cookie for the store will be deleted
            if(Cookie::exists(STORE_COOKIE_NAME)) Cookie::delete(STORE_COOKIE_NAME);

            Session::set(BUYER_SESSION_NAME, $this->buyer_id);
            Session::set(BUYER_NAME, $this->buyer_name);
            Session::set('type', 'buyer'); //tells us if the current user is a buyer or seller
            if($rememberMe) {
                $hash = md5(uniqid() + random_int(100, 100000));
                
                $userAgent = Session::getUserAgent();
                Cookie::set(BUYER_COOKIE_NAME, $hash, USER_COOKIE_EXPIRY);
                $fields = ['session'=>$hash, 'user_agent'=>$userAgent, 'buyer_id'=>$this->buyer_id];
                $this->db->query("DELETE FROM buyer_sessions WHERE user_agent = ? AND buyer_id = ? ", [$userAgent, $this->buyer_id]);
                $this->db->insert('buyer_sessions', $fields);
            }
        }

        /***
         * Checks if the cookie exists,
         * Login the buyer if true
         */
        public static function getCookieForLogin() {
            $buyer = BuyerSessions::getBuyerCookie();
            if($buyer->buyer_id !='') {
                $buyer = new self((int)$buyer->buyer_id);
                if($buyer) $buyer->login();
            }
            self::$isLoggedIn = true;
            return $buyer;
        }

        /***
         * Checks if the user is logged in
         */
        public static function isloggedIn() {
            if(Session::exists(BUYER_SESSION_NAME)) {
                return self::$isLoggedIn = true;
            } else {
                return self::$isLoggedIn = false;
            }
        }

        /***
         * This function unset cookies and sessions
         */
        public function logout() {
            $userAgent = Session::getUserAgent();
            $this->db->query("DELETE FROM buyer_sessions WHERE user_agent = ? AND buyer_id = ? ", 
                            [$userAgent, Session::get(BUYER_SESSION_NAME)]);
            Session::delete(BUYER_SESSION_NAME, 'type');
            if(Cookie::exists(BUYER_COOKIE_NAME)) {
                Cookie::delete(BUYER_COOKIE_NAME);
            }
            self::$isLoggedIn = false;
            return true;
        }

        /****
         * Select all buyer details using email
         */
        public function findByEmail() {
            return $this->findFirst(['conditions'=>'email = ?', 'bind'=>[$this->email]]);
        }

        public function validateRegistration() {
            return [
                'buyer_name' => ['display' => 'Name', 'required' => true, 'max' => 50, 'letters' => true],
                'email' => ['display' => 'Email', 'required' => true, 
                            'isUniqueBuyer' => $this->email, 'email' => true],
                'password' => ['display' => 'Password', 'min' => 8, 'required' => true]
            ];
        }

        //This function registers a buyer
        public function register() {
            $this->insert([
                'buyer_name' => $this->buyer_name,
                'email' => $this->email,
                'password' => $this->password
            ]);

            return true;
        }

        public function updateRetrieveCode($code) {
            return $this->update('email', "'$this->email'", [
                'pwd_retrieve' => $code
            ]);
        }

        public function resetPassword() {
            return $this->update('email', "'$this->email'", [
                'pwd_retrieve' => $this->pwd_retrieve,
                'password' => $this->password
            ]);
        }

    }

?>