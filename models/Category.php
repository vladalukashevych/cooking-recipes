<?php

namespace models;

use core\Core;
use core\Utils;

class Category
{
    protected static $tableName = 'category';

    public static function addCategory($name, $photoPath)
    {
        if (!empty($photoPath)) {
            $fileName = Utils::moveUploadedFileWithNewName('category', $photoPath);
            Core::getInstance()->db->insert(self::$tableName, [
                'name' => $name,
                'photo' => $fileName
            ]);
        } else {
            Core::getInstance()->db->insert(self::$tableName, [
                'name' => $name
            ]);
        }
    }

    public static function getCategoryById($id)
    {
        $rows = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        if (!empty($rows))
            return $rows[0];
        return null;
    }

    public static function deleteCategory($id)
    {
        self::deleteCategoryPhoto($id);
        Core::getInstance()->db->delete(self::$tableName, [
            'id' => $id
        ]);
    }

    public static function updateCategory($id, $newName)
    {
        Core::getInstance()->db->update(self::$tableName, [
            'name' => $newName
        ], [
            'id' => $id
        ]);
    }

    public static function changePhoto($id, $newPhotoPath)
    {
        if (!empty($newPhotoPath)) {
            self::deleteCategoryPhoto($id);
            $fileName = Utils::moveUploadedFileWithNewName('category', $newPhotoPath);
            Core::getInstance()->db->update(self::$tableName, [
                'photo' => $fileName
            ], [
                'id' => $id
            ]);
        }
    }

    public static function deleteCategoryPhoto($id)
    {
        $category = self::getCategoryById($id);
        $photoPath = 'files/category/' . $category['photo'];
        if (is_file($photoPath))
            unlink($photoPath);
    }

    public static function getCategories()
    {
        return Core::getInstance()->db->select(self::$tableName);
    }

    public static function isCategoryExistsByName($name)
    {
        $category = Core::getInstance()->db->select(self::$tableName, '*', [
            'name' => $name
        ]);
        return !empty($category);
    }

    public static function isCategoryExists($id)
    {
        $category = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        return !empty($category);
    }

    public static function getCategoryCount()
    {
        return Core::getInstance()->db->count(self::$tableName, null);
    }

    public static function getRecipeCountPerCategory()
    {
        $str = "select count(recipe.id) as 'count', category.name from recipe inner join category 
                                                   on category.id = category_id group by category.name;";
        return Core::getInstance()->db->execute($str);
    }

    public static function getAverageIngredientCountPerCategory()
    {
        $str = "select round(avg(r.ing)) as 'average', category.name from 
                 ( select recipe.category_id as 'category_id', count(ingredient.id) as 'ing' 
                   from recipe inner join ingredient on recipe.id = recipe_id GROUP by recipe_id) r 
                     inner join category on r.category_id = category.id group by category.name;";
        return Core::getInstance()->db->execute($str);
    }

    public static function getAverageTimeServingsCaloriesPerCategory()
    {
        $str = "select round(avg(time)) as 'time', round(avg(calories)) as 'calories', round(avg(servings))
                as 'servings', category.name, category.id from recipe inner join category on category.id = category_id 
                group by category.name, category.id;";
        return Core::getInstance()->db->execute($str);
    }

    public static function getMostLikedCategories($limit)
    {
        $str = "select category.id, category.name, count(favourite.id) as 'count' from category inner join recipe on category.id = category_id inner join favourite 
                    on recipe.id = recipe_id group by category.id, category.name order by count desc limit {$limit}";
        return Core::getInstance()->db->execute($str);
    }

}