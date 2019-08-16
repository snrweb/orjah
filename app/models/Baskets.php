<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;

    class Baskets extends Model {
        public $basket_id, $product_id, $store_id, $buyer_cookie_name, $created_at, $csrf_token;

        public function __construct() {
            parent::__construct('baskets');
        }

        public function addToBasket($store_id, $product_id, $hash) {
            $buyer_id = (Session::exists(BUYER_SESSION_NAME)) ? Session::get(BUYER_SESSION_NAME) : 0;
            $id = (!Cookie::get(USER_COOKIE_NAME)) ? $hash : Cookie::get(USER_COOKIE_NAME);
            return $this->insert([
                'product_id' => $product_id,
                'store_id' => $store_id,
                'buyer_id' => $buyer_id,
                'buyer_cookie_name' => $id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        public function countProductInBasket() {
            $buyer_id = (Session::exists(BUYER_SESSION_NAME)) ? Session::get(BUYER_SESSION_NAME) : 0;
            $id = (Cookie::exists(USER_COOKIE_NAME)) ? Cookie::get(USER_COOKIE_NAME) : 0;
            if($buyer_id != 0) {
                $result = $this->query("SELECT count(buyer_id) AS basketNum FROM baskets WHERE buyer_id = ?", [$buyer_id]);
                return $result->getResult()[0]->basketNum;
            } elseif($id != 0) {
                return count(explode(" ", Cookie::get('basket'))); 
            }
        }

        public function checkProductInBasket($product_id) {
            $buyer_id = (Session::exists(BUYER_SESSION_NAME)) ? Session::get(BUYER_SESSION_NAME) : 0;
            $id = (Cookie::exists(USER_COOKIE_NAME)) ? Cookie::get(USER_COOKIE_NAME) : 0;
            if($buyer_id != 0) {
                $result = $this->query("SELECT product_id FROM baskets WHERE buyer_id = ? AND product_id = ?", [$buyer_id, $product_id]);
                if(!empty($result->getResult())) {
                    return true;
                }
                return false;
            } elseif($id != 0) {
                $product_ids = explode(" ", Cookie::get('basket'));
                if(in_array($product_id, $product_ids)) {
                    return true;
                } 
                return false;
            }
        }

        public function getBasket() {
            $buyer_id = Session::get(BUYER_SESSION_NAME);
            $sql = "SELECT baskets.basket_id, baskets.buyer_id, baskets.product_id, baskets.store_id,
            baskets.created_at, stores.store_name, products.product_price, products.product_name,
            products.product_image_one
            FROM baskets
            LEFT JOIN products
            ON baskets.product_id = products.product_id
            LEFT JOIN stores
            ON baskets.store_id = stores.store_id
            WHERE baskets.buyer_id = ?";
            return $this->query($sql, [$buyer_id])->getResult();
        }

        public function deleteFromBasket($basket_id) {
            $buyer_id = Session::get(BUYER_SESSION_NAME);
            $this->query("DELETE FROM baskets WHERE basket_id = ? AND buyer_id = ?", [$basket_id, $buyer_id]);
            return true;
        }
        
        public function deleteFromBasketP($product_id) {
            $buyer_id = Session::get(BUYER_SESSION_NAME);
            $this->query("DELETE FROM baskets WHERE product_id = ? AND buyer_id = ?", [$product_id, $buyer_id]);
            return true;
        }
    }
?>