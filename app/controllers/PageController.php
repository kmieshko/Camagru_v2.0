<?php

namespace app\controllers;

class PageController extends AppController
{

    public function viewAction()
    {
        debug($this->route);
        echo 'Page::view';
    }
}