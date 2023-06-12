<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Category;
use models\Recipe;
use models\User;

class AdministrationController extends Controller
{
    public function statisticsAction()
    {
        if (!User::isAdmin())
            return $this->error(403);
        $recipeCountPerCategory = Category::getRecipeCountPerCategory();
        $averageTSCPerCategory = Category::getAverageTimeServingsCaloriesPerCategory();
        $recipesPerMonth = Recipe::getRecipesCountPerMonth(2023);
        $categoriesWithRecipesPerMonth = Recipe::getCategoriesWithRecipesCountPerMonth(2023);
        $avrIngredientCountPerCategory = Category::getAverageIngredientCountPerCategory();
        $recipesCount = Recipe::getRecipesCount();
        $categoriesCount = Category::getCategoryCount();
        $mostLikedCategories = Category::getMostLikedCategories(10);
        $mostLikedRecipes = Recipe::getMostLikedRecipes(10);

        $userCount = User::getUserCount();
        $privilegedUsers = User::getPrivilegedUsers();
        $signupsPerMonth = User::getCountSignupsPerMonth(2023);
        $userCountByAccess = User::getUserCountByAccess();
        $lastLoginedUsers = User::getLastLoginedUsers(10);

        return $this->render(null, [
            'recipe_count_per_category' => $recipeCountPerCategory,
            'average_tsc_per_category' => $averageTSCPerCategory,
            'average_ingredient_per_category' => $avrIngredientCountPerCategory,
            'recipes_per_month' => $recipesPerMonth,
            'categories_with_recipes_per_month' => $categoriesWithRecipesPerMonth,
            'recipes_count' => $recipesCount,
            'categories_count' => $categoriesCount,
            'most_liked_categories' => $mostLikedCategories,
            'most_liked_recipes' => $mostLikedRecipes,

            'user_count' => $userCount,
            'privileged_users' => $privilegedUsers,
            'signups_per_month' => $signupsPerMonth,
            'user_count_by_access' => $userCountByAccess,
            'last_logined_users' => $lastLoginedUsers
        ]);
    }

    public function indexAction()
    {
        return $this->redirect('/administration/statistics');
    }

    public function backupAction($params = null)
    {
        if (!User::isAdmin())
            return $this->error(403);
        if (in_array("server", $params))
        {
            $res = Core::getInstance()->db->backup(getcwd()."\backups",DATABASE_LOGIN, DATABASE_PASSWORD, DATABASE_BASENAME);

            if ($res[0] == 1)
                echo '<script type="text/javascript">
                    window.onload = function () { alert("Backup is completed."); }                     
                </script>';
            else
                echo '<script type="text/javascript">
                        window.onload = function () { alert("Error occurred. Backup was not completed."); } 
                </script>';
            return $this->render();
        }
        elseif (in_array("download", $params))
        {
            $res = Core::getInstance()->db->backup(getcwd()."\backups",DATABASE_LOGIN, DATABASE_PASSWORD, DATABASE_BASENAME);
            if ($res[0] == 0)
                echo '<script type="text/javascript">
                    window.onload = function () { alert("Error occurred."); } 
                </script>';
            else {
                $filename = $res[1];
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false);
                header('Content-Type: application/sql');
                header('Content-Disposition: attachment; filename="'. basename($filename) . '";');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($filename));

                readfile($filename);
                unlink($filename);
            }
            return $this->render();
        }
        return $this->render();
    }
}