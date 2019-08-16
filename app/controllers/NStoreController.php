<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;

    class NStoreController extends Controller {
        public $store_name, $store_id;

        public function __construct($controller, $action) {

            parent::__construct($controller, $action);
            $this->loadModel('Stores');
            $this->loadModel('Products');
            $this->loadModel('Bookmarks');
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->loadModel('Messages');
            $this->loadModel('StoreVisit');

            $this->store_name = $storeName = str_replace('-', ' ', $controller);
            $this->findAllStore($storeName);
        }

        private function findAllStore($params) {
            $s;
            $this->view->bookmark = false;
            if(is_numeric($params)) {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_id = ?', 'bind'=>[$params]]);
            } else {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_name = ?', 'bind'=>[$params]]);   
            }
            $this->store_id = $s->store_id;
           
            $this->assignToView($s);
            $this->fetchStoreProducts($s->store_id);

            $this->view->store_rating = $this->view->rate($s->store_rating).' '.$s->store_rating;
            $this->view->totalProductInBasket = '';
            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $this->view->totalOrders = $this->OrdersModel->countSentOrder();
                $this->view->bookmark = $this->BookmarksModel->checkBookmarked($s->store_id);
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
            
            $this->view->setLayout('store');
            $this->StoreVisitModel->timeOfStoreVisit($s->store_id);
        }

        public function findStoreAction($params) {
            $s;
            if(is_numeric($params)) {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_id = ?', 'bind'=>[$params]]);
            } else {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_name = ?', 'bind'=>[$params]]);   
            }

            $this->view->store_rating = $this->view->rate($s->store_rating).' '.$s->store_rating;
            $this->view->totalProductInBasket = '';
            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $this->view->totalOrders = $this->OrdersModel->countSentOrder();
                $this->view->bookmark = $this->BookmarksModel->checkBookmarked($s->store_id);
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
            
        }

        private function fetchStoreProducts($store_id) {
            $products = $this->ProductsModel->find(['conditions' => 'store_id = ?', 
                                                    'bind' => [$store_id]
                                                   ]);
                                                  
            $store_menus = []; 
            foreach($products as $p) {
                if(!in_array($p->store_menu, $store_menus)) {
                    array_push($store_menus, $p->store_menu);
                }
            }
                                                    
            $this->view->store_menus = $store_menus;    
            $this->view->products = $products;
        }

        public function indexAction() {
            $this->view->urlParams = 'product';
            $this->view->render('store/storeView');
        }

        public function bookmarkStoreAction($params, $store_id) {
            if(Session::exists(BUYER_SESSION_NAME)) {
                if($params === 'add') {
                    $this->BookmarksModel->bookmarkStore($store_id);
                } elseif($params === 'remove') {
                    $this->BookmarksModel->deleteBookmark($store_id);
                }
            } else {
                Router::redirect(str_replace(' ', '-', $this->store_name).'/error');
            }
            Router::redirect(str_replace(' ', '-', $this->store_name));
        }

        public function aboutAction() {
            $this->view->urlParams = 'about';
            $this->view->render('store/storeView');
        }

        public function contactAction() {
            $this->view->message_error = '';
            $this->view->urlParams = 'contact';
            if($_POST) {
                $message = new MessageController('MessageController', 'contactAction');
                if($message->contactAction($this->store_id)) {
                    $this->view->success = 'Message sent successfully';
                }
            }
            $this->view->render('store/storeView');
        }

        public function deliveryAction() {
            $this->view->urlParams = 'delivery';
            $this->view->render('store/storeView');
        }

        public function returnAction() {
            $this->view->urlParams = 'return';
            $this->view->render('store/storeView');
        }

    }
?>