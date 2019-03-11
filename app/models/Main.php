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
        $count_likes = $this->getCountLikes($img);
        foreach ($comments as $comment) {
            $res .= '<div class="comment">';
            $res .=  $this->markUp($comment['login'], $comment['text'], $comment['date'], $login);
            $res .= '</div>';
        }
        $content = '<div class="modal-container">';
        $content .= '<header><h2>User picture <b>'. $login. '</b></h2></header>';
        $content .= '<section><img src="'. $img .'"></section>';
        if (isset($_SESSION['user']) && $_SESSION['user']['activate'] === '1') {
            $content .= $like;
            $content .= '<div class="post-comment" id="comment"></div>';
        }
        if (isset($_SESSION['user']) && $_SESSION['user']['login'] === $login) {
            $content .= '<input class="btn" type="submit" id="delete-image" value="Delete Image">';
        }
        $content .= '<div id="count-likes">Likes: ' . $count_likes . '</div>';
        $content .= '<div id="container-comment">' . $res . '</div>';
        $content .= $this->addCommentBlock();
        $content .= '<footer class="footer"><a href="#" class="btn"><input id="close-modal" type="button" value="Close"></a></footer>';
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

    protected function getCountLikes($img)
    {
        $count_likes = $this->FindBySql("SELECT COUNT(*) FROM `likes` WHERE `image` = '$img'");
        return $count_likes[0]['COUNT(*)'];
    }

    protected function addCommentBlock()
    {
        $result = '<div class="invisible" id="addCommentContainer">';
        $result .= '<p>Add comment</p>';
        $result .= '<div id="addCommentForm">';
        $result .= '<div>';
        $result .= '<textarea name="body" id="body" rows="4"></textarea>';
        $result .= '<div class="send-button" id="btnSubmit"> </div>';
        $result .= '</div>';
        $result .= '</div>';
        $result .= '</div>';
        return $result;
    }

    public function markUp($login, $text, $date, $user_photo = '')
    {
        $delete_comment = '';
        if (isset($_SESSION['user']) && ($_SESSION['user']['login'] === $login || $_SESSION['user']['login'] === $user_photo)) {
            $delete_comment = '<input class="btn delete-comment" type="submit" value="Delete Comment">';
        }
        $return = '<div class="name">' . $login . '</div>';
        $return .= '<div class="date">' . $date . '</div>';
        $return .= '<p>' . $text . '</p>' . '<div>' . $delete_comment . '</div>';
        return $return;
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

    public function deleteComment($date)
    {
        $this->query("DELETE FROM `comments` WHERE `date` = '$date'");
    }

    public function checkNotifications($img)
    {
        $login = $this->getLogin($img);
        $notifications = $this->FindBySql("SELECT `notifications` FROM `users` WHERE `login` = '$login'");
        $notifications = $notifications[0]['notifications'];
        if ($notifications === 'yes') {
            $this->sendCommentNotification($login);
        }
    }

    protected function sendCommentNotification($login)
    {
        $to = $this->findBySql("SELECT `email` FROM `users` WHERE `login` = '$login'");
        $to = $to[0]['email'];
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
        $resetPassLink = 'http://127.0.0.1:8100/';
        $subject = 'Your post was commented';
        $mailContent = 'Dear ' . $login . ',';
        if ($login !== $_SESSION['user']['login']) {
            $mailContent .= '<br/>User ' . $_SESSION['user']['login'] . ' commented on your image';
        } else {
            $mailContent .= '<br/>You commented on your own photo';
        }
        $mailContent .= '<br/>To show this comment, visit the following link: <a href="' . $resetPassLink . '">' . $resetPassLink . '</a>';
        $mailContent .= '<br/><br/>Regards,';
        $mailContent .= '<br/>Camagru';
        mail($to, $subject, $mailContent, $header);
    }
}
