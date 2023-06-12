<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Recipe;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        $recipes = Recipe::getRandomRecipes(5);
        return $this->render(null, [
            'recipes' => $recipes
        ]);
    }

    public function errorAction($code)
    {
        switch ($code) {
            case 401:
                return $this->render('views/error/error-401.php');
            case 404:
                return $this->render('views/error/error-404.php');
            case 403:
                return $this->render('views/error/error-403.php');
        }
    }
}