<?php

namespace controllers;

use core\Controller;
use core\Core;
use core\Utils;
use models\Category;
use models\Favourite;
use models\Ingredient;
use models\Recipe;
use models\User;

class RecipeController extends Controller
{

    public function indexAction()
    {
        $pages['current'] = 1;
        if (!empty($_GET['page']))
            $pages['current'] = intval($_GET['page']);

        $recipes = Recipe::getRecipes();
        $categories = Category::getCategories();
        $_GET['search'] = trim($_GET['search']);

        if (!empty($_GET['search']))
            $recipes = Recipe::getRecipesNameMatch($_GET['search']);

        if (!is_null($_GET['category_id']))
            $recipes = Recipe::getRecipesByFilter($_GET);

        if (User::isUserAuthenticated()) {
            $user = User::getCurrentAuthenticatedUser();
            $favourites = Favourite::getFavouritesByUserId($user['id']);
        }

        $pages['count'] = ceil(count($recipes)/RECIPES_LIMIT);
        if ($pages['count'] > 1)
            $recipes = Utils::getOnePage($recipes, $pages['current'], RECIPES_LIMIT);

        return $this->render(null, [
            'favourites' => $favourites,
            'recipes' => $recipes,
            'categories' => $categories,
            'model' => $_GET,
            'search' => $_GET['search'],
            'pages' => $pages
        ]);
    }

    public function addAction($params)
    {
        if (!User::isAdmin())
            return $this->error(403);

        $category_id = intval($params[0]);
        if (empty($category_id))
            $category_id = null;

        $categories = Category::getCategories();
        if (Core::getInstance()->requestMethod === 'POST') {
            $_POST['name'] = trim($_POST['name']);
            $_POST['description'] = trim($_POST['description']);
            $_POST['instructions'] = trim($_POST['instructions']);
            foreach ($_POST['ingredient-name'] as $key => $n)
                $_POST['ingredient-name'][$key] = trim($_POST['ingredient-name'][$key]);
            foreach ($_POST['ingredient-amount'] as $key => $am)
                $_POST['ingredient-amount'][$key] = trim($_POST['ingredient-amount'][$key]);


            $errors = [];

            if (empty($_POST['name']))
                $errors['name'] = 'The name field can\'t be empty.';
            if (empty($_POST['description']))
                $errors['description'] = 'The description field can\'t be empty.';
            $time = filter_input(INPUT_POST, 'time', FILTER_VALIDATE_INT);
            if ($time == false || $time <= 0)
                $errors['time'] = 'Enter a valid number.';
            $servings = filter_input(INPUT_POST, 'servings', FILTER_VALIDATE_INT);
            if ($servings == false || $servings <= 0)
                $errors['servings'] = 'Enter a valid servings number.';
            $calories = filter_input(INPUT_POST, 'calories', FILTER_VALIDATE_INT);
            if ($calories == false || $calories < 0)
                $errors['calories'] = 'Enter a valid calories number.';
            if (empty($_POST['instructions']))
                $errors['instructions'] = 'The instructions field can\'t be empty.';
            if ($_POST['category_id'] != 'NULL' && !Category::isCategoryExists($_POST['category_id']))
                $errors['category'] = 'This category doesn\'t exist.';
            if (empty($_POST['ingredient-name']))
                $errors['ingredients'] = 'Recipe has to contain at least 1 ingredient.';
            else
                foreach ($_POST['ingredient-name'] as $ingredientName) {
                    if (empty($ingredientName)) {
                        $errors['ingredients'] = 'The name fields can\'t be empty.';
                    }
                }
            if (empty($_FILES['file']['tmp_name']))
                $errors['photo'] = 'Adding a photo is obligatory.';

            if (empty($errors)) {
                $recipe = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'time' => $time,
                    'servings' => $servings,
                    'calories' => $calories,
                    'instructions' => $_POST['instructions'],
                ];
                if ($_POST['category_id'] != 'NULL')
                    $recipe['category_id'] = $_POST['category_id'];
                if (Recipe::isRecipeExists($recipe))
                    $errors['exists'] = 'This recipe already exists.';
            }
            if (empty($errors)) {
                $recipe['create_date'] = date('Y-m-d');
                Recipe::addRecipe($recipe);
                $recipeId = Recipe::getRecipeId($recipe);
                Recipe::changePhoto($recipeId, $_FILES['file']['tmp_name']);
                foreach ($_POST['ingredient-name'] as $key => $n) {
                    $row = [
                        'name' => $n,
                        'amount' => $_POST['ingredient-amount'][$key],
                        'recipe_id' => $recipeId
                    ];
                    Ingredient::addIngredient($row);
                }
                return $this->redirect('/recipe/view/' . $recipeId);
            } else {
                $model = $_POST;
                return $this->render(null, [
                    'errors' => $errors,
                    'categories' => $categories,
                    'model' => $model,
                    'category_id' => $category_id
                ]);
            }
        }
        return $this->render(null, [
            'categories' => $categories,
            'category_id' => $category_id
        ]);
    }

    public function editAction($params)
    {
        if (!User::isAdmin())
            return $this->error(403);

        $id = intval($params[0]);
        if (Recipe::getRecipeById($id) == null)
            return $this->error(404);

        $categories = Category::getCategories();
        if (Core::getInstance()->requestMethod === 'POST') {
            $_POST['name'] = trim($_POST['name']);
            $_POST['description'] = trim($_POST['description']);
            $_POST['instructions'] = trim($_POST['instructions']);

            $errors = [];

            if (empty($_POST['name']))
                $errors['name'] = 'The name field can\'t be empty.';
            else
                foreach ($_POST['ingredient-name'] as $key => $n)
                    $_POST['ingredient-name'][$key] = trim($_POST['ingredient-name'][$key]);
            if (empty($_POST['description']))
                $errors['description'] = 'The description field can\'t be empty.';
            else
                foreach ($_POST['ingredient-amount'] as $key => $am)
                    $_POST['ingredient-amount'][$key] = trim($_POST['ingredient-amount'][$key]);
            $time = filter_input(INPUT_POST, 'time', FILTER_VALIDATE_INT);
            if ($time == false || $time <= 0)
                $errors['time'] = 'Enter a valid number.';
            $servings = filter_input(INPUT_POST, 'servings', FILTER_VALIDATE_INT);
            if ($servings == false || $servings <= 0)
                $errors['servings'] = 'Enter a valid servings number.';
            $calories = filter_input(INPUT_POST, 'calories', FILTER_VALIDATE_INT);
            if ($calories == false || $calories < 0)
                $errors['calories'] = 'Enter a valid calories number.';
            if (empty($_POST['instructions']))
                $errors['instructions'] = 'The instructions field can\'t be empty.';
            if ($_POST['category_id'] != 'NULL' && !Category::isCategoryExists($_POST['category_id']))
                $errors['category'] = 'This category doesn\'t exist.';
            if (empty($_POST['ingredient-name']))
                $errors['ingredients'] = 'Recipe has to contain at least 1 ingredient.';
            else
                foreach ($_POST['ingredient-name'] as $ingredientName) {
                    if (empty($ingredientName)) {
                        $errors['ingredients'] = 'The name fields can\'t be empty.';
                    }
                }
            if (empty($errors)) {
                $recipe = [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'time' => $time,
                    'servings' => $servings,
                    'calories' => $calories,
                    'instructions' => $_POST['instructions'],
                ];
                if ($_POST['category_id'] != 'NULL')
                    $recipe['category_id'] = $_POST['category_id'];
                else
                    $recipe['category_id'] = null;

                Recipe::updateRecipe($id, $recipe);
                Recipe::changePhoto($id, $_FILES['file']['tmp_name']);
                foreach ($_POST['ingredient-name'] as $key => $n)
                    $ingredientsNew[] = [
                        'id' => $_POST['ingredient-id'][$key],
                        'name' => $_POST['ingredient-name'][$key],
                        'amount' => $_POST['ingredient-amount'][$key]
                    ];

                $ingredients = Ingredient::getIngredientsByRecipeId($id);
                foreach ($ingredients as $ingredient)
                    $ingredientsId[] = $ingredient['id'];
                foreach ($ingredientsNew as $ingredient)
                    $ingredientsNewId[] = $ingredient['id'];
                $idDeletes = array_diff($ingredientsId, $ingredientsNewId);

                foreach ($ingredientsNew as $ingredient) {
                    $row = [
                        'name' => $ingredient['name'],
                        'amount' => $ingredient['amount'],
                        'recipe_id' => $id
                    ];
                    if (empty($ingredient['id']))
                        Ingredient::addIngredient($row);
                    else
                        Ingredient::updateIngredient($ingredient['id'], $row);
                }
                if (!empty($idDeletes))
                    foreach ($idDeletes as $idDelete)
                        Ingredient::deleteIngredient($idDelete);

                return $this->redirect('/recipe/view/' . $id);
            } else {
                $model = $_POST;
                return $this->render(null, [
                    'errors' => $errors,
                    'categories' => $categories,
                    'model' => $model
                ]);
            }
        }

        $recipe = Recipe::getRecipeById($id);
        $ingredients = Ingredient::getIngredientsByRecipeId($id);
        return $this->render(null, [
            'categories' => $categories,
            'model' => $recipe,
            'ingredients' => $ingredients
        ]);
    }

    public function deleteAction($params)
    {
        if (!User::isAdmin())
            return $this->error(403);
        $id = intval($params[0]);
        if (Recipe::getRecipeById($id) == null)
            return $this->error(404);

        $yes = boolval($params[1] === 'yes');
        if ($id > 0) {
            if ($yes) {
                Recipe::deleteRecipe($id);
                return $this->redirect('/recipe');
            }
            $recipe = Recipe::getRecipeById($id);
            return $this->render(null, [
                'recipe' => $recipe
            ]);
        } else {
            return $this->error(403);
        }
    }

    public function viewAction($params)
    {
        $id = intval($params[0]);
        if (Recipe::getRecipeById($id) == null)
            return $this->error(404);
        $recipe = Recipe::getRecipeById($id);
        $category = Category::getCategoryById($recipe['category_id']);
        $ingredients = Ingredient::getIngredientsByRecipeId($id);
        if (User::isUserAuthenticated())
            $favourite = Favourite::isFavourite($id, User::getCurrentAuthenticatedUser()['id']) ? 'true' : 'false';
        $favouriteCount = Favourite::getFavouritesCountByRecipeId($id);
        return $this->render(null, [
            'recipe' => $recipe,
            'category' => $category,
            'ingredients' => $ingredients,
            'favourite' => $favourite,
            'favourite_count' => $favouriteCount
        ]);
    }

}