<?php
    namespace App\Models;
    use Core\Model;
    use Core\Cookie;
    
    class StoreVisit extends Model {
        public $id, $store_id, $visit_count, $visit_day, $visited_at;

        public function __construct() {
            parent::__construct('store_visit');
        }

        public function timeOfStoreVisit($store_id) {
            $visitedStoreAry = [];
            if(Cookie::exists(VISIT_COOKIE_NAME)) {
                $visitedStoreAry = explode(" ", Cookie::get(VISIT_COOKIE_NAME));
            }

            if (!in_array($store_id, $visitedStoreAry)) {
                array_push($visitedStoreAry, $store_id);
                $visitedStoreAry = implode(" ", $visitedStoreAry);
                Cookie::set(VISIT_COOKIE_NAME, $visitedStoreAry, time() + (86400 * 1));

                $time = date('Y-m-d');
                $result = $this->findFirst(['conditions'=>["store_id = ?", "visited_at = '$time'"], 'bind' => [$store_id]]);
                if(empty($result)) {
                    $this->insert([
                        'store_id' => $store_id, 
                        'visit_count' => 1,
                        'visit_day' => date('D'),
                        'visited_at' => date('Y-m-d')
                    ]);
                } else {
                    $this->query("UPDATE store_visit SET visit_count = $result->visit_count + 1 
                                        WHERE store_id = ? AND visited_at = '$time'", [$store_id]);
                }
            }  
        }

        public function lastSevenDayVisit($store_id) {
            $time = date('Y-m-d');
            $result = $this->query("SELECT visit_count, visited_at, visit_day FROM store_visit 
                                    WHERE store_id = ? AND visited_at <= NOW() ORDER BY id DESC LIMIT 7", [$store_id])->getResult();
            return $result;
        }

    }
?>