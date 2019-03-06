<?php

namespace app\controllers;

use app\models\User;

class UserController extends \vendor\core\base\Controller
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
            $user->attributes['activate'] = 0;
            $user->save('users');
            $user->activateAccount($data['login'], $data['email'], $user->attributes['token']);
            $_SESSION['success'] = 'Check your inbox for an account activate email';
        }
        $title = 'Signup';
        $this->set(compact('title'));
    }

    public function activateAccountAction()
    {
        if (isset($_GET['token'])) {
            $model = new User();
            $user = $model->findBySql("SELECT * FROM `users` WHERE `token` = ? LIMIT 1", [$_GET['token']]);
            if (!empty($user)) {
                $model->updateActivate($user[0]['user_id']);
                $model->updateToken($user[0]['user_id']);
                $token = $model->getToken($user[0]['user_id']);
                $_SESSION['user']['token'] = $token[0]['token'];
            } else {
                $_SESSION['error'] = 'Some problem occurred, please try again';
                redirect('/user/signup');
            }
        }
        $title = 'Activate Account';
        $this->set(compact('title'));
    }

    public function resendActivationLinkAction()
    {
        if (isset($_SESSION['user'])) {
            $model = new User();
            $token = $model->getToken($_SESSION['user']['user_id']);
            $_SESSION['user']['token'] = $token[0]['token'];
            $model->activateAccount($_SESSION['user']['login'], $_SESSION['user']['email'], $_SESSION['user']['token']);
            $_SESSION['success'] = 'Check your inbox for an account activate email';
            redirect('/user/activate-account');
        } else {
            redirect('/user/signup');
        }
        $title = 'Activate Account';
        $this->set(compact('title'));
    }

    public function loginAction()
    {
        if (!empty($_POST)) {
            $user = new User();
            if (isset($_POST['g-recaptcha-response'])) {
                $url_to_google_api = "https://www.google.com/recaptcha/api/siteverify";
                $secret_key = '6LfK5pUUAAAAAJPp9wxs4e6WsBV00eEyNGoP_TAY';
                $query = $url_to_google_api . '?secret=' . $secret_key . '&response='
                    . $_POST['g-recaptcha-response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
                $data = json_decode(file_get_contents($query));
                if ($data->success) {
                    if ($user->login()) {
                        $_SESSION['success'] = 'You have successfully logged in';
                        redirect('/');
                    } else {
                        $_SESSION['error'] = 'Wrong login or password';
                        redirect('/user/login');
                    }
                } else {
                    $_SESSION['error'] = 'Valid Captcha, please';
                }
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
                $_SESSION['success'] = 'Check your inbox for reset password email';
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
        $title = 'New Password';
        $this->set(compact('title'));
    }

    public function updatePasswordAction()
    {
        if (!empty($_SESSION['reset_user'])) {
            $data = array_merge($_POST, $_SESSION['reset_user']);
            debug($data);
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