<?php
    namespace App\Models;
    use Core\Model;

    class MessagesSentToAdmin extends Model {
        public $message_id, $sender_name, $sender_email, $subject, $message, $created_at;

        public function __construct() {
            parent::__construct('messages_sent_to_admin');
        }

        public function saveMessage() {
            return $this->insert([
                'sender_name' => $this->sender_name,
                'sender_email' => $this->sender_email,
                'subject' => $this->subject,
                'message' => $this->message,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

    }
?>