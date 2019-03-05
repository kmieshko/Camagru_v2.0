<?php

namespace app\controllers;

use app\models\Main;
use vendor\core\base\Pagination;

class MainController extends AppController
{
    public function indexAction()
    {
        $model = new Main;
        $model->query("CREATE TABLE IF NOT EXISTS users (`user_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `login` VARCHAR(" . MAX_LENGTH . ") NOT NULL, `password` VARCHAR(255) NOT NULL, `email` VARCHAR(100) NOT NULL, `notifications` ENUM('yes', 'no'), `token` VARCHAR(255) NOT NULL)");
        $model->query("CREATE TABLE IF NOT EXISTS images (`user_id` INT(11) NOT NULL, `login` VARCHAR(" . MAX_LENGTH . ") NOT NULL, `date` TIMESTAMP PRIMARY KEY,  `image` VARCHAR(255) NOT NULL)");
        $model->query("CREATE TABLE IF NOT EXISTS comments (`comment_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `user_id` INT(11) NOT NULL, `login` VARCHAR(" . MAX_LENGTH . ") NOT NULL, `date` TIMESTAMP,  `image` VARCHAR(255) NOT NULL, `text` VARCHAR(1000) NOT NULL)");
        $model->query("CREATE TABLE IF NOT EXISTS likes (`like_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `login` VARCHAR(" . MAX_LENGTH . ") NOT NULL, `image` VARCHAR(255) NOT NULL)");

        $total = $model->FindBySql("SELECT COUNT(*) FROM images");
        $total = $total[0]['COUNT(*)'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 9;
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();
        $images = $model->FindBySql("SELECT * FROM `images` ORDER BY `date` DESC LIMIT $start, $perpage");
        $title = 'Home';
        $this->set(compact('title', 'images', 'total', 'perpage', 'pagination'));
    }

    public function modalAction()
    {
        if (isset($_POST['img'])) {
            $mObj = new Main();
            $content = $mObj->getModal($_POST['img']);
            echo json_encode(array('html' => $content));
        }
        $this->view = 'index';
    }

    public function commentImageAction()
    {
        if (isset($_POST['body'])) {
            $text = $_POST['body'];
            $mObj = new Main();
            $mObj->saveCommentToDb($text, $_SESSION['user'], $_POST['img']);
            $mObj->checkNotifications($_POST['img']);
            date_default_timezone_set('Europe/Kiev');
            $date = date('Y-m-d H:i:s', time());
            $content = $mObj->markUp($_SESSION['user']['login'], $text, $date, $_SESSION['user']['login']);
            echo json_encode(array('html' => $content));
        }
        $this->view = 'index';
    }

    public function likeImageAction()
    {
        if (isset($_POST['img']) && isset($_POST['like'])) {
            $mObj = new Main();
            if ($_POST['like'] == -1) {
                $mObj->unlikeImage($_POST['img'], $_SESSION['user']['login']);
            } elseif ($_POST['like'] == 1) {
                $mObj->likeImage($_POST['img'], $_SESSION['user']['login']);
            }
        }
    }


    public function deleteCommentAction()
    {
        if (isset($_POST['date'])) {
            $mObj = new Main();
            $mObj->deleteComment($_POST['date']);
        } else {
            redirect('main/index');
        }
    }
}







