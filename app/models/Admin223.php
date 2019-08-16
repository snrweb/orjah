<?php
    namespace App\Models;
    use Core\Model;
    use Core\Session;
    use Core\Cookie;

    class Admin223 extends Model {
        public $admin_id, $admin_username, $admin_name, $created_at, $csrf_token;

        public function __construct() {
            parent::__construct('admin223');
        }

        public function login() {
            Session::set(ADMIN_SESSION_NAME, $this->admin_id);
            Session::set('admin_username', $this->admin_username);
        }

    }
?>