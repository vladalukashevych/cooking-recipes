<?php

namespace controllers;

use core\Controller;
use core\Core;
use core\Utils;
use models\Favourite;
use models\Recipe;
use models\User;

class UserController extends Controller
{

    public function signupAction()
    {
        if (User::isUserAuthenticated())
            $this->redirect('/');
        if (Core::getInstance()->requestMethod == 'POST') {
            $errors = [];
            if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL))
                $errors['login'] = 'Email is not valid.';
            if (User::isLoginExists($_POST['login']))
                $errors['login'] = 'User with this email already exists.';
            if ($_POST['password'] != $_POST['password2'])
                $errors['password2'] = 'Passwords don\'t match.';
            if (empty($_POST['firstname']))
                $errors['firstname'] = 'Field can\'t be empty.';
            if (empty($_POST['lastname']))
                $errors['lastname'] = 'Field can\'t be empty.';

            if (!empty($errors)) {
                $model = $_POST;
                return $this->render(null, [
                    'errors' => $errors,
                    'model' => $model
                ]);
            } else {
                User::addUser($_POST['login'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], date('Y-m-d'));
                $user = User::getUserByLoginAndPassword($_POST['login'], $_POST['password']);
                User::authenticateUser($user);
                $this->redirect("/");
            }
        } else
            return $this->render();
    }

    public function loginAction()
    {
        if (User::isUserAuthenticated())
            $this->redirect('/');
        if (Core::getInstance()->requestMethod === 'POST') {
            $user = User::getUserByLoginAndPassword($_POST['login'], $_POST['password']);
            $error = null;
            if (empty($user)) {
                $error = 'Wrong login or password.';
            } else {
                User::authenticateUser($user);
                $this->redirect('/');
            }
        }
        return $this->render(null, [
            'error' => $error
        ]);
    }

    public function logoutAction()
    {
        User::logoutUser();
        $this->redirect('/user/login');
    }

    public function favouriteAction()
    {
        if (!User::isUserAuthenticated())
            return $this->error(401);
        $user = User::getCurrentAuthenticatedUser();

        $pages['current'] = 1;
        if (!empty($_GET['page']))
            $pages['current'] = intval($_GET['page']);

        $favourites = Favourite::getFavouritesByUserId($user['id']);
        foreach ($favourites as $favourite)
            $recipes[] = Recipe::getRecipeById($favourite['recipe_id']);

        if (!is_null($recipes))
        $pages['count'] = ceil(count($recipes)/RECIPES_LIMIT);
        if ($pages['count'] > 1)
            $recipes = Utils::getOnePage($recipes, $pages['current'], RECIPES_LIMIT);

        return $this->render(null, [
            'recipes' => $recipes,
            'pages' => $pages
        ]);
    }

    public function deleteAction($params)
    {
        if (!User::isUserAuthenticated())
            return $this->error(401);

        $id = intval($params[0]);
        if (User::getUserById($id) == null)
            return $this->error(404);

        $user = User::getCurrentAuthenticatedUser();
        if (!User::isAdmin() && $user['id'] != $id)
            return $this->error(403);

        $yes = boolval($params[1] === 'yes');
        if ($id > 0) {
            if ($yes) {
                User::deleteUser($id);
                User::logoutUser();
                return $this->redirect('/home');
            }
            $category = User::getCurrentAuthenticatedUser();
            return $this->render(null, [
                'user' => $user
            ]);
        } else {
            return $this->error(403);
        }
    }

    public function editAction($params)
    {
        if (!User::isUserAuthenticated())
            return $this->error(401);

        $id = intval($params[0]);
        if (User::getUserById($id) == null)
            return $this->error(404);

        $user = User::getCurrentAuthenticatedUser();
        if (!User::isAdmin() && $user['id'] != $id)
            return $this->error(403);

        $user = User::getUserById($id);

        if (Core::getInstance()->requestMethod == 'POST') {
            if (array_key_exists('profile-edit', $_POST)) {
                $errors = [];
                if (empty($_POST['firstname']))
                    $errors['firstname'] = 'Field can\'t be empty.';
                if (empty($_POST['lastname']))
                    $errors['lastname'] = 'Field can\'t be empty.';

                $model = $_POST;

                $updates = [
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname']
                ];
                if (!empty($_POST['access_level']))
                    $updates['access_level'] = $_POST['access_level'];
            } elseif (array_key_exists('password-change', $_POST)) {
                $errors = [];
                if ($_POST['password'] != $_POST['password2'])
                    $errors['password2'] = 'Passwords don\'t match.';
                if (empty($_POST['password']))
                    $errors['password'] = 'Field can\'t be empty.';
                if (empty($_POST['password2']))
                    $errors['password2'] = 'Field can\'t be empty.';

                $updates = [
                    'password' => $_POST['password']
                ];
            }
            if (empty($errors)) {
                User::updateUser($id, $updates);
                $this->redirect("/");
            }
        }
        return $this->render(null, [
            'user' => $user,
            'model' => $model,
            'errors' => $errors
        ]);
    }

}