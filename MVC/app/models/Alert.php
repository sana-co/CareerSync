<?php

class Alert {
    use Model;

    public function getUnreadAlerts() {
        $query = "SELECT * FROM alerts WHERE is_read = 0 ORDER BY created_at DESC";
        return $this->query($query);
    }

    public function createAlert($title, $message, $type = 'info') {
        $query = "INSERT INTO alerts (title, message, type) 
                  VALUES (?, ?, ?)";

        return $this->query($query, [
            $title,
            $message,
            $type
        ]);
    }

    public function markAllAsRead() {
        $query = "UPDATE alerts SET is_read = 1 WHERE is_read = 0";
        return $this->query($query);
    }

    public function exists($title, $message) {
        $query = "SELECT id FROM alerts 
                  WHERE title = ? 
                  AND message = ? 
                  AND is_read = 0 
                  LIMIT 1";

        $result = $this->query($query, [
            $title,
            $message
        ]);

        return !empty($result);
    }

    public function deleteAlert($id) {
        $query = "DELETE FROM alerts WHERE id = ?";
        return $this->query($query, [$id]);
    }
}