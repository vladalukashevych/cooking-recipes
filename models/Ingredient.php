<?php

namespace models;

use core\Core;
use core\Utils;

class Ingredient
{
    protected static $tableName = 'ingredient';

    public static function addIngredient($row)
    {
        $fieldsList = ['name', 'amount', 'recipe_id'];
        $row = Utils::filterArray($row, $fieldsList);
        Core::getInstance()->db->insert(self::$tableName, $row);
    }

    public static function deleteIngredient($id)
    {
        Core::getInstance()->db->delete(self::$tableName, [
            'id' => $id
        ]);
    }

    public static function updateIngredient($id, $row)
    {
        $fieldsList = ['name', 'amount', 'recipe_id'];
        $row = Utils::filterArray($row, $fieldsList);
        Core::getInstance()->db->update(self::$tableName, $row, [
            'id' => $id
        ]);
    }

    public static function getIngredientById($id)
    {
        $row = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        if (!empty($row))
            return $row[0];
        return null;
    }

    public static function getIngredientsByRecipeId($recipe_id)
    {
        $rows = Core::getInstance()->db->select(self::$tableName, '*', [
            'recipe_id' => $recipe_id
        ]);
        return $rows;
    }

}