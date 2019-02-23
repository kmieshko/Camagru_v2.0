<?php

namespace app\controllers;

use app\models\User;
use vendor\core\base\View;

class UserController extends AppController
{

    public function signupAction()
    {
        if (!empty($_POST)) {
            $user = new User();
            $data = $_POST;
            $user->load($data);
            if (!$user->validate($data) || !$user->checkUnique()) {
                $user->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
            $user->save('users');
            $_SESSION['success'] = 'You have successfully registered';
            redirect('/user/login');
        }

    }

    public function loginAction()
    {
        if (!empty($_POST)) {
            $user = new User();
            $user->login();
            if ($user->login()) {
                $_SESSION['success'] = 'You have successfully logged in';
            } else {
                $_SESSION['error'] = 'Wrong login or password';
            }
            redirect('/');
        }
    }

    public function logoutAction()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        redirect('/user/login');
    }
}