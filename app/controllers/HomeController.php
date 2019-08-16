<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Categories;
    use Core\Session;

    class HomeController extends Controller {
        
        /********************
         * Call the extended controller construct to 
         * instatiate the view object
         */
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);

            $this->loadModel('Admin');
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->loadModel('Messages');
        }

        /***********
         * The default action if no action is provided
         */
        public function indexAction() {
            $this->view->categories = Categories::category();

            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $this->view->totalOrders = $this->OrdersModel->countSentOrder();
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
            
            $this->view->render('home/index');
        }
    }
?>