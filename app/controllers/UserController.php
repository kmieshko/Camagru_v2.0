<?php

namespace app\controllers;

use app\models\User;

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
            $user->attributes['token'] = md5(rand(999, 99999));
            $user->save('users');
            $_SESSION['success'] = 'You have successfully registered';
            redirect('/user/login');
        }
        $title = 'Signup';
        $this->set(compact('title'));
    }

    public function loginAction()
    {
        if (!empty($_POST)) {
            $user = new User();
            $user->login();
            if ($user->login()) {
                $_SESSION['success'] = 'You have successfully logged in';
                redirect('/');
            } else {
                $_SESSION['error'] = 'Wrong login or password';
                redirect('/user/login');
            }
        }
        $title = 'Login';
        $this->set(compact('title'));
    }

    public function logoutAction()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        redirect('/user/login');
    }

    public function resetPasswordAction()
    {
        if (!empty($_POST)) {
            $email = !empty(trim($_POST['reset'])) ? trim($_POST['reset']) : NULL;
            $user = new User();
            $res = $user->findEmail($email);
            if ($res === true) {
                $user->resetPassword($email);
                $_SESSION['success'] = 'Check your inbox for a password reset email';
            } else {
                $_SESSION['error'] = 'Given email is not associated with any account';
                redirect();
            }
        }
        $title = 'Reset Password';
        $this->set(compact('title'));
        $this->view = 'reset';
    }

    public function newPasswordAction()
    {
        $model = new User();
        $user = $model->findBySql("SELECT * FROM `users` WHERE `token` = ? LIMIT 1", [$_GET['token']]);
        if (!empty($user)) {
            $_SESSION['reset_user'] = $user[0];
            $_SESSION['success'] = 'You can update your password';
        } else {
            $_SESSION['error'] = 'Some problem occurred, please try again';
            redirect('/user/reset-password');
        }
    }

    public function updatePasswordAction()
    {
        if (!empty($_SESSION['reset_user'])) {
            $data = array_merge($_POST, $_SESSION['reset_user']);
            unset($_SESSION['reset_user']);
            $user = new User();
            if (!$user->validate($_POST)) {
                $user->getErrors();
                redirect();
            }
            $user->updatePassword($data['new-password'], $data['user_id']);
            $user->updateToken($data['user_id']);
            $title = 'Update Password';
            $this->set(compact('title'));
        } else {
            redirect('/user/reset-password');
        }
    }

    public function profileAction()
    {
        if (!empty($_SESSION['user'])) {
            if (!empty($_POST)) {
                $user = new User();
                if (!$user->validate($_POST)) {
                    $user->getErrors();
                    redirect();
                }
                if ($_POST['notifications'] !== $_SESSION['user']['notifications']) {
                    $user->updateNotifications($_POST['notifications'], $_SESSION['user']['user_id']);
                    $_SESSION['user']['notifications'] = $_POST['notifications'];
                }
                if (isset($_POST['login']) && $_POST['login'] !== $_SESSION['user']['login']) {
                    $user->updateLogin($_POST['login'], $_SESSION['user']['user_id']);
                    $_SESSION['user']['login'] = $_POST['login'];
                }
                if (isset($_POST['email']) && $_POST['email'] !== $_SESSION['user']['email']) {
                    $user->updateEmail($_POST['email'], $_SESSION['user']['user_id']);
                    $_SESSION['user']['email'] = $_POST['email'];
                }
                if (isset($_POST['new-password'])) {
                    $user->updatePassword($_POST['new-password'], $_SESSION['user']['user_id']);
                }
            }
            $login = $_SESSION['user']['login'];
            $email = $_SESSION['user']['email'];
            $notifications = $_SESSION['user']['notifications'];
            $title = 'Profile';
            $this->set(compact('title', 'login', 'email', 'notifications'));
        } else {
            redirect('/user/login');
        }
    }
}