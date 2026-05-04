<?php
class ContactModel {
    use Model;

    protected $table = "feedback";
    protected $allowedColumns = [
        'name',
        'email',
        'message',
    ];

    public function __construct() {
        // override the order_column defined in the trait dynamically
        $this->order_column = "id"; // safe dynamic assignment
        $this->order_type   = "desc"; // optional
    }

    // Helper method to select all feedback
    public function SelectAll() {
        return $this->query("SELECT * FROM {$this->table} ORDER BY {$this->order_column} {$this->order_type}");
    }

    // Delete a single feedback by id
    public function deleteMessage($id) {
        // Use prepared query to avoid SQL injection
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }
}