<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Categories;

    class CategoryController extends Controller {

        /********************
         * Call the extended controller construct to 
         * instatiate the view object
         */
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->loadModel('Messages');
            $this->loadModel('Products');

            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $this->view->totalOrders = $this->OrdersModel->countSentOrder();
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }

            $this->view->setLayout('details');
        }

        public function ParamsAction($paramsOne = '', $paramsTwo = '') {
            $paramsOne = str_replace('-', ' ', $paramsOne);
            $paramsTwo = str_replace('-', ' ', $paramsTwo);
            $this->view->categories = $categories = Categories::list();

            $mainCategoryName = str_replace(['Action', '-'], ['', ' '], $this->_action);

            $categoryMaps = [];
            if(!empty($mainCategoryName)) array_push($categoryMaps, [$mainCategoryName]);
            if(!empty($paramsOne)) array_push($categoryMaps, [$mainCategoryName, $paramsOne]);
            if(!empty($paramsTwo)) array_push($categoryMaps, [$mainCategoryName, $paramsOne, $paramsTwo]);
            $this->view->categoryMaps = $categoryMaps;

            $this->getCategories($categories, $mainCategoryName, $paramsOne, $paramsTwo);
            $this->getProducts($categories, $mainCategoryName, $paramsOne, $paramsTwo);

            $this->view->render('category/category');
        }

        private function getProducts($categories, $mainCategoryName, $paramsOne, $paramsTwo) {
            if(!empty($paramsOne) && empty($paramsTwo)) {
                $this->view->products = $this->ProductsModel->findProductByCategory($paramsOne);
            } elseif(!empty($paramsOne) && !empty($paramsTwo)) {
                $this->view->products = $this->ProductsModel->findProductBySubCategory($paramsOne, $paramsTwo);
            } else {
                foreach($categories as $category => $sub_categories) {
                    if($category == $mainCategoryName) {
                        $fcat = '';
                        foreach($sub_categories as $k => $v) {
                            if(is_numeric($k)) {
                                $fcat .= "'$v', ";
                            } else {
                                $fcat .= "'$k', ";
                            }
                        }
                    }
                }
                $fcat = rtrim($fcat, ", ");
                $this->view->products = $this->ProductsModel->findProductByMainCategory($fcat);
            }
        }

        private function getCategories($categories, $mainCategoryName, $paramsOne, $paramsTwo) {
            if(!empty($mainCategoryName) && empty($paramsOne)) {
                foreach($categories as $category => $sub_categories) {
                    if($category == $mainCategoryName) {
                        $fcat = [];
                        foreach($sub_categories as $k => $v) {
                            if(is_numeric($k)) {
                                array_push($fcat, $v);
                            } else {
                                array_push($fcat, $k);
                            }
                        }
                        $this->view->cat = $fcat;
                        $this->view->mcat = '/'.$category;
                    }
                }
                return;
            }

            if(!empty($mainCategoryName) && !empty($paramsOne)) {
                foreach($categories as $category => $sub_categories) {
                    if($category == $mainCategoryName) {
                        foreach($sub_categories as $cat => $scat) {
                            if(is_numeric($cat)) {
                                if($scat == $paramsOne) {
                                    $this->view->cat = $sub_categories;
                                    $this->view->mcat = '/'.$category;
                                }
                            } else {
                                if($cat == $paramsOne) {
                                    $this->view->cat = $scat;
                                    $this->view->mcat = '/'.$category.'/'.$paramsOne;
                                }
                            }
                        }
                    }
                }
            }
            
        }


    }
?>