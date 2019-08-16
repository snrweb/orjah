<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Categories;
    use Core\Session;
    use Core\Router;

    class Admin223Controller extends Controller {
        private $categories;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Stores');
            $this->loadModel('Products');
            $this->loadModel('Admin');

            $this->view->categories = $this->categories = Categories::list();

            $this->view->setLayout('admin');
        }

        public function indexAction() {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                // $this->view->urlParams = 'store';

                // $this->view->stores = $this->StoresModel->findAllStores();
                
                $this->view->render('admin/index');
            }
        }

        public function storeAction($paramsOne = '', $paramsTwo = '') {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                // $this->view->urlParams = 'store';
                // $stores;

                // if($paramsOne == 'category' && !empty($paramsTwo)) {
                //     $category = str_replace('-', ' ', $paramsTwo);
                //     $this->view->stores = $this->StoresModel->findStoresByCategory($category);
                // } else {
                //     $this->view->stores = $this->StoresModel->findAllStores();
                // }
                
                $this->view->render('admin/index');
            }
        }

        public function productAction($paramsOne = '', $paramsTwo = '', $paramsThree = '') {
            if (Session::exists(ADMIN_SESSION_NAME)) {
                // $this->view->urlParams = 'product';
                
                // if($paramsOne == 'category' && !empty($paramsTwo)) {
                //     $paramsTwo = str_replace('-', ' ', $paramsTwo);
                //     $cat = $this->setCategories($paramsTwo);

                //     if(empty($paramsThree)) {
                //         $this->view->products = $this->ProductsModel->findProductsByCategories($cat);
                //     }
                    
                //     if(!empty($paramsThree)) {
                //         $paramsThree = str_replace('-', ' ', $paramsThree);
                //         $this->view->products = $this->ProductsModel->findProductsByCategory($paramsThree);
                //     }
                // } else {
                //     $this->view->products = $this->ProductsModel->findAllProducts();
                // }
                $this->view->render('admin/product');
            }
        }

        private function setCategories($paramsTwo) {
            $category = str_replace('-', ' ', $paramsTwo);
            $cat = '';
            $fcat = [];
            foreach($this->categories as $k => $v) {
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

            $this->view->mcat = $category;
            $this->view->fcat = $fcat;
            return $cat = rtrim($cat, ", ");
        }

        public function editAboutAction() {
            $this->view->urlParams = 'about';
            $this->view->csrf_token_error = '';

            if($_POST) {
                $this->AdminModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', [], true);
                
                if($validate->passed()) {
                    $updated = $this->AdminModel->updateAbout(); 
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('admin/aboutTerms');
        }

        public function editTermsAction() {
            $this->view->urlParams = 'terms';
            $this->view->csrf_token_error = '';

            if($_POST) {
                $this->AdminModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', [], true);
                
                if($validate->passed()) {
                    $updated = $this->AdminModel->updateTerms(); 
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('admin/aboutTerms');
        }
        
        public function logoutAction() {
            Session::delete(ADMIN_SESSION_NAME, 'type');
            Router::redirect('login/adminLogin');
        }

    }

?>