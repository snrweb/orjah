<?php
    namespace App\Controllers\API;
    use Core\Controller;
    use Core\Session;
    use Core\Router;
    use Core\Validation;
    use App\Libs\Email;
    use App\Libs\EmailLayout;

    class MessageController extends Controller{
        private $store_name, $email;
        
        public function __construct($controller, $action) {
            parent::__construct($controller, $action);
            $this->APIheaders();

            $this->loadModel('Messages');
            $this->loadModel('Products');
            $this->loadModel('Stores');
            $this->loadModel('Baskets');
            $this->loadModel('Orders');
            $this->loadModel('MessagesSentToAdmin');
            
            $this->email = new Email();
        }

        public function indexAction() {
            if(Session::exists(BUYER_SESSION_NAME) || Session::exists(STORE_SESSION_NAME)) {
                $messages = $this->MessagesModel->fetchMessages();
                echo json_encode(["messages" => $messages]);
            }
        }

        public function readAction($params) {
            if(Session::exists(BUYER_SESSION_NAME) || Session::exists(STORE_SESSION_NAME)) {
                $userID =  Session::exists(BUYER_SESSION_NAME) ? Session::get(BUYER_SESSION_NAME) : Session::get(STORE_SESSION_NAME);
                if($params != '') {
                    $messageLists = $this->MessagesModel->fetchUniqueMessages($params);
                    echo json_encode(["messageLists" => $messageLists, "userID" => $userID]);
                }
            }
        }

        public function sendAction($receiver_id) {
            if($_POST && $receiver_id != '') {
                $this->MessagesModel->assign($_POST);

                $unique_id; $sender_id;

                if(Session::exists(BUYER_SESSION_NAME)) {
                    $sender_id = $this->MessagesModel->sender_id = Session::get(BUYER_SESSION_NAME);
                    $sender_type = $this->MessagesModel->sender_type = 'buyer';
                    $unique_id = $this->MessagesModel->unique_id = $receiver_id.'-'.$sender_id;
                } elseif(Session::exists(STORE_SESSION_NAME)) {
                    $sender_id = $this->MessagesModel->sender_id = Session::get(STORE_SESSION_NAME);
                    $sender_type = $this->MessagesModel->sender_type = 'store';
                    $unique_id = $this->MessagesModel->unique_id = $sender_id.'-'.$receiver_id;
                }

                $this->MessagesModel->receiver_id = $receiver_id;
                $inserted = $this->MessagesModel->sendMessage();
                if($inserted) {
                    $messageLists = $this->MessagesModel->fetchUniqueMessages($unique_id);
                    echo json_encode(["messageLists" => $messageLists]);
                } else {
                    echo json_encode(false);
                }
            }
        }

        public function contactAction($receiver_id, $sender_name = '') {
            $this->MessagesModel->assign($_POST);
            $validate = new Validation();
            $validate->check('$_POST', [
                'message' => ['display' => 'Message', 'required' => true, 'max' => 1000]
            ]);

            if(Session::exists(BUYER_SESSION_NAME)) {
                $this->MessagesModel->sender_id = Session::get(BUYER_SESSION_NAME);
                $sender_type = $this->MessagesModel->sender_type = 'buyer';
                $this->MessagesModel->sender_name = Session::get(BUYER_NAME);
                $this->MessagesModel->unique_id = $receiver_id.'-'.Session::get(BUYER_SESSION_NAME);
            }

            if(Session::exists(STORE_SESSION_NAME)) {
                $this->MessagesModel->sender_id = Session::get(STORE_SESSION_NAME);
                $sender_type = $this->MessagesModel->sender_type = 'store';
                $this->MessagesModel->sender_name = $sender_name;
                $this->MessagesModel->unique_id = Session::get(STORE_SESSION_NAME).'-'.$receiver_id;
            }

            $this->MessagesModel->receiver_id = $receiver_id;

            if($validate->passed()) {
                if($inserted = $this->MessagesModel->sendMessage()) return true;
                return false;
            } else {
                return false;
            }
        }

        public function contactAdminAction() {
            $this->view->urlParams = 'contactOrjah';
            $this->view->csrf_token_error = $this->view->message_error = '';
            if($_POST) {
                $this->MessagesSentToAdminModel->assign($_POST);
                $validate = new Validation();
                $validate->check('$_POST', [
                    'message' => ['display' => 'Message', 'required' => true, 'max' => 1000],
                    'subject' => ['display' => 'Message title', 'required' => true, 'max' => 200],
                    'sender_email' => ['display' => 'Email', 'required' => true, 'email' => true],
                    'sender_name' => ['display' => 'Message', 'required' => true, 'letters' => true]
                ], true );

                if($validate->passed()) {
                    $this->email->setEmailSubject($_POST['subject']);
                    $this->email->setRecipientEmail('Awelewa_tobi@yahoo.com');
                    $content = EmailLayout::AdminMsgLayout($_POST['sender_email'], $_POST['sender_name'], $_POST['message']);
                    $this->email->setEmailContent($content);
                    if($this->email->sendEmail()) {
                        $this->MessagesSentToAdminModel->saveMessage();
                        return true;
                    }
                } else {
                    $this->view->setFormErrors($validate->getErrors());
                }
            }
            $this->view->setLayout('default');
            $this->view->render('home/contact');
        }

    }

?>