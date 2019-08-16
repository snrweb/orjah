<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Router;
    use Core\Sanitise;
    use Core\Validation;
    use Core\Categories;

    class RegisterController extends Controller {

        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Stores');
            $this->loadModel('Buyers');
            $this->view->setLayout('default');
        }
        
        public function indexAction() {
            //Error variables initialisation
            $this->view->store_email_error = $this->view->store_name_error = $this->view->store_category_error = ''; 
            $this->view->store_country_error = $this->view->store_city_error = $this->view->store_street_error = '';
            $this->view->store_password_error = $this->view->registrationError = '';
            $this->view->csrf_token_error = '';
            
            //Select all categories in the database
            $this->view->category = Categories::list();

            //Initialise these view properties used in the registration form
            $this->view->email = $this->view->store_name = $this->view->store_category = '';
            $this->view->store_country = $this->view->store_city = $this->view->store_street = '';

            if($_POST) {

                /**Assigns the value of $_POST properties to the properties of the 
                 * StoresModel object*/
                $this->StoresModel->assign($_POST);
                foreach($_POST as $key => $value) {
                    $this->view->$key = $this->StoresModel->$key;
                }

                //instantiate the Validation class
                $validate = new Validation();
                $validate->check('$_POST', $this->StoresModel->validateRegistration());
                
                /***
                 * The store is registered, if validation is successful,
                 * otherwise errors for each input are returned 
                 **/
                if($validate->passed()) {
                    $password = Sanitise::get('store_password');
                    $confirm_password = Sanitise::get('confirm_password');

                    if ($password === $confirm_password) {
                        $this->StoresModel->store_password = password_hash($password, PASSWORD_DEFAULT);

                        $registered = $this->StoresModel->register(); 
                        $error = '<span class="inputError">Registration error</span>';
                        ($registered) ? Router::redirect('login') : $this->view->registrationError = $error;
                    } else {
                        $this->view->passwordError = '<span class="inputError">Password does not match</span>';
                    }

                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('login/register');
        }



        public function buyerAction() {
            $this->view->password_error = $this->view->registrationError = ''; 
            $this->view->buyer_name_error = $this->view->email_error = '';
            $this->view->email = $this->view->buyer_name = '';
            $this->view->csrf_token_error = '';
            if($_POST) {
                $this->BuyersModel->assign($_POST);

                $validate = new Validation();
                $validate->check('$_POST', $this->BuyersModel->validateRegistration());
                
                /***
                 * If there are no error,
                 * => register the buyer 
                 * => redirect to login page
                 * 
                 * Else, errors are returned and outputed
                 */
                $this->view->buyer_name = Sanitise::get('buyer_name');
                $this->view->email = Sanitise::get('email');
                if($validate->passed()) {
                    $password = Sanitise::get('password');
                    $confirm_password = Sanitise::get('confirm_password');

                    if ($password === $confirm_password) {
                        $this->BuyersModel->password = password_hash($password, PASSWORD_DEFAULT);
                        $registered = $this->BuyersModel->register(); 
                        if ($registered) Router::redirect('login/buyer');

                    } else {
                        $this->view->passwordError = '<span class="inputError">Password does not match</span>';
                    }

                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->render('login/buyer_register');
        }

    }

?>