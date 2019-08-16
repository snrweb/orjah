<?php
    namespace App\Controllers;
    use Core\Controller;
    use Core\Validation;
    use Core\Sanitise;
    use Core\Router;
    use App\Libs\Email;
    use App\Libs\EmailLayout;

    class PasswordController extends Controller {
        private $email; 
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->loadModel('Stores');
            $this->loadModel('Buyers');
            $this->view->setLayout('default');
            $this->email = new Email();
        }

        public function forgotAction($userType) {
            $this->view->urlParams = $userType;
            $code = md5(uniqid(true));

            if($_POST) {
                if($userType == 'store') {
                    $this->storeForgotPassword($code);
                } else {
                    $this->buyerForgotPassword($code);
                }
            }

            $this->view->render('password/forgot');
        }

        private function buyerForgotPassword($code) {
            $validate = new Validation();
            $validate->check('$_POST', [
                'email' => ['display'=>'Email', 'required'=>true, 'email'=>true]
            ]);

            if($validate->passed()) {
                $this->BuyersModel->email = sanitise::get('email');
                if(!empty($this->BuyersModel->findByEmail())) {
                    $this->BuyersModel->updateRetrieveCode($code);
                    
                    $this->email->setEmailSubject('Password Reset Link');
                    $this->email->setRecipientEmail($_POST['email']);
                    $content = EmailLayout::passwordLayout('buyer', $code, $_POST['email']);
                    $this->email->setEmailContent($content);

                    if($this->email->sendEmail()) return $this->view->success ='A reset link has been sent to your email';
                    return $this->view->danger ='There was connection error';
                } 
                return $this->view->danger ='Email does not exist in the database';
            } 
            $this->view->danger ='Enter a valid email address';
        }

        private function storeForgotPassword($code) {
            $validate = new Validation();
            $validate->check('$_POST', [
                'store_name' => ['display'=>'Store name', 'required'=>true, 'isStoreName'=>true]
            ]);

            if($validate->passed()) {
                $this->StoresModel->store_name = sanitise::get('store_name');
                $result = $this->StoresModel->findStore();
                if(!empty($result) && $this->StoresModel->updateRetrieveCode($code) == true) {
                    $this->email->setEmailSubject('Password Reset Link');
                    $this->email->setRecipientEmail($result->store_email);
                    $content = EmailLayout::passwordLayout('store', $code, str_replace(' ', '-', sanitise::get('store_name')));
                    $this->email->setEmailContent($content);
                    
                    if($this->email->sendEmail()) return $this->view->success ='A reset link has been sent to your email';
                    return $this->view->danger ='There was connection error';
                } 
                return $this->view->danger ='Store name does not exist in the database';
            } 
            return $this->view->danger ='Enter a valid store name';
        }

        public function ResetAction($userType, $code='', $email='') {
            $this->view->type = $userType; 
            $this->view->code = $code; 
            $this->view->email = $email; 
            if($_POST) {
                $validate = new Validation();
                $validate->check('$_POST', [
                    'password_one' => ['display' => 'Password', 'min' => 8, 'required' => true]
                ]);
                    
                if($validate->passed()) {
                    $password = Sanitise::get('password_one');
                    $confirm_password = Sanitise::get('password_two');

                    if($password === $confirm_password) {
                        if($userType == 'buyer') {
                            $this->BuyersModel->password = password_hash($password, PASSWORD_DEFAULT);
                            $this->BuyersModel->pwd_retrieve = '';
                            $this->BuyersModel->email = $email;
                            $registered = $this->BuyersModel->resetPassword();   
                        } else {
                            $this->StoresModel->store_password = password_hash($password, PASSWORD_DEFAULT); 
                            $this->StoresModel->store_name = str_replace('-', ' ', $email); //$email = store name
                            $this->StoresModel->store_pwd_retrieve = '';
                            $registered = $this->StoresModel->resetPassword();    
                        }
                        ($registered) ? Router::redirect('login') : $this->view->danger = 'Error encountered'; 
                    } 
                    $this->view->danger = 'Password does not match';
                }
                $this->view->danger = 'Password should not be less than 8 characters';
            }
            
            $this->view->render('password/reset');
        }


    }

?>