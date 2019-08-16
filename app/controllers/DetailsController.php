<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Router;
    use Core\Session;
    use Core\Cookie;
    use Core\Validation;
    use Core\Categories;

    class DetailsController extends Controller {
        private $totalProductViews, $store_id, $product_name, $product_id, $bookmark_id, $product_category;

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Stores');
            $this->loadModel('Products');
            $this->loadModel('ProductReviews');
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->loadModel('Bookmarks');
            $this->loadModel('Messages');
            
            $product_id = str_replace('Action', '', $this->_action);
            $this->view->isProductInBasket = $this->BasketsModel->checkProductInBasket($product_id);
            $this->getProductDetails($product_id);
            $this->setProductViewCount($product_id);
            $this->view->categories = $categories = Categories::list();
            if(Session::exists(STORE_SESSION_NAME)) {
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
            
            $this->view->setLayout('details');
        }

        private function getProductDetails($product_id) {
            if(!empty($product_id) && is_numeric($product_id)) {
                $u = $this->ProductsModel->findById('product_id', $product_id);
                foreach($u as $key => $value) {
                    $this->view->$key = $value;
                    if($key === 'product_rating') {
                        $this->view->product_rating = $this->view->rate($value).' '.$value;
                        $this->view->product_rating_value = $value;
                    }
                }
 
                $categories = Categories::list();
                $mainCategory = '';
                foreach($categories as $key => $val) {
                    foreach($val as $cat => $scat) {
                        if(is_numeric($cat)) {
                            if($u->product_cat == $scat) return $mainCategory = $key;
                        }
                        if($u->product_cat == $cat) $mainCategory = $key;
                    }
                }

                $categoryMaps = [[$mainCategory], [$mainCategory, $u->product_cat]];
                if(!empty($u->product_sub_cat)) array_push($categoryMaps, [$mainCategory, $u->product_cat, $u->product_sub_cat]);
                $this->view->categoryMaps = $categoryMaps;

                $this->view->productReviews = $this->getProductReviews($product_id);
                $this->setVariables($u);

                $this->fetchSimilarProducts($u->product_cat);
                $this->fetchSimilarStores($mainCategory);
            }
        }

        private function getProductDetail($product_id) {
            if(!empty($product_id) && is_numeric($product_id)) {
                $u = $this->ProductsModel->findById('product_id', $product_id);
                $this->assignToView($u);
                $this->setVariables($u);
            }
        }

        private function setVariables($u) {
            $this->store_id = $u->store_id;
            $this->product_name = $u->product_name;
            $this->product_id = $u->product_id;
            $this->view->bookmark = false;
            $this->totalProductViews = $u->product_views;
            
            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->totalProductInBasket = $this->BasketsModel->countProductInBasket();
                $this->view->totalOrders = $this->OrdersModel->countSentOrder();
                $this->view->bookmark = $this->bookmark_id = $this->BookmarksModel->checkBookmarked($u->store_id);
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $this->view->msgCount = $this->MessagesModel->unReadMessages();
            }
        }

        private function getProductReviews($product_id) {
            $productReviews = $this->ProductReviewsModel->getProductReviews($product_id);
            $one_rating = $two_rating = $three_rating = $four_rating = $five_rating = [];
            foreach($productReviews as $productReview) {
                foreach($productReview as $key => $value) {
                    if($key === 'product_rating') {
                        switch ($value) {
                            case 5: 
                                array_push($five_rating, $value);
                                break;
                            case 4: 
                                array_push($four_rating, $value);
                                break;
                            case 3: 
                                array_push($three_rating, $value);
                                break;
                            case 2: 
                                array_push($two_rating, $value);
                                break;
                            case 1: 
                                array_push($one_rating, $value);
                                break;
                        }
                    }
                }
            } 
            $one_rating = count($one_rating); $two_rating = count($two_rating); $three_rating = count($three_rating); 
            $four_rating = count($four_rating); $five_rating = count($five_rating);

            $rating = array('one_rating' => $one_rating, 'two_rating' => $two_rating, 'three_rating' => $three_rating, 
                            'four_rating' => $four_rating, 'five_rating' => $five_rating);

            $totalReviews = $one_rating + $two_rating + $three_rating + $four_rating + $five_rating;

            foreach($rating as $key => $value) {
                $this->view->{$key} = $value;
                if($value != 0) {
                  $this->view->{'percent_'.$key} = ($value / $totalReviews) * 100;
                } else { $this->view->percent_{$key} = 0;}
            }
            return $productReviews;
        }

        private function fetchSimilarProducts($product_category) {
            $products = $this->ProductsModel->find(['conditions' => 'product_cat = ?', 
                                                    'bind' => [$product_category],
                                                    'limit' => '8'
                                                   ]);
                                                   
            $this->view->similarProducts = $products;
        }

        private function fetchSimilarStores($store_category) {
            $similarStores = $this->StoresModel->find(['conditions' => 'store_category = ?', 
                                                    'bind' => [$store_category],
                                                    'limit' => '2'
                                                   ]);
                                                   
            $this->view->similarStores = $similarStores;
        }

        private function setProductViewCount($product_id) {
            $product_ids = [];
            if(Cookie::exists('product_id')) {
                $product_ids = explode(" ", Cookie::get('product_id'));
            }
            if (!in_array($product_id, $product_ids)) {
                array_push($product_ids, $product_id);
                $product_ids = implode(" ", $product_ids);
                Cookie::set('product_id', $product_ids, time() + (86400 * 7));
                $newTotalViews = $this->totalProductViews + 1;
                $this->ProductsModel->updateViewCounts($newTotalViews);
            }   
        }

        public function paramsAction(string $productName, string $orderParam = '') {
            $this->view->urlParams = 'details';
            if(!empty($this->store_id) && is_numeric($this->store_id)) {
                $u = $this->StoresModel->findById('store_id', $this->store_id);
                foreach($u as $key => $value) {
                    $this->view->$key = $value;
                }
            }
            if($orderParam === 'sent') {
                $this->view->success = 'Order successfully sent';
            } elseif($orderParam === 'error') {
                $this->view->danger = 'Please login as a buyer';
            }
            $this->view->store_rating = $this->view->rate($u->store_rating).' '.$u->store_rating;

            $this->view->render('store/productDetails');
        }

        public function writeReviewAction(int $product_id) {
            $this->getProductDetail($product_id);
            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->view->urlParams = 'review'; $this->view->product_review = $this->view->product_rating = '';
                $this->view->product_review_error = $this->view->product_rating_error = $this->view->csrf_token_error = '';
                if($_POST) {
                    $this->assignToView($_POST);

                    $validate = new Validation();
                    $validate->check('$_POST', $this->ProductReviewsModel->validateReview(), true);

                    if($validate->passed()) {
                        $this->ProductReviewsModel->assign($_POST);
                        $result = $this->ProductReviewsModel->findReview($product_id);
                        if(empty($result)) {
                            $this->ProductReviewsModel->insertReview($product_id);
                            $this->ProductsModel->updateReviewCounts($product_id, $this->ProductReviewsModel->product_rating);
                            
                            $this->StoresModel->updateStoreRating($this->store_id, $this->ProductReviewsModel->product_rating);
                        } else {
                            $this->ProductReviewsModel->updateReview($result->review_id);
                        }         
                        Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name));
                    } else {
                        $this->view->setFormErrors($validate->getErrors());
                    } 
                }
                return $this->view->render('store/productDetails');
            } 
            Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name).'/error');
        }

        public function addToBasketAction($product_id) {
            $this->getProductDetails($product_id);
            
            if(Cookie::exists(USER_COOKIE_NAME)) {
                $this->setBasket($product_id, $hash = '');
            } else {
                $hash = md5(uniqid() + random_int(100, 100000));
                if (Cookie::set(USER_COOKIE_NAME, $hash, time() + (86400 * 7))) {
                    $this->setBasket($product_id, $hash);
                }
            }             
            Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name));
        }

        private function setBasket($product_id, $hash) {
            $product_ids = [];
            if(Cookie::exists('basket')) {
                $product_ids = explode(" ", Cookie::get('basket'));
            }
            if (!in_array($product_id, $product_ids)) {
                array_push($product_ids, $product_id);
                $product_ids = implode(" ", $product_ids);
                $basketCookie = Cookie::set('basket', $product_ids, time() + (86400 * 7));
                if($basketCookie) {
                    $this->BasketsModel->addToBasket($this->store_id, $product_id, $hash);
                }
            }
        }

        public function removeFromBasketAction($product_id) {
            $this->getProductDetails($product_id);
            if(Cookie::exists(USER_COOKIE_NAME)) {
                $product_ids = explode(" ", Cookie::get('basket'));
                if(in_array($product_id, $product_ids)) {
                    $pIndex = array_search($product_id, $product_ids);
                    unset($product_ids[$pIndex]);
                    $product_ids = implode(" ", $product_ids);
                    $basketCookie = Cookie::set('basket', $product_ids, time() + (86400 * 7));
                } 
            }
            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->BasketsModel->deleteFromBasketP($product_id);
            }            
            Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name));
        }

        public function sendOrderAction($product_id) {
            $this->getProductDetails($product_id);
            if(Session::exists(BUYER_SESSION_NAME)) {
                if($_POST) {              
                    $this->OrdersModel->assign($_POST);
                    $sent = $this->OrdersModel->sendOrder($this->store_id, $product_id);
                    if($sent) Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name).'/sent');;
                }
            }
            Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name).'/error');
        }

        public function bookmarkStoreAction($params, $product_id) {
            $this->getProductDetails($product_id);
            if(Session::exists(BUYER_SESSION_NAME)) {
                if($params === 'add') {
                    $this->BookmarksModel->bookmarkStore($this->store_id);
                } elseif($params === 'remove') {
                    $this->BookmarksModel->deleteBookmark($this->store_id);
                }
            } else {
                Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name).'/error');
            }
            Router::redirect('details/'.$product_id.'/'.str_replace(' ', '-', $this->product_name));
        }

    }

?>