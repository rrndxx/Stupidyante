<?php

require_once '../config/Database.php';

class User
{
    private $conn;
    private $usersTable = "users";
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function registerUser($userData)
    {
        try {
            $query = "INSERT INTO $this->usersTable 
        (first_name, last_name, email, password, gender, phone_number, course, address, birthdate, profile_path, role) 
        VALUES 
        (:first_name, :last_name, :email, :password, :gender, :phone_number, :course, :address, :birthdate, :profile_path, :role)";

            $stmnt = $this->conn->prepare($query);

            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

            $stmnt->bindParam(':first_name', $userData['first_name']);
            $stmnt->bindParam(':last_name', $userData['last_name']);
            $stmnt->bindParam(':email', $userData['email']);
            $stmnt->bindParam(':password', $hashedPassword);
            $stmnt->bindParam(':gender', $userData['gender']);
            $stmnt->bindParam(':phone_number', $userData['phone_number']);
            $stmnt->bindParam(':course', $userData['course']);
            $stmnt->bindParam(':address', $userData['address']);
            $stmnt->bindParam(':birthdate', $userData['birthdate']);
            $stmnt->bindParam(':profile_path', $userData['profile_path']);
            $stmnt->bindParam(':role', $userData['role']);

            return $stmnt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    public function findByEmail($email)
    {
        try {
            $query = "SELECT * FROM $this->usersTable WHERE email = :email LIMIT 1";
            $stmnt = $this->conn->prepare($query);
            $stmnt->bindParam(':email', $email);
            $stmnt->execute();
            return $stmnt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllStudents()
    {
        try {
            $query = "SELECT * FROM $this->usersTable WHERE role = 'student'";
            $stmnt = $this->conn->prepare($query);
            $stmnt->execute();
            return $stmnt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getStudentCount()
    {
        try {
            $query = "SELECT COUNT(*) AS total FROM $this->usersTable WHERE role = 'student'";
            $stmnt = $this->conn->prepare($query);
            $stmnt->execute();
            $result = $stmnt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            return false;
        }
    }

}

?>