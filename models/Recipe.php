<?php

namespace models;

use core\Core;
use core\Utils;

class Recipe
{
    protected static $tableName = 'recipe';

    public static function addRecipe($row)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions', 'create_date', 'category_id'];
        $row = Utils::filterArray($row, $fieldsList);
        Core::getInstance()->db->insert(self::$tableName, $row);
    }

    public static function deleteRecipe($id)
    {
        self::deleteRecipePhoto($id);
        Core::getInstance()->db->delete(self::$tableName, [
            'id' => $id
        ]);
    }

    public static function updateRecipe($id, $row)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions', 'category_id'];
        $row = Utils::filterArray($row, $fieldsList);
        Core::getInstance()->db->update(self::$tableName, $row, [
            'id' => $id
        ]);
    }

    public static function changePhoto($id, $newPhotoPath)
    {
        if (!empty($newPhotoPath)) {
            self::deleteRecipePhoto($id);
            $fileName = Utils::moveUploadedFileWithNewName('recipe', $newPhotoPath);
            Core::getInstance()->db->update(self::$tableName, [
                'photo' => $fileName
            ], [
                'id' => $id
            ]);
        }
    }

    public static function deleteRecipePhoto($id)
    {
        $recipe = self::getRecipeById($id);
        $photoPath = 'files/recipe/' . $recipe['photo'];
        if (is_file($photoPath))
            unlink($photoPath);
    }

    public static function getRecipes()
    {
        return Core::getInstance()->db->select(self::$tableName, "*", null, "order by create_date desc");
    }

    public static function getRecipeById($id)
    {
        $row = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        if (!empty($row))
            return $row[0];
        return null;
    }

    public static function getRecipesInCategory($category_id)
    {
        return Core::getInstance()->db->select(self::$tableName, '*', [
            'category_id' => $category_id
        ], "order by create_date desc");
    }

    public static function isRecipeExists($recipe)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions'];
        $recipe = Utils::filterArray($recipe, $fieldsList);
        $row = Core::getInstance()->db->select(self::$tableName, '*', $recipe);
        return !empty($row);
    }

    public static function getRecipeId($row)
    {
        $fieldsList = ['name', 'description', 'time', 'servings', 'calories', 'instructions'];
        $row = Utils::filterArray($row, $fieldsList);
        $id = Core::getInstance()->db->select(self::$tableName, 'id', $row);
        if (!empty($id))
            return intval($id[0]['id']);
        return null;
    }

    public static function getRecipesNameMatch($likePart)
    {
        return Core::getInstance()->db->selectLike(self::$tableName, '*', 'name',
            $likePart, 'order by create_date desc');
    }

    public static function getRandomRecipes($amount)
    {
        $rows = Core::getInstance()->db->select(self::$tableName);
        shuffle($rows);
        return array_slice($rows, 0, $amount, true);
    }

    public static function getRecipesCount()
    {
        return Core::getInstance()->db->count(self::$tableName, null);
    }

    public static function getRecipesCountPerMonth($year)
    {
        return Core::getInstance()->db->countPerMonthInYear(self::$tableName, 'create_date', $year);
    }

    public static function getCategoriesWithRecipesCountPerMonth($year)
    {
        return Core::getInstance()->db->countDistinctPerMonthInYear(self::$tableName,
            'create_date', $year, 'category_id');
    }

    public static function getMostLikedRecipes($limit)
    {
        $str = "select recipe.id, recipe.name, count(favourite.id) as 'count' from recipe inner join favourite 
                    on recipe.id = recipe_id group by recipe.id, recipe.name order by count desc limit {$limit}";
        return Core::getInstance()->db->execute($str);
    }

    public static function getRecipesByFilter($params)
    {
        $categoryId = $params['category_id'];
        $time = $params['time'];
        $calMin = $params['cal_min'];
        $calMax = $params['cal_max'];
        $serMin = $params['ser_min'];
        $serMax = $params['ser_max'];
        $order = $params['order'];

        $cal = self::checkMinMax($calMin, $calMax);
        $ser = self::checkMinMax($serMin, $serMax);

        $str = "select * from " . self::$tableName . " ";

        $whereParts = [];
        if (!empty($categoryId))
            $whereParts[] = "category_id = {$categoryId}";
        if (!empty($time))
            $whereParts[] = "time <= {$time}";
        if (!empty($cal))
            $whereParts[] = "calories = {$cal}";
        else {
            if (!empty($calMin))
                $whereParts[] = "calories >= {$calMin}";
            if (!empty($calMax))
                $whereParts[] = "calories <= {$calMax}";
        }
        if (!empty($ser))
            $whereParts[] = "servings = {$ser}";
        else {
            if (!empty($serMin))
                $whereParts[] = "servings >= {$serMin}";
            if (!empty($serMax))
                $whereParts[] = "servings <= {$serMax}";
        }
        if (!empty($whereParts)) {
            $wherePartsString = "where " . implode(' and ', $whereParts);
            $str .= $wherePartsString;
        }
        if (!empty($order))
            $str .= " order by create_date {$order}";
        return Core::getInstance()->db->execute($str);
    }

    private static function checkMinMax(&$min, &$max)
    {
        if (empty($min) || empty($max))
            return;
        if ($min > $max) {
            $temp = $min;
            $min = $max;
            $max = $temp;
            return;
        }
        return $min;
    }
}