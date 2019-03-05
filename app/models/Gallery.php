<?php

namespace app\models;

use vendor\core\base\Model;

class Gallery extends Model
{

    public function saveImageToDb($user, $image) {
        $this->query("INSERT INTO `images` (`user_id`, `login`, `date`, `image`) VALUES ('{$user['user_id']}', '{$user['login']}', NOW(), '$image')");
    }

    public function getExtension($img) {

        $extension = str_replace( 'data:', '', stristr($_POST["image"], ';base64,', true));
        switch ($extension) {
            case 'image/jpeg':
                $extension = 'jpg';
                break;
            case 'image/png':
                $extension = 'png';
                break;
            case 'image/gif':
                $extension = 'gif';
                break;
            default :
                $_SESSION['error'] = 'Wrong format of file. Only JPG, JPEG, PNG & GIF files are allowed';
                die;
        }
        return $extension;
    }

    public function imageDecode($image, $extension) {
        $mime = '';
        switch ($extension) {
            case 'jpg':
                $mime = 'image/jpeg';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;

        }
        $result = str_replace('data:'. $mime .';base64,', '', $image);
        $result = str_replace(' ', '+', $result);
        $result = base64_decode($result);
        return $result;
    }

    public function deleteImage($img)
    {
        $this->query("DELETE FROM `images` WHERE `image` = '" . str_replace('/public/images/', '', $img) . "'");
        $this->query("DELETE FROM `comments` WHERE `image` = '$img'");
        $this->query("DELETE FROM `likes` WHERE `image` = '$img'");
    }
}