<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class Orders extends Model {
        public $order_id, $product_id, $store_id, $buyer_id, $quantity, $created_at;

        public function __construct() {
            parent::__construct('orders');
        }

        public function sendOrder($store_id, $product_id) {
            return $this->insert([
                'product_id' => $product_id,
                'store_id' => $store_id,
                'buyer_id' => Session::get(BUYER_SESSION_NAME),
                'buyer_name' => Session::get(BUYER_NAME),
                'quantity' => $this->quantity,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        public function countSentOrder() {
            $buyer_id = Session::get(BUYER_SESSION_NAME);
            $result = $this->query("SELECT count(buyer_id) as total FROM orders WHERE buyer_id = ?", [$buyer_id]);
            return $result->getResult()[0]->total;
        }

        public function newOrders($store_id) {
            $results = $this->query("SELECT count(store_id) AS orders FROM orders WHERE store_id = ? AND viewed = 0", [$store_id]);
            return $result = round($results->getResult()[0]->orders, 1);
        }

        public function fetchOrders() {
            $store_id = Session::get(STORE_SESSION_NAME);
            $this->update('store_id', $store_id, ['viewed' => 1]);

            $sql = "SELECT orders.order_id, orders.buyer_id, orders.product_id, orders.viewed, orders.store_id,
            orders.quantity, orders.created_at, orders.buyer_name, products.product_cat, products.product_sub_cat,
            products.product_price, products.product_name 
            FROM orders
            LEFT JOIN products
            ON orders.product_id = products.product_id
            WHERE orders.store_id = ? AND orders.deleted <> 1";
            return $this->query($sql, [$store_id])->getResult();
        }

        public function fetchBuyerOrders() {
            $buyer_id = Session::get(BUYER_SESSION_NAME);

            $sql = "SELECT orders.order_id, orders.buyer_id, orders.product_id, orders.viewed, orders.store_id,
            orders.quantity, orders.created_at, orders.buyer_name, products.product_cat, products.product_sub_cat,
            products.product_price, products.product_name 
            FROM orders
            LEFT JOIN products
            ON orders.product_id = products.product_id
            WHERE orders.buyer_id = ?";
            return $this->query($sql, $buyer_id)->getResult();
        }

        public function cancelOrder($order_id) {
            if(Session::exists(BUYER_SESSION_NAME)) {
                $buyer_id = Session::get(BUYER_SESSION_NAME);
                $this->query("DELETE FROM orders WHERE order_id = ? AND buyer_id = ?", [$order_id, $buyer_id]);
                return true;
            }
        }

        public function deleteOrder($params2) {
            $store_id = Session::get(STORE_SESSION_NAME);
            $this->query("UPDATE orders SET deleted = 1 WHERE order_id = ? AND store_id = ?", [$params2, $store_id]);
            return true;
        }

    }
?>