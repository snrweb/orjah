<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;

    class BasketController extends Controller{
        private $store_name;
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Messages');
            $this->loadModel('Products');
            $this->loadModel('Stores');
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
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
        }

        public function indexAction() {
            $this->view->basketItems = $this->BasketsModel->getBasket();
            $this->view->render('basket/basket');
        }

        public function removeAction($basket_id) {
            if($this->BasketsModel->deleteFromBasket($basket_id)) {
                Router::redirect('basket');
            }
        }
        
    }

?>