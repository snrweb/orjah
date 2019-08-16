<?php 
    namespace App\Models;
    use Core\Model;
    use Core\Session;

    class ProductReviews extends Model {
        public $review_id, $buyer_id, $product_id, $product_review, $product_rating, $deleted = 0;

        public function __construct() {
            parent::__construct('product_reviews');
        }

        public function validateReview() {
            return [
                'product_review' => ['display' => 'Review', 'required' => true, 'max' => 500], 
                'product_rating' => ['display' => 'Rating', 'required' => true, 'isNumber' => true] 
            ];
        }

        public function findReview(int $product_id) {
            return $this->findFirst(['conditions'=>['product_id = ?', 'buyer_id = ?'], 
                                'bind'=>[$product_id, Session::get(BUYER_SESSION_NAME)]
                            ]);
        }

        public function insertReview(int $product_id) {
            return $this->insert([
                                    'product_id' => $product_id,
                                    'buyer_id' => Session::get(BUYER_SESSION_NAME),
                                    'product_review' => $this->product_review,
                                    'product_rating' => $this->product_rating,
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
        }

        public function updateReview(int $review_id) {
            return  $this->update('review_id', $review_id, 
                                        [
                                            'product_review' => $this->product_review,
                                            'product_rating' => $this->product_rating,
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]);
        }

        public function getProductReviews($product_id) {
            $sql = "SELECT product_reviews.buyer_id, product_reviews.product_id, product_reviews.product_review,  
                product_reviews.product_rating, product_reviews.created_at, buyers.buyer_name
                FROM product_reviews
                LEFT JOIN buyers
                ON product_reviews.buyer_id = buyers.buyer_id
                WHERE product_reviews.product_id = ? AND product_reviews.deleted <> 1";
                return $this->query($sql, $product_id)->getResult();
        }

    }
?>