<?php

namespace app\models;

use vendor\core\base\Model;

class User extends Model
{

    public $attributes = [
        'login' => '',
        'password' => '',
        'email' => '',
        'notifications' => ''
    ];

    public function load($data)
    {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    private function checkEmail($email)
    {
        if (!$email) {
            $this->errors['email'][] = 'Email is required';
        }
        if (!preg_match("/^([a-zA-Z0-9\-.]+)(\@){1}([a-zA-Z0-9\-.]+)$/", $email)) {
            $this->errors['email'][] = 'Email contains not significant characters';
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors['email'][] = 'Email is not a valid address';
        }
    }

    private function checkLogin($login)
    {
        if (!$login) {
            $this->errors['login'][] = 'Login is required';
        }
        if (!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
            $this->errors['login'][] = 'Login contains only valid characters: a-Z, A-Z, 0-9';
        }
        if (strlen($login) < MIN_LENGTH) {
            $this->errors['login'][] = 'Login must be at least ' . MIN_LENGTH . ' characters long';
        }
        if (strlen($login) > MAX_LENGTH) {
            $this->errors['login'][] = 'Login must be not more than ' . MAX_LENGTH . ' characters';
        }
        if (!preg_match("/[a-zA-Z]+/", $login)) {
            $this->errors['login'][] = 'Login must contains at least 1 alphabet character';
        }
    }

    private function checkPassword($password)
    {
        if (!$password) {
            $this->errors['password'][] = 'Password is required';
        }
        if (!preg_match("/^[a-zA-Z0-9!#-\.$]+$/", $password)) {
            $this->errors['password'][] = 'Password contains only valid characters: a-Z, A-Z, 0-9, !, #, -, ., $';
        }
        if (strlen($password) < MIN_LENGTH) {
            $this->errors['password'][] = 'Password must be at least ' . MIN_LENGTH . ' characters long';
        }
        if (strlen($password) > MAX_LENGTH) {
            $this->errors['password'][] = 'Password must be not more than ' . MAX_LENGTH . ' characters';
        }
    }

    public function validate($data)
    {
        foreach ($data as $key => $value) {
            if ($key === 'login') {
                $this->checkLogin($data[$key]);
            }
            if ($key === 'email') {
                $this->checkEmail($data[$key]);
            }
            if ($key === 'password') {
                $this->checkPassword($data[$key]);
            }
        }
        if ($this->errors) {
            foreach ($this->errors as $error) {
                if ($error) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getErrors()
    {
        $errors = '<ul>';
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $errors .= "<li>$item</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors;
    }

    public function checkUnique()
    {
        foreach ($this->attributes as $name => $value) {
            if ($name === 'login' || $name === 'email') {
                if ($this->findBySql("SELECT * FROM `users` WHERE $name = ? LIMIT 1", [$value])) {
                    $this->errors['unique'][] = "User with same $name already exist";
                }
            }
        }
        if ($this->errors['unique']) {
            return false;
        }
        return true;
    }

    public function save($table)
    {
        $fields = '';
        $values = '';
        foreach ($this->attributes as $name => $value) {
            $fields .= '`' . $name . '`, ';
            $values .= '\'' . $value . '\', ';
        }
        $fields = rtrim($fields, ', ');
        $values = rtrim($values, ', ');
        $this->query("INSERT INTO $table ($fields) VALUES ($values)");
    }

    public function login()
    {
        $login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : NULL;
        $password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : NULL;
        if ($login && $password) {
            $user = $this->findBySql("SELECT * FROM `users` WHERE login = ? LIMIT 1", [$login]);
            if ($user) {
                if (password_verify($password, $user[0]['password'])) {
                    foreach ($user[0] as $field => $value) {
                        if ($field != 'password') {
                            $_SESSION['user'][$field] = $value;
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }
}