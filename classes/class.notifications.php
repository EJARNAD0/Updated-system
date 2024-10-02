<?php
class Notification {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'db_plato';
    private $conn;

    public function __construct() {
        $this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
    }

    // Send notification to a user using username
    public function send_notification_by_username($username, $message) {
        // Retrieve user_id from the username
        $sql = "SELECT user_id FROM tbl_users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user_id = $stmt->fetchColumn();

        // Check if the user exists
        if (!$user_id) {
            throw new Exception("User with username '$username' not found.");
        }

        // Proceed with sending the notification
        $sql = "INSERT INTO notifications (user_id, message) VALUES (:user_id, :message)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id, 'message' => $message]);
        return true;
    }

    // Retrieve notifications for a user by username
    public function get_notifications_by_username($username, $only_unread = false) {
        // Retrieve user_id from the username
        $sql = "SELECT user_id FROM tbl_users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user_id = $stmt->fetchColumn();

        // Check if the user exists
        if (!$user_id) {
            throw new Exception("User with username '$username' not found.");
        }

        // Retrieve notifications
        if ($only_unread) {
            $sql = "SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC";
        } else {
            $sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $notifications ?: false;
    }

    // Mark a specific notification as read
    public function mark_as_read($notification_id) {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = :notification_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['notification_id' => $notification_id]);
        return true;
    }

    // Mark all notifications for a user as read by username
    public function mark_all_as_read_by_username($username) {
        // Retrieve user_id from the username
        $sql = "SELECT user_id FROM tbl_users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user_id = $stmt->fetchColumn();

        // Check if the user exists
        if (!$user_id) {
            throw new Exception("User with username '$username' not found.");
        }

        // Mark all notifications as read for the user
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return true;
    }

    // Delete a specific notification by notification ID
    public function delete_notification($notification_id) {
        $sql = "DELETE FROM notifications WHERE notification_id = :notification_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['notification_id' => $notification_id]);
        return true;
    }

    // Delete all notifications for a user by username
    public function delete_all_notifications_by_username($username) {
        // Retrieve user_id from the username
        $sql = "SELECT user_id FROM tbl_users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user_id = $stmt->fetchColumn();

        // Check if the user exists
        if (!$user_id) {
            throw new Exception("User with username '$username' not found.");
        }

        // Delete all notifications for the user
        $sql = "DELETE FROM notifications WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return true;
    }
}
