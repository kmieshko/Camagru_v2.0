<?php

namespace app\controllers;

use app\models\Gallery;

class GalleryController extends \vendor\core\base\Controller
{
    public function indexAction()
    {
        $title = 'Add Image';
        $this->set(compact('title'));
        $this->view = 'add_image';
    }

    public function saveImageAction()
    {
        if (isset($_POST["image"])) {
            $gObj = new Gallery();
            $extension = $gObj->getExtension($_POST["image"]);
            $img = $gObj->imageDecode($_POST["image"], $extension);
            $name = uniqid() . '.' . $extension;
            $path = ROOT . '/public/images/' . $name;
            if ($img === false) {
                $_SESSION['error'] = 'base64_decode failed';
            } else {
                file_put_contents($path, $img);
                $gObj->saveImageToDb($_SESSION['user'], $name);
                $_SESSION['success'] = 'Your image successfully added to the gallery';
            }
        }
        $this->view = 'add_image';
    }

    public function deleteImageAction()
    {
        if (isset($_POST["img"])) {
            $gObj = new Gallery();
            $gObj->deleteImage($_POST["img"]);
        } else {
            redirect('main/index');
        }
    }
}
