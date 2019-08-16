<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    
    class Bookmarks extends Model {

        public function __construct() {
            parent::__construct('bookmarks');
        }

        public function bookmarkStore($store_id) {
            $this->insert([
                        'store_id' => $store_id,
                        'buyer_id' => Session::get(BUYER_SESSION_NAME),
                        'created_at' => date('Y-m-d H:i:s')
                        ]);
        }

        public function checkBookmarked($store_id) {
            $result = $this->findFirst(['conditions' => 'store_id = ? AND buyer_id = ?', 
                                        'bind' => [$store_id, Session::get(BUYER_SESSION_NAME)]
                                        ]);

            return (!empty($result)) ? true : false;
            
        }

        public function totalBookmark($store_id) {
            $result = $this->query("SELECT count('store_id') AS totalBookmark FROM bookmarks WHERE store_id = ?", [$store_id]);
            return $result->getResult()[0]->totalBookmark;
        }

        public function deleteBookmark($store_id) {
            $buyer_id = Session::get(BUYER_SESSION_NAME);
            $sql = "DELETE FROM bookmarks WHERE buyer_id = ?  AND store_id = ?";
            $this->query($sql, [$buyer_id, $store_id]);
        }

    }
?>