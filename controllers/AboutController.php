<?php

namespace controllers;

use core\Controller;
use models\Category;

class AboutController extends Controller
{
    public function indexAction()
    {
        return $this->render();
    }
}