<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;

    class WhoIsLoggedInController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->APIheaders();
        }

        public function indexAction() {
            $whoIsLoggedIn = "none";
            
            if(Session::exists(BUYER_SESSION_NAME)) {
                $whoIsLoggedIn = "buyer";
            } elseif(Session::exists(STORE_SESSION_NAME)) {
                $whoIsLoggedIn = "store";
            }

            echo json_encode(["whoIsLoggedIn" => $whoIsLoggedIn]);
        }


    }

?>