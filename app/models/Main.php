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

    public function getModal($img)
    {
        $login = $this->getLogin($img);
        $comments = $this->getComments($img);
        $res = '';
        foreach ($comments as $comment) {
            $res .= '<div class="comment">';
            $res .=  $this->markUp($comment['login'], $comment['text'], $comment['date']);
            $res .= '</div>';
        }
        $content = '<div class="modal-container">';
        $content .= '<header><h2>User picture '. $login. '</h2></header>';
        $content .= '<section><img src="'. $img .'"></section>';
        $content .= '<div>' . $res . '</div>';
        $content .= $this->addCommentBlock();
        $content .= '<footer class="footer"><a href="#" class="btn"><input type="button" value="Close"></a></footer>';
        $content .= '</div>';
        return $content;
    }

    protected function getLogin($img)
    {
        $login = $this->findBySql("SELECT `login` FROM `images` WHERE `image` = '" . str_replace('/public/images/', '', $img) . "' LIMIT 1");
        return $login[0]['login'];
    }

    protected function getComments($img)
    {
        $comments = $this->FindBySql("SELECT * FROM `comments` WHERE `image` = '$img'");
        return $comments;
    }

    protected function addCommentBlock()
    {
        $result = '<div id="addCommentContainer">';
        $result .= '<p>Add comment</p>';
        $result .= '<div id="addCommentForm">';
        $result .= '<div>';
        $result .= '<textarea name="body" id="body" cols="20" rows="4"></textarea>';
        $result .= '<input type="submit" id="btnSubmit" value="Send" />';
        $result .= '</div>';
        $result .= '</div>';
        $result .= '</div>';
        return $result;
    }

    public function markUp($login, $text, $date)
    {
        return '<div class="name">' . $login . '</div>
                <div class="date" title="Added at ' . $date . '">' . $date . '</div>
                <p>' . $text . '</p>';
    }
}

//<div class="comment">
//</div>';


//<div class="date" title="Added at ' . date('H:i \o\n d M Y', $date) . '">' . date('d M Y', $date) . '</div>


//5c73d832ef895.png