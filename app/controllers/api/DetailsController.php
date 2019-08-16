<?php 
    namespace App\Controllers\API;
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
            $this->getProductDetails($product_id);
            $this->view->categories = $categories = Categories::list();
            if(Session::exists(STORE_SESSION_NAME)) {
                $this->view->newOrders = $this->OrdersModel->newOrders(Session::get(STORE_SESSION_NAME));
            }
        }

        private function getProductDetails($product_id) {
            if(!empty($product_id) && is_numeric($product_id)) {
                $u = $this->ProductsModel->findById('product_id', $product_id);
                $this->setVariables($u);
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
            $this->totalProductViews = $u->product_views;
            
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
                    if($this->BasketsModel->addToBasket($this->store_id, $product_id, $hash)) {
                        echo "trueA";
                    } else {
                        echo "falseA";
                    }
                }
            } else {
                echo "Product already in basket";
            }
        }

        public function removeFromBasketAction($product_id) {
            $this->getProductDetails($product_id);
            if(Cookie::exists(USER_COOKIE_NAME) && Cookie::exists('basket')) {
                $product_ids = explode(" ", Cookie::get('basket'));
                if(in_array($product_id, $product_ids)) {
                    $pIndex = array_search($product_id, $product_ids);
                    unset($product_ids[$pIndex]);
                    $product_ids = implode(" ", $product_ids);
                    $basketCookie = Cookie::set('basket', $product_ids, time() + (86400 * 7));
                } 
            }
            if(Session::exists(BUYER_SESSION_NAME)) {
                if($this->BasketsModel->deleteFromBasketP($product_id)) {
                    echo "trueR";
                } else {
                    echo "falseR";
                }
            }            
        }

        public function sendOrderAction($product_id) {
            $this->getProductDetails($product_id);
            if(Session::exists(BUYER_SESSION_NAME)) {
                if($_POST) {              
                    $this->OrdersModel->assign($_POST);
                    $sent = $this->OrdersModel->sendOrder($this->store_id, $product_id);
                    if($sent) {
                        echo "true";
                    } else {
                        echo "false";
                    }
                }
            } else {
                echo "Please login as a buyer";
            }
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