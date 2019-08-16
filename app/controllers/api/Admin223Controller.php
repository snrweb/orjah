<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Categories;
    use Core\Session;
    use Core\Router;
    use App\Libs\Email;
    use App\Libs\EmailLayout;

    class Admin223Controller extends Controller {
        private $categories, $email; 

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->APIheaders();

            $this->loadModel('Stores');
            $this->loadModel('Products');
            $this->loadModel('MessagesSentByAdmin');
            $this->loadModel('Admin');

            $this->view->setLayout('admin');
            $this->email = new Email();
        }

        public function indexAction() {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                $this->view->urlParams = 'store';

                $data = array(
                    'stores' => $this->StoresModel->findAllStores()
                );
                echo json_encode($data);
            }
        }

        public function storeAction($paramsOne = '', $paramsTwo = '') {
            //if (Session::exists(ADMIN_SESSION_NAME)) {
                $stores;

                if($paramsOne == 'category' && !empty($paramsTwo)) {
                    $category = str_replace('-', ' ', $paramsTwo);
                    $stores = $this->StoresModel->findStoresByCategory($category);
                } else {
                    $stores = $this->StoresModel->findAllStores();
                }
                
                $data = array(
                    'stores' => $stores
                );
                
                echo json_encode($data);
            //}
        }

        
        public function searchStoreAction($params = '') {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                $stores = $this->StoresModel->searchStore($params);
                $data = array(
                    'stores' => $stores
                );
                echo json_encode($data);
            }
        }

        public function searchProductAction($params = '') {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                $products = $this->ProductsModel->searchProduct($params);
                $data = array(
                    'products' => $products
                );
                echo json_encode($data);
            }
        }

        public function productAction($paramsOne = '', $paramsTwo = '', $paramsThree = '') {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                $products; $productSubCat = [];

                if($paramsOne == 'category' && !empty($paramsTwo)) {
                    $paramsTwo = str_replace('-', ' ', $paramsTwo);
                    $cat = $this->setCategories($paramsTwo);
                    $productSubCat = $cat[1];

                    if(empty($paramsThree)) {
                        $products = $this->ProductsModel->findProductsByCategories($cat[0]);
                    }
                    
                    if(!empty($paramsThree)) {
                        $paramsThree = str_replace('-', ' ', $paramsThree);
                        $products = $this->ProductsModel->findProductsByCategory($paramsThree);
                    }
                } else {
                    $products = $this->ProductsModel->findAllProducts();
                }

                if(empty($products)) $products = [];
                $data = array(
                    'products' => $products,
                    'subCats' => $productSubCat
                );
                echo json_encode($data);
            }
        }

        private function setCategories($paramsTwo) {
            $category = str_replace('-', ' ', $paramsTwo);
            $cat = '';
            $fcat = [];
            $categories = Categories::list();
            foreach($categories as $k => $v) {
                if($k == $category) {
                    foreach($v as $kcat => $vcat) {
                        if(is_numeric($kcat)) {
                            $cat .= "'$vcat', ";
                            array_push($fcat, $vcat);
                        } else {
                            $cat .= "'$kcat', ";
                            array_push($fcat, $kcat);
                        }
                    }
                }
            }

            return $cat = [rtrim($cat, ", "), $fcat, $category];
        }

        public function categoriesAction() {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                $categories = Categories::category();

                $data = array(
                    'categories' => $categories
                );

                echo json_encode($data);
            }
        }

        public function softDeleteAction($store_id, $visibilityType) {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                if($this->StoresModel->update('store_id', $store_id, [
                    'deleted' => $visibilityType
                ])) {
                    echo json_encode($visibilityType);
                }
            }
        }

        public function deleteStoreAction($store_id) {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                if($this->StoresModel->delete('store_id', $store_id)) {
                    echo json_encode(true);
                }
            }
        }

        public function sendMessageAction($store_id) {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                $result = $this->StoresModel->findById('store_id', $store_id);
                $this->email->setEmailSubject($_POST['subject']);
                $this->email->setRecipientEmail($result->store_email);
                $content = EmailLayout::AdminStoreMsgLayout($_POST['body'], $result->store_name);
                $this->email->setEmailContent($content);
                if($this->email->sendEmail()) {
                    $this->MessagesSentByAdminModel->saveMessage($store_id, $result->store_name, 
                                                        $result->store_email, $_POST['subject'], $_POST['body']);
                    echo json_encode(true);
                } else {
                    echo json_encode(false);
                }
            }
        }

        public function productSoftDeleteAction($product_id, $visibilityType) {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                if($this->ProductsModel->update('product_id', $product_id, [
                    'deleted' => $visibilityType
                ])) {
                    echo json_encode($visibilityType);
                }
            }
        }

        public function deleteProductAction($product_id) {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                if($this->ProductsModel->delete('product_id', $product_id)) {
                    echo json_encode(true);
                }
            }
        }

        public function editAboutAction() {
            if($_POST) {
                $this->AdminModel->assign($_POST);
                if($this->AdminModel->updateAbout()) {
                    echo json_encode(true);
                } else {
                    echo json_encode('Error during update');
                } 
            }
        }

        public function editTermsAction() {
            if($_POST) {
                $this->AdminModel->assign($_POST);

                if($this->AdminModel->updateTerms()) {
                    echo json_encode(true);
                } else {
                    echo json_encode('Error during update');
                }
            }
        }

        public function logoutAction() {
            Session::delete(ADMIN_SESSION_NAME, 'type');
            //Router::redirect('login/adminLogin');
            echo json_encode(true);
        }
    }

?>