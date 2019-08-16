<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;

    class OrderController extends Controller{
        private $store_name;
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Messages');
            $this->loadModel('Products');
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->view->setLayout('store');

            $this->findUser();
        }

        private function findUser() {
            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $this->view->totalOrders = $this->OrdersModel->countSentOrder();
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
            }
        }

        public function indexAction() {
            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->orders = $this->OrdersModel->fetchBuyerOrders();
                $this->view->render('orders/orders');
            }
        }

        public function cancelAction($order_id) {
            if(Session::exists(BUYER_SESSION_NAME)) {
                if($this->OrdersModel->cancelOrder($order_id)) {
                    Router::redirect('order');
                }
            }
        }
        
    }

?>