<?php

namespace controllers;

use \core\Controller;
use core\Core;
use core\Utils;
use models\Category;
use models\Favourite;
use models\Recipe;
use models\User;

class CategoryController extends Controller
{
    public function indexAction()
    {
        $categories = Category::getCategories();
        return $this->render(null, [
            'categories' => $categories
        ]);
    }

    public function addAction()
    {
        if (!User::isAdmin())
            return $this->error(403);
        if (Core::getInstance()->requestMethod === 'POST') {
            $_POST['name'] = trim($_POST['name']);
            $errors = [];
            if (empty($_POST['name']))
                $errors['name'] = 'The name field is empty.';
            else if (Category::isCategoryExistsByName($_POST['name']))
                $errors['name'] = 'Category with this name already exists.';
            else {
                Category::addCategory($_POST['name'], $_FILES['file']['tmp_name']);
                return $this->redirect('/category/index');
            }
        }
        return $this->render(null, [
            'errors' => $errors
        ]);
    }

    public function updateAction()
    {
        if (!User::isAdmin())
            return $this->error(403);
        if (Core::getInstance()->requestMethod === 'POST') {
            $_POST['name'] = trim($_POST['name']);
            $error[] = null;
            if ($_POST['name'] == Category::getCategoryById($_POST['id'])['name'])
                Category::changePhoto($_POST['id'], $_FILES['file']['tmp_name']);
            elseif (empty($_POST['name']))
                $error = [
                    'id' => $_POST['id'],
                    'name' => 'The name field is empty.'
                ];
            elseif (Category::isCategoryExistsByName($_POST['name']))
                $error = [
                    'id' => $_POST['id'],
                    'name' => 'Category with this name already exists.'
                ];
            else {
                Category::updateCategory($_POST['id'], $_POST['name']);
                Category::changePhoto($_POST['id'], $_FILES['file']['tmp_name']);
            }
        }
        $categories = Category::getCategories();
        return $this->render(null, [
            'categories' => $categories,
            'error' => $error
        ]);
    }

    public function deleteAction($params)
    {
        if (!User::isAdmin())
            return $this->error(403);
        $id = intval($params[0]);
        $yes = boolval($params[1] === 'yes');
        if ($id > 0) {
            if ($yes) {
                Category::deleteCategory($id);
                return $this->redirect('/category/update');
            }
            $category = Category::getCategoryById($id);
            return $this->render(null, [
                'category' => $category
            ]);
        } else {
            return $this->error(403);
        }
    }

    public function viewAction($params)
    {
        $pages['current'] = 1;
        if (!empty($_GET['page']))
            $pages['current'] = intval($_GET['page']);

        $id = intval($params[0]);
        $category = Category::getCategoryById($id);
        $recipes = Recipe::getRecipesInCategory($id);

        $pages['count'] = ceil(count($recipes)/RECIPES_LIMIT);
        if ($pages['count'] > 1)
            $recipes = Utils::getOnePage($recipes, $pages['current'], RECIPES_LIMIT);

        if (User::isUserAuthenticated()) {
            $user = User::getCurrentAuthenticatedUser();
            $favourites = Favourite::getFavouritesByUserId($user['id']);
        }
        return $this->render(null, [
            'category' => $category,
            'favourites' => $favourites,
            'recipes' => $recipes,
            'pages' => $pages
        ]);
    }
}
