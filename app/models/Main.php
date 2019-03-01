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
        $like = $this->getLike($img, $_SESSION['user']['login']);
        foreach ($comments as $comment) {
            $res .= '<div class="comment">';
            $res .=  $this->markUp($comment['login'], $comment['text'], $comment['date']);
            $res .= '</div>';
        }
        $content = '<div class="modal-container">';
        $content .= '<header><h2>User picture '. $login. '</h2></header>';
        $content .= '<section><img src="'. $img .'"></section>';
        $content .= $like;
        $content .= '<div class="post-comment" id="comment"></div>';
        $content .= '<div id="container-comment">' . $res . '</div>';
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
        $result = '<div class="invisible" id="addCommentContainer">';
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
                <div class="date">' . $date . '</div>
                <p>' . $text . '</p>';
    }

    protected function getLike($img, $login)
    {
        $res = $this->FindBySql("SELECT * FROM `likes` WHERE `image` = '$img' AND `login` = '$login' LIMIT 1");
        if ($res == TRUE) {
            $like = '<div class="post-like liked" id="like"></div>';
        } else {
            $like = '<div class="post-like unliked" id="like"></div>';
        }
        return $like;
    }

    public function likeImage($img, $login)
    {
        $this->query("INSERT INTO `likes` (`login`, `image`) VALUES ('$login', '$img')");
    }

    public function unlikeImage($img, $login)
    {
        $this->FindBySql("DELETE FROM likes WHERE `image` = '$img' AND `login` = '$login'");
    }
}