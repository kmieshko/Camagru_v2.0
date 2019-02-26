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
        return '<div class="comment">
                <div class="avatar">
                </div>
                <div class="name">' . $login . '</div>
                <div class="date" title="Added at ' . date('H:i \o\n d M Y', $date) . '">' . date('d M Y', $date) . '</div>
                <p>' . $text . '</p>
                </div>';
    }
}