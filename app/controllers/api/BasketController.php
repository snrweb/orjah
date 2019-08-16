<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;

    class BasketController extends Controller{
        private $store_name;
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->APIheaders();
            $this->loadModel('Baskets');
        }

        public function indexAction() {
            $basketItems = $this->BasketsModel->getBasket();
            echo json_encode(["basketItems" => $basketItems]);
        }

        public function removeAction($basket_id) {
            if($this->BasketsModel->deleteFromBasket($basket_id)) {
                echo json_encode(true);
            } else {
                echo json_encode(false);
            }
        }
        
    }

?>