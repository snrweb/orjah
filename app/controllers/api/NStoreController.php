<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;

    class NStoreController extends Controller {
        public $store_name, $store_id, $data = [], $products = [], $store_menus = [];

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->APIheaders();

            $this->loadModel('Stores');
            $this->loadModel('Products');
            $this->loadModel('Bookmarks');
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->loadModel('Messages');
            $this->loadModel('StoreVisit');

            $this->store_name = $storeName = str_replace('-', ' ', $controller);
            //$this->findAllStore($storeName);

        }

        private function findAllStore($params, $fetch_product = false) {
            $s;
            $bookmark = false;
            if(is_numeric($params)) {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_id = ?', 'bind'=>[$params]]);
            } else {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_name = ?', 'bind'=>[$params]]);   
            }

            $store_rating = $s->store_rating;
            $newOrders = $bookmark = $msgCount = $totalOrders = $totalProductInBasket = 0;
            if(Session::exists(BUYER_SESSION_NAME)) {
                $totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $totalOrders = $this->OrdersModel->countSentOrder();
                $bookmark = $this->BookmarksModel->checkBookmarked($s->store_id);
                $msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $msgCount = $this->MessagesModel->unReadMessages();
                $newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
            
            $this->StoreVisitModel->timeOfStoreVisit($s->store_id);
            $this->store_id = $s->store_id;
            if($fetch_product) {
                $this->fetchStoreProducts($s->store_id);
            }

            $this->data = array(
                "storeDetails" => $s,
                "storeName" => $s->store_name,
                "storeRating" => $store_rating,
                "totalProductInBasket" => $totalProductInBasket,
                "totalOrders" => $totalOrders,
                "bookmark" => $bookmark,
                "msgCount" => $msgCount,
                "newOrders" => $newOrders,
                "products" => $this->products,
                "storeMenus" => $this->store_menus
            );

        }

        public function findStoreAction($params) {
            $s;
            if(is_numeric($params)) {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_id = ?', 'bind'=>[$params]]);
            } else {
                $s = $this->StoresModel->findFirst(['conditions'=>'store_name = ?', 'bind'=>[$params]]);   
            }

            $store_rating = $rate($s->store_rating).' '.$s->store_rating;
            $totalProductInBasket = '';
            if(Session::exists(BUYER_SESSION_NAME)) {
                $totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $totalOrders = $this->OrdersModel->countSentOrder();
                $bookmark = $this->BookmarksModel->checkBookmarked($s->store_id);
                $msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $msgCount = $this->MessagesModel->unReadMessages();
                $newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }

            $this->data = array(
                "storeDetails" => $s,
                "storeName" => $s->store_name
            );
            
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
                                  
            $this->products = $products;
            $this->store_menus = $store_menus;
        }

        public function indexAction() {
            $this->findAllStore($this->store_name, true);
            echo json_encode($this->data);
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
            echo json_encode($this->data);
        }

        public function contactAction() {
            $this->findAllStore($this->store_name, true);
            if($_POST) {
                $message = new MessageController('MessageController', 'contactAction');
                if($message->contactAction($this->store_id)) {
                    echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        public function deliveryAction() {
            echo json_encode($this->data);
        }

        public function returnAction() {
            echo json_encode($this->data);
        }

    }
?>