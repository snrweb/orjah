<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;
    use Core\Resize;
    use App\Models\StoreSessions;

    class Stores extends Model {
        private static $isLoggedIn;

        /**
         * List of all table column names
         */
        public $store_id, $store_name, $store_email, $store_category, $store_country, 
         $confirm_password, $csrf_token, $store_pwd_retrieve;

        public function __construct($store = '') {
            $table = 'stores';
            parent::__construct($table);
            
            if($store != '') {
                if(is_int($store)) {
                    $b = $this->db->findFirst('stores', ['conditions'=>'store_id = ?', 'bind'=>[$store]]);
                    if($b) {
                        foreach($b as $key => $value) {
                            $this->$key = $value;
                        }
                    }
                }
            }
        }

        public function validateRegistration() {
            return [
                'store_email' => ['display' => 'Email', 'required' => true, 'email' => true],
                'store_name' => ['display' => 'Store name', 'required' => true, 
                                 'isUniqueStoreName' => $this->store_name, 'max' => 20],
                'store_category' => ['display' => 'a category', 'select' => 'Select category']
            ];
        }

        /**Registers new user */
        public function register() {

            return $this->insert([
                'store_email' => $this->store_email,
                'store_name' => $this->store_name,
                'store_country' => $this->store_country,
                'store_category' => $this->store_category,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
        }

        /**
         * Login user method.
         * 
         * if rememberMe is false, the user cookie is not stored, 
         * Cookie detail is not saved in the database for cookie login
         */
        public function login($rememberMe = false) {
            //check to see if this store owner has logged in as a buyer.
            //if true, any stored cookie for the buyer will be deleted
            if(Cookie::exists(BUYER_COOKIE_NAME)) Cookie::delete(BUYER_COOKIE_NAME);

            Session::set(STORE_SESSION_NAME, $this->store_id);
            Session::set('type', 'store');
            if($rememberMe) {
                $hash = md5(uniqid() + random_int(100, 100000));
                $userAgent = Session::getUserAgent();
                Cookie::set(STORE_COOKIE_NAME, $hash, USER_COOKIE_EXPIRY);
                $fields = ['session'=>$hash, 'user_agent'=>$userAgent, 'store_id'=>$this->store_id];
                $this->db->query("DELETE FROM store_sessions WHERE user_agent = ? AND store_id = ? ", 
                                [$userAgent, $this->store_id]);
                $this->db->insert('store_sessions', $fields);
            }
        }

        /***
         * Checks if the cookie exists,
         * Login the store owner if true
         */
        public static function getCookieForLogin() {
            $storeSession = StoreSessions::getStoreCookie();
            if($storeSession) {
                $store = new self((int)$storeSession->store_id);
                if($store) {
                    $store->login();
                }
                self::$isLoggedIn = true;
            }
        }

        /**Checks if the user is logged in */
        public static function isloggedIn() {
            if(Session::exists(STORE_SESSION_NAME)) {
                return self::$isLoggedIn = true;
            } else {
                return self::$isLoggedIn = false;
            }
        }

        /**Logout any user and delete any stored cookie */
        public function logout() {
            $userAgent = Session::getUserAgent();
            $this->db->query("DELETE FROM store_sessions WHERE user_agent = ? AND store_id = ? ", 
                            [$userAgent, Session::get(STORE_SESSION_NAME)]);
            Session::delete(STORE_SESSION_NAME, 'type');
            if(Cookie::exists(STORE_COOKIE_NAME)) {
                Cookie::delete(STORE_COOKIE_NAME);
            }
            self::$isLoggedIn = false;
            return true;
        }

        public function validateDetails() {
            return [
                'store_email' => ['display' => 'Email', 'required' => true, 'email' => true],
                'store_name' => ['display' => 'Store name', 'required' => true, 'max' => 50, 
                                'isUniqueStoreNameById' => $this->store_name],
                'store_category' => ['display' => 'a category', 'select' => 'Select category'],
            ];
        }

        public function updateDetails() {
            return $this->update('store_id', Session::get(STORE_SESSION_NAME), [
                'store_email' => $this->store_email,
                'store_name' => $this->store_name,
                'store_country' => $this->store_country,
                'store_category' => $this->store_category,
                'store_phone' => $this->store_phone
                ]
            );
        }

        public function updateSocialLinks() {
            return $this->update('store_id', Session::get(STORE_SESSION_NAME), [
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'instagram' => $this->instagram
                ]
            );
        }

        public function addCategory() {
            return $this->update('store_id', Session::get(STORE_SESSION_NAME), 
                                    [
                                        'category_one' => $this->category_one,
                                        'category_two' => $this->category_two,
                                        'category_three' => $this->category_three,
                                        'category_four' => $this->category_four
                                    ]);
        }


        public function updateAbout() {
            return $this->update('store_id', Session::get(STORE_SESSION_NAME), [
                'store_about' => $this->store_about
                ]
            );
        }

        public function updateDeliveyReturn() {
            return $this->update('store_id', Session::get(STORE_SESSION_NAME), [
                'delivery_terms' => $this->delivery_terms,
                'return_policy' => $this->return_policy
                ]
            );
        }

        public function updateLogo($oldLogo) {
            $resize = new Resize();
            
            $image = $_FILES["store_logo"]["name"];
            $ext = pathinfo($_FILES["store_logo"]["name"], PATHINFO_EXTENSION);
            $image = time().random_int(100, 1000000000).".".$ext;

            $update = $this->update('store_id', Session::get(STORE_SESSION_NAME), ['store_logo' => $image]);

            if($update) {
                //The old logo is deleted if it exists
                if(!empty($oldLogo)) unlink(ROOT . DS . 'public' . DS . 'images' . DS . 'storeLogos' . DS . $oldLogo);

                $resize::changeSize(//temporary image image location
                    $_FILES["store_logo"]["tmp_name"], 
                    //location to upload resized image
                    ROOT . DS . 'public' . DS . 'images' . DS . 'storeLogos' . DS . $image,
                    //Maximum width of the new resized image
                    400, 
                    //Maximum height of the new resized image
                    300,
                    //File extension of the new resized image
                    $ext,
                    //Quality of the image
                    85 );
                return true;
            }
            return false;
        }

        public function updateCoverPhoto($oldCover) {
            $resize = new Resize();
            
            $image = $_FILES["store_coverPhoto"]["name"];
            if(!empty($image)) {
                $ext = pathinfo($_FILES["store_coverPhoto"]["name"], PATHINFO_EXTENSION);
                $image = time().random_int(100, 1000000000).".".$ext;
            }
            $update = $this->update('store_id', Session::get(STORE_SESSION_NAME), ['store_coverPhoto' => $image]);
            if($update) {
                //The old logo is deleted if it exists
                if(!empty($oldCover)) unlink(ROOT . DS . 'public' . DS . 'images' . DS . 'storeCoverPhoto' . DS . $oldCover);

                $resize::changeSize(//temporary image image location
                    $_FILES["store_coverPhoto"]["tmp_name"], 
                    //location to upload resized image
                    ROOT . DS . 'public' . DS . 'images' . DS . 'storeCoverPhoto' . DS . $image,
                    //Maximum width of the new resized image
                    1000, 
                    //Maximum height of the new resized image
                    450,
                    //File extension of the new resized image
                    $ext,
                    //Quality of the image
                    90 );
                return true;
            }
            return false;
        }

        public function findAllStores() {
            return $this->query("SELECT store_id, store_name, store_phone, store_email, store_rating, store_category,
                                        store_street, store_city, store_country, store_about, deleted
                                    FROM stores")->getResult();
        }

        public function findStoresByCategory($category) {
            return $this->query("SELECT store_id, store_name, store_phone, store_email, store_rating, store_category,
                                        store_street, store_city, store_country, store_about, deleted
                                    FROM stores 
                                    WHERE store_category = ?", ["$category"])->getResult();
        }

        public function findStore() {
            return $this->findFirst(['conditions'=>'store_name = ?', 'bind'=>[$this->store_name]]);
        }

        public function storeBasket($store_id) {
            $results = $this->query("SELECT count(store_id) AS baskets FROM baskets WHERE store_id = ?", [$store_id]);
            return $result = round($results->getResult()[0]->baskets, 1);
        } 

        public function searchStore($params) {
            if(is_numeric($params)) {
                return $this->query("SELECT store_id, store_name, store_phone, store_email, store_rating, store_category,
                                 store_street, store_city, store_country, store_about, deleted 
                          FROM stores 
                          WHERE store_id LIKE '%".$params."%' LIMIT 10")->getResult();
            } else {
                return $this->query("SELECT store_id, store_name, store_phone, store_email, store_rating, store_category,
                                 store_street, store_city, store_country, store_about, deleted 
                          FROM stores 
                          WHERE store_name LIKE '%".$params."%' LIMIT 10")->getResult();
            }
        }

        public function updateStoreRating($store_id, $newProductRating) {
            $sql = "SELECT store_rating, store_rating_count FROM stores WHERE store_id = ?";
            $results = $this->query($sql, [$store_id])->getResult();
            $storeRating; $storeRatingCount;
            foreach($results as $result) {
                $storeRating = $result->store_rating;
                $storeRatingCount = $result->store_rating_count;
            }

            $totalRating = (($storeRating * $storeRatingCount) + $newProductRating) / ($storeRatingCount + 1);
            
            $this->update('store_id', $store_id, [
                'store_rating' => $totalRating,
                'store_rating_count' => $storeRatingCount + 1
            ]);
        }

        public function updateRetrieveCode($code) {
            return $this->update('store_name', "'$this->store_name'", [
                'store_pwd_retrieve' => $code
            ]);
        }
        
        public function resetPassword() {
            return $this->update('store_name', "'$this->store_name'", [
                'store_pwd_retrieve' => $this->store_pwd_retrieve,
                'store_password' => $this->store_password
            ]);
        }
        
    }

?>