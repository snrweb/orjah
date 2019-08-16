<?php 
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Categories;
    use Core\Validation;
    use App\Controllers\MessageController;
    use App\Models\Products;

    class StoresController extends Controller {
        private $store_category, $store_name, $store_details, $store_rating, $store_baskets,
                $newOrders, $store_bookmarks, $store_visit, $oldCover, $oldLogo ;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->APIheaders();

            $this->loadModel('Stores');
            $this->loadModel('Products');
            $this->loadModel('Bookmarks');
            $this->loadModel('Messages');
            $this->loadModel('Orders');
            $this->loadModel('StoreVisit');
            
            if(Session::exists(STORE_SESSION_NAME)) {
                $this->store_details = $u = $this->StoresModel->findById('store_id', Session::get(STORE_SESSION_NAME));
                $this->store_category = $u->store_category;
                $this->store_name = $u->store_name;
                $this->oldCover = $u->store_coverPhoto;
                $this->oldLogo = $u->store_logo;

                $this->store_rating = $u->store_rating;
                $this->store_baskets = $store_baskets = $this->StoresModel->storeBasket(Session::get(STORE_SESSION_NAME));
                $this->newOrders = $newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
                $this->store_bookmarks = $store_bookmarks = $this->BookmarksModel->totalBookmark(Session::get(STORE_SESSION_NAME));
                $this->store_visit = $store_visit = $this->StoreVisitModel->lastSevenDayVisit(Session::get(STORE_SESSION_NAME));
            }
        }

        public function resetStateAction($params = "") {
            if($params != "") {
                $products = $this->ProductsModel->find(['conditions' => 'store_id = ?', 
                                                        'bind' => [Session::get(STORE_SESSION_NAME)]
                                                       ]);
            
                $data = array(
                    "storeName" => $this->store_details->store_name,
                    "store_details" => $this->store_details,
                    "products" => $products
                );
                
                echo json_encode($data);
                return;
            }
            
            $data = array(
                "store_details" => $this->store_details,
                "storeName" => $this->store_details->store_name
            );
            echo json_encode($data);
        }

        public function adminAction($params = '', $params2 = '') {
            $urlParams = 'admin';
            $categories = Categories::list();
            $mainCategoryAry = Categories::category();
            if (Session::exists(STORE_SESSION_NAME)) {
                $msgCount = $this->MessagesModel->unReadMessages();

                $products = $this->ProductsModel->find(['conditions' => 'store_id = ?', 
                                                        'bind' => [Session::get(STORE_SESSION_NAME)]
                                                       ]);
                                                       
                $store_menus = []; 
                foreach($products as $p) {
                    if(!in_array($p->store_menu, $store_menus)) {
                        array_push($store_menus, $p->store_menu);
                    }
                }

                $totalVisit = 0;
                foreach($this->store_visit as $v) {
                    $totalVisit += $v->visit_count;
                }
                                                        
                $store_menus = $store_menus;  

                if($params == '') {
                    $data = array(
                        "storeId" => Session::get(STORE_SESSION_NAME),
                        "categories" => $mainCategoryAry,
                        "store_name" => $this->store_name, 
                        "store_details" => $this->store_details, 
                        "store_rating" => $this->store_rating, 
                        "store_baskets" => $this->store_baskets,
                        "newOrders" => $this->newOrders, 
                        "msgCount" => $msgCount, 
                        "store_bookmarks" => $this->store_bookmarks, 
                        "store_visit" => $this->store_visit,
                        "totalVisit" => $totalVisit,
                        "store_menus" => $store_menus,  
                        "products" => $products
                    );
                    
                    echo json_encode($data);
                }

                switch($params) {
                    case 'editContact': 
                            $this->editContact();
                            break;
                    case 'orders': 
                            $this->orders();
                            break;
                    case 'deleteOrder': 
                            $this->deleteOrders($params2);
                            break;
                    case 'editAbout': 
                            $this->editAbout();
                            break;
                    case 'editDelivery': 
                            $this->editDelivery();
                            break;
                    case 'editSocial': 
                            $this->editSocial();
                            break;
                    case 'editLogo': 
                            $this->editLogo();
                            break;
                    case 'addCover': 
                            $this->addCoverPhoto();
                            break;
                    case 'addProduct': 
                            $this->addProduct('add');
                            break;
                    case 'modifyProduct': 
                            $this->modifyProduct('modify', $params2);
                            break;
                    case 'deleteProduct': 
                            $this->deleteProduct($params2);
                            break;
                    case 'storeCategories': 
                            $this->addCategory();
                            break;
                    case 'messages': 
                            $this->messages();
                            break;
                    case 'chats': 
                            $this->chats($params2);
                            break;
                    case 'sendChat': 
                            $this->sendChat($params2);
                            break;
                }
            }
        }

        private function addCategory() {
            if($_POST) {
                $validate = new Validation();
                $this->StoresModel->assign($_POST);

                $validate->check('$_POST', [
                    'category_one' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                    'category_two' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                    'category_three' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                    'category_four' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                ]);

                if($validate->passed()) {
                    $updated = $this->StoresModel->addCategory();
                    if($updated) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        private function orders() {
            $orders = $this->OrdersModel->fetchOrders();
            
            if($_POST) {
                $validate = new Validation();
                $validate->check('$_POST', [
                    'store_name' => ['display' => 'Store name', 'required' => true, 'isStoreName' => true],
                    'buyer_id' => ['display' => 'ID', 'required' => true, 'isNumeric' => true]
                ]);
                if($validate->passed()) {
                    $message = new MessageController('MessageController', 'contactAction');
                    if($message->contactAction($_POST['buyer_id'], $_POST['store_name'])) {
                        echo json_encode(true);
                    } else {
                        echo json_encode(false);
                    }
                    return;
                }
            }
            echo json_encode(["orders" => $orders]);
        }

        private function deleteOrders($params2) {
            if($this->OrdersModel->deleteOrder($params2)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }

        private function editContact() {
            if($_POST) {
                $validate = new Validation();
                $this->StoresModel->assign($_POST);

                $validate->check('$_POST', $this->StoresModel->validateDetails());
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateDetails(); 
                    if($updated) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        private function editSocial() {
            if($_POST) {
                $this->StoresModel->assign($_POST);
                
                $validate = new Validation();
                $validate->check('$_POST', [
                    'facebook' => ['display' => 'Facebook link', 'url' => true],
                    'twitter' => ['display' => 'twitter link', 'url' => true],
                    'instagram' => ['display' => 'instagram link', 'url' => true]
                ]);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateSocialLinks(); 
                    if($updated) echo json_encode(true);
                    
                } else {
                    echo json_encode(false);
                }
            }    
        }

        private function editAbout() {

            if($_POST) {
                $this->StoresModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', [
                    'store_about' => ['display' => 'About store', 'max' => 1000]
                ]);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateAbout(); 
                    if($updated) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        private function editDelivery() {
            if($_POST) {
                $this->StoresModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', [
                    'delivery_terms' => ['display' => 'Delivery terms', 'max' => 1000],
                    'return_policy' => ['display' => 'Return olicy', 'max' => 1000]
                ]);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateDeliveyReturn(); 
                    if($updated) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        private function editLogo() {
            if($_FILES) {
                $validate = new Validation();

                $validate->check('$_FILES', [
                    'store_logo' => ['display' => 'Image', 'isImage' => true, 'size' => 2.5]
                ]);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateLogo($this->oldLogo); 
                    if($updated) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        private function addCoverPhoto() {
            
            if($_FILES) {
                $validate = new Validation();

                $validate->check('$_FILES', [
                    'store_coverPhoto' => ['display' => 'Image', 'isImage' => true, 'size' => 4.2]
                ]);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateCoverPhoto($this->oldCover); 
                    if($updated) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        private function addProduct($urlParams) {
            $categories = Categories::list();
            
            if($_POST) {
                $this->ProductsModel->assign($_POST);

                if(empty($_FILES["product_image_two"]["name"]) && !empty($_FILES["product_image_three"]["name"])) {
                    echo json_encode(false);
                }

                $validate = new Validation();
                $validate->check('$_POST', Products::productValidates());

                if($validate->passed()) {
                    $inserted = $this->ProductsModel->add("separateCategory");
                    if($inserted) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
                return;
            }

            $data = array(
                "categories" => $categories,
                "store_details" => $this->store_details
            );

            echo json_encode($data);
        }

        private function modifyProduct($urlParams, $product_id) {
            $categories = Categories::list();
            $u = $this->ProductsModel->findProductById($product_id);
            
            foreach($u as $key => $value) {
                $this->ProductsModel->$key = $value;
            }

            if(!empty($u->product_sub_cat)) {
                $product_category = $u->product_cat.' | '.$u->product_sub_cat;
            } else {
                $product_category = $u->product_cat;
            }

            $subCat = [];
            foreach($categories as $cat => $sub_cat) {
                if($cat == $this->store_category && is_array($sub_cat)) { 
                    foreach($sub_cat as $key => $value) {
                        if($key == $u->product_cat && is_array($sub_cat)) { 
                            if(is_array($value)) { 
                                $subCat = $value;
                            } else { 
                                $subCat = [];
                            } 
                        }
                    }
                } 
            }
            
            if($_POST) {
                $this->ProductsModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', Products::productEditValidates());

                if($validate->passed()) {
                    $updated = $this->ProductsModel->modifyProduct("separateCategory", $product_id);
                    if($updated) echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
                return;
            }

            $data = array(
                "store_details" => $this->store_details,
                "product" => $u,
                "categories" => $categories,
                "subCat" => $subCat
            );

            echo json_encode($data);
        }

        private function deleteProduct($product_id) {

            $u = $this->ProductsModel->findProductById($product_id);
            foreach($u as $key => $value) {
                $this->ProductsModel->$key = $value;
            }

            $deleted = $this->ProductsModel->deleteProduct($product_id);
            if($deleted) echo json_encode(true);
        }
        
    }

?>