<?php

require_once __DIR__ . '/../config/Database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class User
{
    private $conn;
    private $usersTable;
    private $mailPassword;
    private $mailPort;
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
        $this->usersTable = $_ENV['USERS_TABLE'];
        $this->mailPassword = $_ENV['MAIL_PASSWORD'];
        $this->mailPort = $_ENV['MAIL_PORT'];
    }
    private function sendVerification($email, $firstName, $code)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rendyllcabardo11@gmail.com';
            $mail->Password = $this->mailPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->mailPort;

            $mail->setFrom('rendyllcabardo11@gmail.com', 'Rendyll Ryan');
            $mail->addAddress($email);

            $verificationLink = "http://localhost/Stupidyante/views/auth/verify_email.php?token=$code";

            $mail->isHTML(true);
            $mail->Subject = 'Verify your email address';
            $mail->Body = "Hello, $firstName!<br><br>Please click the link below to verify your email:<br><a href='$verificationLink'>Verify your email</a>";

            $mail->send();

            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function verifyUser($token)
    {
        try {
            $query = "SELECT * FROM {$this->usersTable} WHERE verification_code = :token AND is_verified = 0";
            $stmnt = $this->conn->prepare($query);
            $stmnt->bindParam(':token', $token);
            $stmnt->execute();

            $user = $stmnt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $query = "UPDATE {$this->usersTable} SET is_verified = 1 WHERE id = :id";
                $stmnt = $this->conn->prepare($query);
                $stmnt->bindParam(':id', $user['id']);

                return $stmnt->execute();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }

    }
    public function sendTwoStepVerification($email, $firstName, $mfaCode)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rendyllcabardo11@gmail.com';
            $mail->Password = $this->mailPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $this->mailPort;

            $mail->setFrom('rendyllcabardo11@gmail.com', 'Rendyll Ryan');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Verify your email address';
            $mail->Body = "Hello, $firstName!<br><br>Here is your verification code in order to login to Stupidyante: $mfaCode";

            $mail->send();

            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
    public function registerUser($userData)
    {
        try {
            $code = bin2hex(random_bytes(16));

            $query = "INSERT INTO $this->usersTable 
        (first_name, last_name, email, password, gender, phone_number, course, address, birthdate, profile_path, role, verification_code) 
        VALUES 
        (:first_name, :last_name, :email, :password, :gender, :phone_number, :course, :address, :birthdate, :profile_path, :role, :code)";

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
            $stmnt->bindParam(':code', $code);

            if ($stmnt->execute()) {
                $emailResult = $this->sendVerification($userData['email'], $userData['first_name'], $code);

                if ($emailResult === true) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        } catch (PDOException $e) {
            return false;
        }
    }
    public function findByEmail($email)
    {
        try {
            $query = "SELECT * FROM $this->usersTable WHERE email = :email AND is_verified = 1 LIMIT 1";
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
    public function getStudentById($studentId)
    {
        try {
            $query = "SELECT * FROM $this->usersTable WHERE id = :student_id";
            $stmnt = $this->conn->prepare($query);
            $stmnt->bindParam(':student_id', $studentId);
            $stmnt->execute();

            return $stmnt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function editStudent($studentDetails)
    {
        try {
            $query = "UPDATE $this->usersTable SET first_name = :first_name, last_name = :last_name, gender = :gender, phone_number = :phone_number, 
                        course = :course, address = :address, birthdate = :birthdate, profile_path = :profile_path WHERE id = :id";
            $stmnt = $this->conn->prepare($query);

            $stmnt->bindParam(':first_name', $studentDetails['first_name']);
            $stmnt->bindParam(':last_name', $studentDetails['last_name']);
            $stmnt->bindParam(':gender', $studentDetails['gender']);
            $stmnt->bindParam(':phone_number', $studentDetails['phone_number']);
            $stmnt->bindParam(':course', $studentDetails['course']);
            $stmnt->bindParam(':address', $studentDetails['address']);
            $stmnt->bindParam(':birthdate', $studentDetails['birthdate']);
            $stmnt->bindParam(':profile_path', $studentDetails['profile_path']);
            $stmnt->bindParam(':id', $studentDetails['student_id']);

            return $stmnt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

}

?>
