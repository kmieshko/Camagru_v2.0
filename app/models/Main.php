<?php

namespace app\models;

use vendor\core\base\Model;

class Main extends Model
{

    public $table = 'images';

    public function saveCommentToDb($text, $user, $img)
    {
        $this->query("INSERT INTO `comments` (`user_id`, `login`, `date`, `image`, `text`) VALUES ('{$user['user_id']}', '{$user['login']}', NOW(), '$img', '$text')");
    }

    public function markUp($login, $text, $date)
    {
        return '
                <div class="avatar">
                </div>
                <div class="name">' . $login . '</div>
                <div class="date" title="Added at ' . $date . '">' . $date . '</div>
                <p>' . $text . '</p>';
    }

    public function openModal($img)
    {
        $login = $this->getLogin($img);
        return '<div class="modal-container">
                <header><h2>User picture '. $login. '</h2><header>
                <section><img src="'. $img .'"></section>
                <footer class="footer">
                <a href="#" class="btn"><input type="button" value="Close"></a>
                </footer>
                </div>';
    }

    protected function getLogin($img)
    {
        $login = $this->findBySql("SELECT `login` FROM `images` WHERE `image` = '" . str_replace('/public/images/', '', $img) . "' LIMIT 1");
        return $login[0]['login'];
    }

    protected function getComments($img)
    {
        $comments = $this->FindBySql("SELECT * FROM `comments` WHERE `image` = $img");
        return $comments;
    }
}

//<div class="comment">
//</div>';


//<div class="date" title="Added at ' . date('H:i \o\n d M Y', $date) . '">' . date('d M Y', $date) . '</div>


//5c73d832ef895.png