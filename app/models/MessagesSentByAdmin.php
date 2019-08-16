<?php
    namespace App\Models;
    use Core\Model;

    class MessagesSentByAdmin extends Model {
        public $message_id, $sender, $store_id, $store_name, $subject, $message, $created_at;

        public function __construct() {
            parent::__construct('messages_sent_by_admin');
        }

        public function saveMessage($store_id, $store_name, $store_email, $subject, $message) {
            return $this->insert([
                'store_id' => $store_id,
                'store_name' => $store_name,
                'store_email' => $store_email,
                'subject' => $subject,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

    }
?>