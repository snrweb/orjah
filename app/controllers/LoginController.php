<?php 
    namespace App\Controllers;
    use Core\Controller;
    use Core\Router;
    use Core\Sanitise;
    use Core\Validation;
    use Core\Session;

    class LoginController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Buyers');
            $this->loadModel('Stores');
            $this->loadModel('admin223');
            $this->view->setLayout('default');
        }

        //Login to store admin area
        public function indexAction() {
            $this->view->store_name = $this->view->store_password = '';
            $this->view->store_name_error = $this->view->store_password_error = '';

            /** validate input*/
            if ($_POST) {

                $validate = new Validation();
                $validate->check('$_POST', [
                    'store_name' => ['display' => 'Store name', 'required' => true],
                    'store_password' => ['display' => 'Password', 'required' => true]
                ]);

                /**Assign the input to values to the 
                 * corresponding Buyer class properties **/
                $this->StoresModel->assign($_POST);
                
                /**login store owner if vaidation is passed,
                 * store exist in the database and 
                 * password is correct**/
                if($validate->passed()) {
                    $store = $this->StoresModel->findStore();
                    
                    if(!empty($store)) {
                        if($store->store_name !== null && 
                            password_verify($this->StoresModel->store_password, $store->store_password)) {
                            $rememberMe = (isset($_POST['remember_me']) && Sanitise::get('remember_me')) ? true : false;

                            $this->StoresModel->store_id = $store->store_id; 
                            $this->StoresModel->login($rememberMe);
                            
                            Router::redirect('stores/admin');
                        } else {
                            $this->view->password_error = '<span class="inputError">Password is incorrect</span>'; 
                        }
                    } else {
                        $this->view->store_name_error = 
                                '<span class="inputError">This store does not exist in the database</span>';
                    }
                } else {
                    $inputErrors = $validate->getErrors();
                    foreach($inputErrors as $inputError) {
                        $this->view->{$inputError[1].'_error'} = '<span class="inputError">'.$inputError[0].'</span>';
                    }
                }
            }

            $this->view->render('login/login');
        }

        /***Login buyer */
        public function buyerAction() {

            /**Assign the input to values to the 
             * corresponding Buyer class properties **/
            $this->BuyersModel->assign($_POST);

            $this->view->email_error = $this->view->password_error = $this->view->email = '';

            if ($_POST) {
                $validate = new Validation();

                /**Validate all input */
                $validate->check('$_POST', [
                    'email' => ['display' => 'Email', 'required' => true, 'email' => true],
                    'password' => ['display' => 'Password', 'required' => true]
                ]);

                /*** Checks if validation is true, and then login buyer;
                 * 
                 * If the validation return false, errors will be outputed*/
                if($validate->passed()) {
                    $buyer = $this->BuyersModel->findByEmail();
                    $this->BuyersModel->buyer_id = $buyer->buyer_id;
                    $this->BuyersModel->buyer_name = $buyer->buyer_name;

                    if(!empty($buyer)) {
                        if($buyer->buyer_id !== null && password_verify($this->BuyersModel->password, $buyer->password)) {
                            $rememberMe = (isset($_POST['remember_me'])) ? true : false;
                            $this->BuyersModel->login($rememberMe);
                
                            Router::redirect('');
                        }
                    } else {
                        $this->view->email_error = 
                                    '<span class="inputError">This email does not exist in the database</span>';
                    }

                } else {
                    $inputErrors = $validate->getErrors();
                    foreach($inputErrors as $inputError) {
                        $this->view->{$inputError[1].'_error'} = '<span class="inputError">'.$inputError[0].'</span>';
                    }
                }
            }
            
            $this->view->render('login/buyer_login');
        }

        
        public function adminLoginAction() {
            $this->view->csrf_token_error = '';
            if ($_POST) {
                $validate = new Validation();
                $validate->check('$_POST', [
                    'admin_password' => ['display' => 'Password', 'required' => true]
                ], true);
                if($validate->passed()) {
                    $result = $this->admin223Model->findFirst(['conditions'=>'admin_username = ?', 'bind'=>[$_POST['admin_username']]]);
                    if(password_verify($_POST['admin_password'], $result->admin_password)) {
                        $this->admin223Model->admin_id = $result->admin_id; 
                        $this->admin223Model->admin_username = $result->admin_username; 
                        $this->admin223Model->login();
                        Router::redirect('admin223/');
                    } else {
                        Router::redirect('');
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('admin/login');
        }
    }
?> 