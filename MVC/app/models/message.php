<?php
class Message {
    use Model;

    protected $table = 'messages';
    protected $allowedColumns = ['receiver_id', 'receiver_type', 'content', 'is_read'];

    public function getByReceiver($receiverId, $receiverType)
    {
        $query = "SELECT * FROM $this->table WHERE receiver_id = ? AND receiver_type = ? ORDER BY created_at DESC LIMIT 10";
        return $this->query($query, [$receiverId, $receiverType]);
    }
}