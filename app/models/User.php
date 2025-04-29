<?php
require_once __DIR__ . '/Model.php';

class User extends Model {
    protected $table = 'users';

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (first_name, last_name, email, gender, phone, course, address, birthdate, profile_picture, password, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['firstName'],
            $data['lastName'],
            $data['email'],
            $data['gender'],
            $data['phone'],
            $data['course'],
            $data['address'],
            $data['birthdate'],
            $data['profile_picture'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['role'] ?? 'student'
        ]);
    }

    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function isAdmin($userId) {
        $user = $this->findById($userId);
        return $user && $user['role'] === 'admin';
    }

    public function isStudent($userId) {
        $user = $this->findById($userId);
        return $user && $user['role'] === 'student';
    }

    public function getAllStudents() {
        $sql = "SELECT * FROM {$this->table} WHERE role = 'student' ORDER BY first_name, last_name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 