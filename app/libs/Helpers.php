<?php 
    use Core\Session;
    use App\Models\Stores;
    use App\Models\Buyers;

    /*******
     * This is for debugging
     * *********/
    function dnd ($data) {
        echo '<pre>';
            var_dump($data);
        echo '</pre>';
        die();
    }

    /*************
     * To check if the buyer/store owner
     * is logged in or not.
     * *************** */
    function isLoggedIn() {
        if(Session::get('type') === 'buyer') {
            return Buyers::isloggedIn();
        } elseif(Session::get('type') === 'store') {
            return Stores::isloggedIn();
        }
    }

    function timeFormatter($dbtime) {
        $time = date('Y-m-d H:i:s', time());
        $time1 = new DateTime($time);
        $time2 = new DateTime($dbtime);
        $diff = $time2->diff($time1);
        $interval;
        
        if($diff->y > 0) {
            $strTime = strtotime($dbtime);
            $interval = date('j M Y', $strTime);
        }
        elseif($diff->m > 0) {
            $strTime = strtotime($dbtime);
            $interval = date('j M', $strTime);
        }
        elseif($diff->d > 0) {
            $interval = $diff->d.(" d");
        }
        elseif($diff->h > 0) {
            $interval = $diff->h.(" h");
        }
        elseif($diff->i > 0) {
            $interval = $diff->i.(" m");
        }
        elseif($diff->s >= 0) {
            $interval = "Just now";
        }
        return $interval;
    }
    
?>