<?php

class Database
{
    private $host = "localhost";
    private $user = "root";
    private $pass = "1983";
    private $dbname = "demoex_kor";
    public $conn;

    public function getConn()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->conn->exec("set names utf8");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }

        return $this->conn;
    }

}

class User
{
    private $conn;
    private $table = "user";

    public $login;
    public $passwd;
    public $fio;
    public $phonenum;
    public $email;
    public $admin_right = 0;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Проверка существования пользователя по логину или email
    public function exists()
    {
        $query = "SELECT id FROM " . $this->table . " WHERE login = :login OR email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":login", $this->login);
        $stmt->bindParam(":email", $this->email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Регистрация пользователя
    public function register()
    {
        $query = "INSERT INTO " . $this->table . " (login, passwd, fio, phonenum, email, admin_right) VALUES (:login, :passwd, :fio, :phonenum, :email, :admin_right)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":login", $this->login);
        $stmt->bindParam(":passwd", $this->passwd); // В реальном проекте используйте password_hash
        $stmt->bindParam(":fio", $this->fio);
        $stmt->bindParam(":phonenum", $this->phonenum);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":admin_right", $this->admin_right);
        return $stmt->execute();
    }

    // Авторизация (логин)
    public function login()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE login = :login";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":login", $this->login);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // В реальном проекте используйте password_verify
            if ($this->passwd == $row['passwd']) {
                return true;
            }
        }
        return false;
    }
}

class EduForm
{
    private $conn;
    private $table = "edu_form";

    public $course_name;
    public $target_date;
    public $pay_type;
    public $user_fk;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Создание новой заявки
    public function create()
    {
        $query = "INSERT INTO $this->table (course_name, target_date, pay_type, user_fk, view_status) VALUES (:course_name, :target_date, :pay_type, :user_fk, 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":course_name", $this->course_name);
        $stmt->bindParam(":target_date", $this->target_date);
        $stmt->bindParam(":pay_type", $this->pay_type);
        $stmt->bindParam(":user_fk", $this->user_fk);
        return $stmt->execute();
    }
}
