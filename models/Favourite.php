<?php

namespace models;

use core\Core;

class Favourite
{
    protected static $tableName = 'favourite';

    public static function addFavourite($user_id, $recipe_id)
    {
        Core::getInstance()->db->insert(self::$tableName, [
            'user_id' => $user_id,
            'recipe_id' => $recipe_id
        ]);
    }

    public static function deleteFavourite($user_id, $recipe_id)
    {
        Core::getInstance()->db->delete(self::$tableName, [
            'user_id' => $user_id,
            'recipe_id' => $recipe_id
        ]);
    }

    public static function getFavouritesByUserId($id)
    {
        $rows = Core::getInstance()->db->select(self::$tableName, '*', [
            'user_id' => $id
        ], "order by id desc");
        return $rows;
    }

    public static function getFavouriteByUserAndRecipeId($user_id, $recipe_id)
    {
        $row = Core::getInstance()->db->select(self::$tableName, '*', [
            'user_id' => $user_id,
            'recipe_id' => $recipe_id
        ]);
        if (!empty($row))
            return $row[0];
        return null;
    }

    public static function isFavourite($recipe_id, $user_id)
    {
        $row = Core::getInstance()->db->select(self::$tableName, '*', [
            'user_id' => $user_id,
            'recipe_id' => $recipe_id
        ]);
        if (!empty($row))
            return true;
        return false;
    }

    public static function getFavouritesCountByRecipeId($id)
    {
        $count = Core::getInstance()->db->count(self::$tableName, [
            'recipe_id' => $id
        ]);
        return $count;
    }
}