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
            if ($key === 'password' || $key === 'new-password') {
                $this->checkPassword($data[$key]);
            }
        }
        if (!empty($data['new_password'])) {
            if ($data['new_password'] !== $data['repeat']) {
                $this->errors['password'][] = 'Passwords doesn\'t match';
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

    public function activateAccount($login, $to, $token)
    {
        $encoding = "utf-8";
        $subject_preferences = array(
            "input-charset" => $encoding,
            "output-charset" => $encoding,
            "line-length" => 76,
            "line-break-chars" => "\r\n"
        );
        $from_name = 'Camagru';
        $from_mail = 'camagru@example.com';
        $header = "Content-type: text/html; charset=" . $encoding . " \r\n";
        $header .= "From: " . $from_name . " <" . $from_mail . "> \r\n";
        $header .= "MIME-Version: 1.0 \r\n";
        $header .= "Content-Transfer-Encoding: 8bit \r\n";
        $header .= "Date: " . date("r (T)") . " \r\n";
        $header .= iconv_mime_encode("Subject", $from_name . ' <' . $from_mail . '> ', $subject_preferences);
        $resetPassLink = 'http://localhost:8100/user/activate-account/?token=' . $token;
        if (!empty($_SESSION['user'])) {
            $subject = "Activate your Camagru account";
            $mailContent = 'Dear ' . $login . ',';
        } else {
            $subject = "Thank you for registering Camagru";
            $mailContent = 'Dear ' . $login . ',';
            $mailContent .= '<br/>Thank you for registering with Camagru! Your account has been successfully created. Welcome to the Camagru family!';
        }
        $mailContent .= '<br/>To access all features, activate your account now. Please click the following link to activate your account: <a href="' . $resetPassLink . '">' . $resetPassLink . '</a>';
        $mailContent .= '<br/> If the link doesn\'t work, copy it into the address bar of your browser.';
        $mailContent .= '<br/><br/>Regards,';
        $mailContent .= '<br/>Camagru';
        mail($to, $subject, $mailContent, $header);
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

    public function findEmail($email)
    {
        $result = $this->findBySql("SELECT * FROM `users` WHERE `email` = ?", [$email]);
        if (!empty($result)) return true;
        return false;
    }

    public function resetPassword($email)
    {
        $to = $email;
        $login = $this->findBySql("SELECT `login` FROM `users` WHERE `email` = ? LIMIT 1", [$email]);
        $login = $login[0]['login'];
        $token = $this->findBySql("SELECT `token` FROM `users` WHERE `email` = ? LIMIT 1", [$email]);
        $token = $token[0]['token'];

        $encoding = "utf-8";
        $subject_preferences = array(
            "input-charset" => $encoding,
            "output-charset" => $encoding,
            "line-length" => 76,
            "line-break-chars" => "\r\n"
        );
        $from_name = 'Camagru';
        $from_mail = 'camagru@example.com';
        $header = "Content-type: text/html; charset=" . $encoding . " \r\n";
        $header .= "From: " . $from_name . " <" . $from_mail . "> \r\n";
        $header .= "MIME-Version: 1.0 \r\n";
        $header .= "Content-Transfer-Encoding: 8bit \r\n";
        $header .= "Date: " . date("r (T)") . " \r\n";
        $header .= iconv_mime_encode("Subject", $from_name . ' <' . $from_mail . '> ', $subject_preferences);
        $resetPassLink = 'http://localhost:8100/user/new-password/?token=' . $token;
        $subject = "Password Update Request";
        $mailContent = 'Dear ' . $login . ',';
        $mailContent .= '<br/>Recently a request was submitted to reset a password for your account. If this was a mistake, just ignore this email and nothing will happen.';
        $mailContent .= '<br/>To reset your password, visit the following link: <a href="' . $resetPassLink . '">' . $resetPassLink . '</a>';
        $mailContent .= '<br/><br/>Regards,';
        $mailContent .= '<br/>Camagru';
        mail($to, $subject, $mailContent, $header);
    }

    public function updatePassword($password, $user_id)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->query("UPDATE `users` SET `password` = '$password' WHERE `user_id` = '$user_id'");
    }

    public function updateToken($user_id)
    {
        $token = md5(rand(999, 99999));
        $this->query("UPDATE `users` SET `token` = '$token' WHERE `user_id` = '$user_id'");
    }

    public function updateNotifications($notifications, $user_id)
    {
        $this->query("UPDATE `users` SET `notifications` = '$notifications' WHERE `user_id` = '$user_id'");
    }

    public function updateLogin($login, $user_id)
    {
        $this->query("UPDATE `users` SET `login` = '$login' WHERE `user_id` = '$user_id'");
        $this->query("UPDATE `likes` SET `login` = '$login' WHERE `user_id` = '$user_id'");
        $this->query("UPDATE `images` SET `login` = '$login' WHERE `user_id` = '$user_id'");
    }

    public function updateEmail($email, $user_id)
    {
        $this->query("UPDATE `users` SET `email` = '$email' WHERE `user_id` = '$user_id'");
    }

    public function updateActivate($user_id)
    {
        $this->query("UPDATE `users` SET `activate` = '1' WHERE `user_id` = '$user_id'");
    }

    public function checkActivate($user_id)
    {
        $activate = $this->findBySql("SELECT `activate` FROM `users` WHERE `user_id` = '$user_id'");
        if ($activate[0]['activate'] == 1) return true;
        return false;
    }

    public function getToken($user_id)
    {
        $token = $this->findBySql("SELECT `token` FROM `users` WHERE `user_id` = '$user_id' LIMIT 1");
        return $token[0]['token'];
    }
}