<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Session;
    use Core\Router;

    /***
     * Logout any current user, either a buyer
     * or a store owner
     */
    class LogoutController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Buyers'); 
            $this->loadModel('Stores');
            $this->view->setLayout('default');
        }

        public function indexAction() {
            if(isLoggedIn()) {
                if(Session::get('type') === 'buyer') {
                    $this->BuyersModel->logout();
                } elseif(Session::get('type') === 'store') {
                    $this->StoresModel->logout();
                }
                
                Router::redirect('');
            }
        }
    }
?> 