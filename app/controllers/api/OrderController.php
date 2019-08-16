<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;

    class OrderController extends Controller{
        private $store_name;
        public function __construct($controller, $action) {
            $this->APIheaders();
            parent::__construct($controller, $action);
            $this->loadModel('Orders');
        }

        public function indexAction() {
            if(Session::exists(BUYER_SESSION_NAME)) {
                $orders = $this->OrdersModel->fetchBuyerOrders();
                echo json_encode(["orders" => $orders]);
            }
        }

        public function cancelAction($order_id) {
            if(Session::exists(BUYER_SESSION_NAME)) {
                if($this->OrdersModel->cancelOrder($order_id)) {
                    echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }
        
    }

?>