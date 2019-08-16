<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Categories;
    use Core\Validation;
    use App\Models\Products;

    class StoresController extends Controller {
        private $store_category, $store_name;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Stores');
            $this->loadModel('Products');
            $this->loadModel('Bookmarks');
            $this->loadModel('Messages');
            $this->loadModel('Orders');
            $this->loadModel('StoreVisit');
            $this->view->setLayout('storeAdmin');
            
            if(Session::exists(STORE_SESSION_NAME)) {
                $u = $this->StoresModel->findById('store_id', Session::get(STORE_SESSION_NAME));
                $this->store_category = $u->store_category;
                $this->store_name = $u->store_name;
                $this->assignToView($u);
                $this->view->store_rating = $this->view->rate($u->store_rating).' ('.$u->store_rating.')';
                $this->view->store_baskets = $this->StoresModel->storeBasket(Session::get(STORE_SESSION_NAME));
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
                $this->view->store_bookmarks = $this->BookmarksModel->totalBookmark(Session::get(STORE_SESSION_NAME));
                $this->view->store_visit = $this->StoreVisitModel->lastSevenDayVisit(Session::get(STORE_SESSION_NAME));
            }
        }

        public function adminAction($params = '', $params2 = '') {
            $this->view->urlParams = 'admin';
            $this->view->categories = Categories::list();
            if (Session::exists(STORE_SESSION_NAME)) {
                $this->view->msgCount = $this->MessagesModel->unReadMessages();

                $this->view->product_name_error = $this->view->product_price_error = $this->view->product_cat_error = '';
                $this->view->product_image_one_error = $this->view->product_image_two_error = '';
                $this->view->product_image_three_error = $this->view->product_details_error = ''; 
                $this->view->csrf_token_error = $this->view->store_menu_error = ''; 
    
                $this->view->product_name = $this->view->product_price = $this->view->product_category = '';
                $this->view->product_image_one = $this->view->product_image_two = $this->view->product_image_three = ''; 
                $this->view->product_details = ''; $this->view->urlParams = '';

                $products = $this->ProductsModel->find(['conditions' => 'store_id = ?', 
                                                        'bind' => [Session::get(STORE_SESSION_NAME)]
                                                       ]);
                                                       
                $store_menus = []; 
                foreach($products as $p) {
                    if(!in_array($p->store_menu, $store_menus)) {
                        array_push($store_menus, $p->store_menu);
                    }
                }
                                                        
                $this->view->store_menus = $store_menus;  
                $this->view->products = $products;

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
                            $this->deleteProduct('delete', $params2);
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
                $this->view->render('store/storeAdmin');
            }
        }

        private function addCategory() {
            $this->view->urlParams = 'category';
            $this->view->category_one_error = $this->view->category_two_error = '';
            $this->view->category_three_error = $this->view->category_four_error = '';
            
            if($_POST) {
                $validate = new Validation();
                $this->StoresModel->assign($_POST);

                $validate->check('$_POST', [
                    'category_one' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                    'category_two' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                    'category_three' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                    'category_four' => ['display' => 'Category', 'letters' => true, 'max' => 20],
                ], true);

                if($validate->passed()) {
                    $updated = $this->StoresModel->addCategory();
                    if($updated) Router::redirect('stores/admin/');
                } 
                $this->view->setFormErrors($validate->getErrors());
            }
        }

        private function orders() {
            $this->view->urlParams = 'orders';
            $this->view->orders = $this->OrdersModel->fetchOrders();
            
            if($_POST) {
                $validate = new Validation();
                $validate->check('$_POST', [
                    'store_name' => ['display' => 'Store name', 'required' => true, 'isStoreName' => true],
                    'buyer_id' => ['display' => 'ID', 'required' => true, 'isNumeric' => true]
                ]);
                if($validate->passed()) {
                    $message = new MessageController('MessageController', 'contactAction');
                    if($message->contactAction($_POST['buyer_id'], $_POST['store_name'])) {
                        $this->view->success = 'Message sent successfully';
                    }
                }
            }
        }

        private function deleteOrders($params2) {
            $this->view->urlParams = 'orders';
            if($this->OrdersModel->deleteOrder($params2)) {
                Router::redirect('stores/admin/orders');
            }
        }

        private function editContact() {
            $this->view->urlParams = 'details';
            
            //Select all categories
            $this->view->category = Categories::list();

            $this->view->store_name_error = $this->view->store_email_error = $this->view->store_category_error = '';
            $this->view->store_country_error = $this->view->store_city_error = $this->view->store_street_error = '';
            $this->view->store_phone_error = $this->view->csrf_token_error = '';
            
            if($_POST) {
                $validate = new Validation();

                foreach($_POST as $key => $value) {
                    $this->view->$key = $this->StoresModel->$key;
                }

                /*** Assigns the value of $_POST properties to the properties of the 
                 * StoresModel object*/
                $this->StoresModel->assign($_POST);

                $validate->check('$_POST', $this->StoresModel->validateDetails(), true);
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateDetails(); 
                    if($updated) Router::redirect('stores/admin');
                    
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
        }

        private function editSocial() {
            $this->view->urlParams = 'socials';
            
            $this->view->facebook_error = $this->view->twitter_error = $this->view->instagram_error = '';
            $this->view->csrf_token_error = '';

            if($_POST) {
                $this->StoresModel->assign($_POST);

                foreach($_POST as $key => $value) {
                    $this->view->$key = $this->StoresModel->$key;
                }
                
                $validate = new Validation();
                $validate->check('$_POST', [
                    'facebook' => ['display' => 'Facebook link', 'url' => true],
                    'twitter' => ['display' => 'twitter link', 'url' => true],
                    'instagram' => ['display' => 'instagram link', 'url' => true]
                ], true);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateSocialLinks(); 
                    if($updated) Router::redirect('stores/admin');
                    
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }    
        }

        private function editAbout() {
            $this->view->urlParams = 'about';
            
            $this->view->store_about_error = $this->view->csrf_token_error = '';
            if($_POST) {
                $this->StoresModel->assign($_POST);

                foreach($_POST as $key => $value) {
                    $this->view->$key = $this->StoresModel->$key;
                }

                $validate = new Validation();
                $validate->check('$_POST', [
                    'store_about' => ['display' => 'About store', 'max' => 1000]
                ], true);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateAbout(); 
                    if($updated) Router::redirect('stores/admin');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
        }

        private function editDelivery() {
            $this->view->urlParams = 'delivery';
            
            $this->view->delivery_terms_error = $this->view->return_policy_error = '';
            $this->view->csrf_token_error = '';
            if($_POST) {
                $this->StoresModel->assign($_POST);

                foreach($_POST as $key => $value) {
                    $this->view->$key = $this->StoresModel->$key;
                }

                $validate = new Validation();
                $validate->check('$_POST', [
                    'delivery_terms' => ['display' => 'Delivery terms', 'max' => 1000],
                    'return_policy' => ['display' => 'Return olicy', 'max' => 1000]
                ], true);
                
                if($validate->passed()) {
                    $updated = $this->StoresModel->updateDeliveyReturn(); 
                    if($updated) Router::redirect('stores/admin');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
        }

        private function editLogo() {
            $this->view->urlParams = 'logo';
            
            $this->view->store_logo_error = '';
            if($_FILES) {
                $validate = new Validation();

                $validate->check('$_FILES', [
                    'store_logo' => ['display' => 'Image', 'isImage' => true, 'size' => 2,]
                ], true);
                
                if($validate->passed()) {
                    $oldLogo = $this->view->store_logo;
                    $updated = $this->StoresModel->updateLogo($oldLogo); 
                    if($updated) Router::redirect('stores/admin');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
        }

        private function addCoverPhoto() {
            $this->view->urlParams = 'coverPhoto';
            
            $this->view->store_coverPhoto_error = '';
            if($_FILES) {
                $validate = new Validation();

                $validate->check('$_FILES', [
                    'store_coverPhoto' => ['display' => 'Image', 'isImage' => true, 'size' => 5,]
                ], true);
                
                if($validate->passed()) {
                    $oldCover = $this->view->store_coverPhoto;
                    $updated = $this->StoresModel->updateCoverPhoto($oldCover); 
                    if($updated) Router::redirect('stores/admin');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
        }

        private function addProduct($urlParams) {
            $this->view->urlParams = $urlParams;
            
            if($_POST) {
                $this->ProductsModel->assign($_POST);
                foreach($_POST as $key => $value) {
                    $this->view->$key = $this->ProductsModel->$key;
                }

                if(empty($_FILES["product_image_two"]["name"]) && !empty($_FILES["product_image_three"]["name"])) {
                    $err = '<span class="inputError">Image must not be empty</span>';
                    return $this->view->product_image_two_error = $err;
                }
 
                $validate = new Validation();
                $validate->check('$_POST', Products::productValidates(), true);

                if($validate->passed()) {
                    $inserted = $this->ProductsModel->add();
                    if($inserted) Router::redirect('stores/admin');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
        }

        private function modifyProduct($urlParams, $product_id) {
            $this->view->urlParams = $urlParams;

            $u = $this->ProductsModel->findProductById($product_id);
            
            foreach($u as $key => $value) {
                $this->view->$key = $value;
                $this->ProductsModel->$key = $value;
            }

            if(!empty($u->product_sub_cat)) {
                $this->view->product_category = $u->product_cat.'|'.$u->product_sub_cat;
            } else {
                $this->view->product_category = $u->product_cat;
            }

            if($_POST) {
                $this->ProductsModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', Products::productEditValidates(), true);

                if($validate->passed()) {
                    $updated = $this->ProductsModel->modifyProduct($product_id);
                    if($updated) Router::redirect('stores/admin');
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
        }

        private function deleteProduct($urlParams, $product_id) {
            $this->view->urlParams = $urlParams;
    
            $u = $this->ProductsModel->findProductById($product_id);
            foreach($u as $key => $value) {
                $this->ProductsModel->$key = $value;
            }

            $deleted = $this->ProductsModel->deleteProduct($product_id);
            if($deleted) {
                Router::redirect('stores/admin');
            }
        }
        
    }

?>