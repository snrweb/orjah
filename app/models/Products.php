<?php 
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Resize;

    class Products extends Model {
        public $product_id, $store_id, $store_menu, $product_cat, $product_sub_cat, $product_name, $product_price, 
               $product_details, $product_image_one, $product_image_two, $product_image_three, $product_views,
               $product_rating, $product_reviews, $time_uploaded, $deleted = 0, $csrf_token;

        public $product_image_one_o, $product_image_two_o, $product_image_three_o;

        public function __construct() {
            $table = 'products';
            parent::__construct($table);
        }

        private function imageOne($actionType) {
            if(!empty($_FILES["product_image_one"]["name"])) {
                $ext = pathinfo($_FILES["product_image_one"]["name"], PATHINFO_EXTENSION);
                if($actionType === 'edit') unlink(ROOT .DS. 'public' .DS. 'images' .DS. 'products' .DS. $this->product_image_one);
                return time().random_int(100, 1000000000).".".$ext;
            } else {
                if($actionType === 'edit') return $this->product_image_one_o;
                return '';
            }
        }

        private function imageTwo($actionType) {
            if(!empty($_FILES["product_image_two"]["name"])) {
                $ext = pathinfo($_FILES["product_image_two"]["name"], PATHINFO_EXTENSION);
                if($actionType === 'edit' && !empty($this->product_image_two)) {
                    unlink(ROOT .DS. 'public' .DS. 'images' .DS. 'products' .DS. $this->product_image_two);
                }
                return time().random_int(100, 1000000000).".".$ext;
            } else {
                if($actionType === 'edit') return $this->product_image_two_o;
                return '';
            }
        }

        private function imageThree($actionType) {
            if(!empty($_FILES["product_image_three"]["name"])) {
                $ext = pathinfo($_FILES["product_image_three"]["name"], PATHINFO_EXTENSION);
                if($actionType === 'edit' && !empty($this->product_image_three)) {
                    unlink(ROOT .DS. 'public' .DS. 'images' .DS. 'products' .DS. $this->product_image_three);
                }
                return time().random_int(100, 1000000000).".".$ext;
            } else {
                if($actionType === 'edit') return $this->product_image_three_o;
                return '';
            }
        }

        public static function productValidates() {
            $validate = true;
            if(!isset($_FILES["product_image_two"]) || !isset($_FILES["product_image_three"])) {
                $validate = false;
            }

            return [
                    'product_name' => ['display' => 'Product name', 'required' => true],
                    'product_price' => ['display' => 'Price', 'required' => true, 'isNumber' => true],
                    'product_cat' => ['display' => 'category', 'required' => true, 'select' => ''],
                    'product_details' => ['display' => 'Details', 'required' => true],
                    'product_image_one' => ['display' => 'Image', 'required' => true, 'isImage' => true, 'size' => 4.5],
                    'product_image_two' => ['display' => 'Image', 'isImage' => $validate, 'size' => 4.5],
                    'product_image_three' => ['display' => 'Image', 'isImage' => $validate, 'size' => 4.5]
                ];
        }

        public function add($check = "") {
            $ext_one = $ext_two = $ext_three = "";

            //this is coming from a javascript enable form
            $product_cat = $this->product_cat;
            $product_sub_cat = $this->product_sub_cat;

            //this is coming from a javascript disabled form
            if(empty($check)) {
                $catAry = explode('|', $this->product_cat);
                $product_cat = trim($catAry[0]);
                $product_sub_cat = trim($catAry[1]);
            }


            $image_one = $this->imageOne('insert');
            $image_two = $this->imageTwo('insert');
            $image_three = $this->imageThree('insert');

            if(!empty($image_three)) {
                $image_three = $_FILES["product_image_three"]["name"];
                $ext_three = pathinfo($_FILES["product_image_three"]["name"], PATHINFO_EXTENSION);
                $image_three = time().random_int(100, 1000000000).".".$ext_three;
            }

            $insert = $this->insert([
                'store_id' => Session::get(STORE_SESSION_NAME), 'store_menu' => $this->store_menu,
                'product_cat' => $product_cat, 'product_sub_cat' => $product_sub_cat,
                'product_name' => $this->product_name, 'product_price' => $this->product_price,
                'product_details' => $this->product_details, 'product_image_one' => $image_one, 
                'product_image_two' => $image_two, 'product_image_three' => $image_three, 
                'time_uploaded' => date('Y-m-d H:i:s')
            ]);

            if($insert) {
                $resize = new Resize();

                $resize::changeSize(
                    $_FILES["product_image_one"]["tmp_name"], //temporary image image location
                    ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $image_one, //location to upload resized image
                    800, //Maximum width of the new resized image
                    800, //Maximum height of the new resized image
                    $ext_one, //File extension of the new resized image
                    80 //Quality of the image
                );

                if(!empty($image_two)) {
                    $resize::changeSize(
                        $_FILES["product_image_two"]["tmp_name"], 
                        ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $image_two, 800, 800, $ext_two, 80 
                    );
                }

                if(!empty($image_three)) {
                    $resize::changeSize(
                        $_FILES["product_image_three"]["tmp_name"], 
                        ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $image_three, 800, 800, $ext_three, 80 
                    );
                }
                return true;
            }
            return false;
        }


        public static function productEditValidates() {
            $validate = true;
            if(!isset($_FILES["product_image_one"]) || !isset($_FILES["product_image_two"]) || 
            !isset($_FILES["product_image_three"])) {
                $validate = false;
            }

            return [
                    'product_name' => ['display' => 'Product name', 'required' => true],
                    'product_price' => ['display' => 'Price', 'required' => true, 'isNumber' => true],
                    'product_cat' => ['display' => 'category', 'required' => true, 'select' => ''],
                    'product_details' => ['display' => 'Details', 'required' => true],
                    'product_image_one' => ['display' => 'Image', 'isImage' => $validate, 'size' => 4.5],
                    'product_image_two' => ['display' => 'Image', 'isImage' => $validate, 'size' => 4.5],
                    'product_image_three' => ['display' => 'Image', 'isImage' => $validate, 'size' => 4.5]
                ];
        }

        public function modifyProduct($check = "", $product_id) {
            $ext_one = $ext_two = $ext_three = "";

            //this is coming from a javascript enable form
            $product_cat = $this->product_cat;
            $product_sub_cat = $this->product_sub_cat;

            //this is coming from a javascript disabled form
            if(empty($check)) {
                $catAry = explode('|', $this->product_cat);
                $product_cat = trim($catAry[0]);
                $product_sub_cat = trim($catAry[1]);
            }

            $image_one = $this->imageOne('edit');
            $image_two = $this->imageTwo('edit');
            $image_three = $this->imageThree('edit');

            $updated = $this->update('product_id', $product_id, [
                'product_name' => $this->product_name, 'product_price' => $this->product_price,
                'product_details' => $this->product_details, 'product_cat' => $product_cat,
                'product_sub_cat' => $product_sub_cat, 'store_menu' => $this->store_menu, 
                'product_image_one' => $image_one, 
                'product_image_two' => $image_two,'product_image_three' => $image_three
            ]);

            if($updated) {
                $resize = new Resize();
                if(!empty($_FILES["product_image_one"]["name"])) {
                    $resize::changeSize(
                        $_FILES["product_image_one"]["tmp_name"],
                        ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $image_one, 800, 800, $ext_one, 80
                    );
                }

                if(!empty($_FILES["product_image_two"]["name"])) {
                    $resize::changeSize(
                        $_FILES["product_image_two"]["tmp_name"], 
                        ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $image_two, 800, 800, $ext_two, 80 
                    );
                }

                if(!empty($_FILES["product_image_three"]["name"])) {
                    $resize::changeSize(
                        $_FILES["product_image_three"]["tmp_name"], 
                        ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $image_three, 800, 800, $ext_three, 80 
                    );
                }
                return true;
            }
            return false;
        }

        public function deleteProduct(int $product_id) {
            $deleted = $this->delete('product_id', $product_id);
            if($deleted) {
                unlink(ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $this->product_image_one);
                if(!empty($this->product_image_two)) {
                    unlink(ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $this->product_image_two);
                }
                if(!empty($this->product_image_three)) {
                    unlink(ROOT . DS . 'public' . DS . 'images' . DS . 'products' . DS . $this->product_image_three);
                }
                return true;
            }
            return false;
        }

        public function findAllProducts() {
            return $this->query("SELECT product_id,	product_cat, product_sub_cat, product_name, product_price, 
                                        product_details, product_rating, time_uploaded, deleted
                                    FROM products")->getResult();
        }

        public function findProductsByCategories($category) {
            return $this->query("SELECT product_id,	product_cat, product_sub_cat, product_name, product_price, 
                                        product_details, product_rating, time_uploaded, deleted
                                    FROM products 
                                    WHERE product_cat IN ($category)")->getResult();
        }

        public function searchProduct($params) {
            if(is_numeric($params)) {
                return $this->query("SELECT product_id,	product_cat, product_sub_cat, product_name, product_price, 
                                            product_details, product_rating, time_uploaded, deleted
                                        FROM products 
                                        WHERE product_id LIKE '%".$params."%' LIMIT 10")->getResult();
            } else {
                return $this->query("SELECT product_id,	product_cat, product_sub_cat, product_name, product_price, 
                                            product_details, product_rating, time_uploaded, deleted 
                                    FROM products 
                                    WHERE product_name LIKE '%".$params."%' LIMIT 10")->getResult();
            }
        }

        public function findProductsByCategory($category) {
            return $this->query("SELECT product_id,	product_cat, product_sub_cat, product_name, product_price, 
                                        product_details, product_rating, time_uploaded, deleted
                                    FROM products 
                                    WHERE product_cat = ?", ["$category"])->getResult();
        }
        
        public function findProductById(int $product_id) {
            return $this->findFirst(['conditions'=>'product_id = ?', 'bind'=>[$product_id]]);
        }

        public function findProductByCategory(string $cat) {
            return $this->find(['conditions'=>'product_cat = ?', 'bind'=>[$cat]]);
        }

        public function findProductBySubCategory(string $cat, string $subCat) {
            return $this->find(['conditions'=>'product_cat = ?, product_sub_cat = ?', 'bind'=>[$cat, $subCat]]);
        }

        public function findProductByMainCategory($fcat) {
            return $this->query("SELECT * FROM products WHERE product_cat IN ($fcat)")->getResult();
        }

        public function updateViewCounts($product_views) {
            $updated = $this->update('product_id', $this->product_id, ['product_views' => $product_views]);
        }

        public function updateReviewCounts($product_id, $product_rating) {
            $getCurrent = $this->findProductById($product_id);

            $newReviewCount = $getCurrent->product_reviews + 1;
            $newRating = (($getCurrent->product_rating * $getCurrent->product_reviews) +
                                $product_rating) / $newReviewCount;

            return $updated = $this->update('product_id', $product_id, ['product_reviews' => $newReviewCount, 
                                                                 'product_rating' => $newRating]);
        }
        
    }

?>