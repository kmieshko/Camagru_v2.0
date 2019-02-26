<?php

namespace app\controllers;

use app\models\Main;
use vendor\core\base\Pagination;

class MainController extends AppController
{
    public function indexAction()
    {
        $model = new Main;
        $model->query("CREATE TABLE IF NOT EXISTS users (`user_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, `login` VARCHAR(" . MAX_LENGTH . ") NOT NULL, `password` VARCHAR(255) NOT NULL, `email` VARCHAR(100) NOT NULL, `notifications` ENUM('yes', 'no'))");
        $model->query("CREATE TABLE IF NOT EXISTS images (`user_id` INT(11) NOT NULL, `login` VARCHAR(" . MAX_LENGTH . ") NOT NULL, `date` TIMESTAMP PRIMARY KEY,  `image` VARCHAR(255) NOT NULL)");
        $model->query("CREATE TABLE IF NOT EXISTS comments (`user_id` INT(11) NOT NULL, `login` VARCHAR(" . MAX_LENGTH . ") NOT NULL, `date` TIMESTAMP,  `image` VARCHAR(255) NOT NULL PRIMARY KEY, `text` VARCHAR(1000) NOT NULL)");

        $total = $model->FindBySql("SELECT COUNT(*) FROM images");
        $total = $total[0]['COUNT(*)'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = 2;
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();
        $images = $model->FindBySql("SELECT * FROM `images` ORDER BY `date` DESC LIMIT $start, $perpage");
        $title = 'PAGE_TITLE';
        $this->set(compact('title', 'images', 'total', 'perpage', 'pagination'));
    }

    public function commentImageAction()
    {
        if (isset($_POST['body'])) {
            $text = $_POST['body'];
            $comment = new Main();
            $comment->saveCommentToDb($text, $_SESSION['user'], $_POST['img']);
            $date = date('r', time());
            $content = $comment->markUp($_SESSION['user']['login'], $text, strtotime($date));
            echo json_encode(array('html' => $content));
        }
        $this->view = 'index';
    }
}







