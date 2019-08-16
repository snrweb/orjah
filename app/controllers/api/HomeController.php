<?php 
    namespace App\Controllers\API;
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
            $this->APIheaders();

            $this->loadModel('Admin');
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->loadModel('Messages');
        }

        /***********
         * The default action if no action is provided
         */
        public function indexAction() {

            $totalOrders = $totalProductInBasket = $msgCount = $newOrders = 0;
            if(Session::exists(BUYER_SESSION_NAME)) {
                $totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $totalOrders = $this->OrdersModel->countSentOrder();
                $msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $msgCount = $this->MessagesModel->unReadMessages();
                $newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
            
            
            $data = array(
                "totalProductInBasket" => $totalProductInBasket,
                "totalOrders" => $totalOrders,
                "msgCount" => $msgCount,
                "newOrders" => $newOrders
            );

            echo json_encode($data);
        }
    }
?>