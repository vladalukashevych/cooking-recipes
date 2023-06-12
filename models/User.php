<?php

namespace models;

use core\Core;
use core\Utils;

class User
{
    protected static $tableName = 'user';

    public static function addUser($login, $password, $firstname, $lastname, $signupDate)
    {
        Core::getInstance()->db->insert(
            self::$tableName, [
                'login' => $login,
                'password' => self::hashPassword($password),
                'firstname' => $firstname,
                'lastname' => $lastname,
                'signup_date' => $signupDate
            ]
        );
    }

    public static function hashPassword($password)
    {
        return md5($password);
    }

    public static function updateUser($id, $updatesArray)
    {
        $updatesArray = Utils::filterArray($updatesArray,
            ['firstname', 'lastname', 'password', 'access_level', 'last_login_date']);
        if (!empty($updatesArray['password']))
            $updatesArray['password'] = self::hashPassword($updatesArray['password']);
        Core::getInstance()->db->update(
            self::$tableName, $updatesArray, [
            'id' => $id
        ]);
    }

    public static function deleteUser($id)
    {
        Core::getInstance()->db->delete(self::$tableName, [
            'id' => $id
        ]);
    }

    public static function isLoginExists($login)
    {
        $user = Core::getInstance()->db->select(self::$tableName, '*', [
            'login' => $login
        ]);
        return !empty($user);
    }

    public static function getUserByLoginAndPassword($login, $password)
    {
        $user = Core::getInstance()->db->select(self::$tableName, '*', [
            'login' => $login,
            'password' => self::hashPassword($password)
        ]);
        if (!empty($user))
            return $user[0];
        return null;
    }

    public static function authenticateUser($user)
    {
        $_SESSION['user'] = $user;
        self::updateUser($user['id'], [ 'last_login_date' => date('Y-m-d') ]);
    }

    public static function logoutUser()
    {
        unset($_SESSION['user']);
    }

    public static function isUserAuthenticated()
    {
        return isset($_SESSION['user']);
    }

    public static function getCurrentAuthenticatedUser()
    {
        return $_SESSION['user'];
    }

    public static function isAdmin()
    {
        $user = self::getCurrentAuthenticatedUser();
        return $user['access_level'] == 10;
    }

    public static function getUserById($id)
    {
        $user = Core::getInstance()->db->select(self::$tableName, '*', [
            'id' => $id
        ]);
        if (!empty($user))
            return $user[0];
        return null;
    }

    public static function getUserCount()
    {
        return Core::getInstance()->db->count(self::$tableName, null);
    }
    
    public static function getPrivilegedUsers()
    {
        $str = "select id, login, firstname, lastname, access_level, last_login_date from user 
                                                         where access_level > 1 order by access_level desc;";
        return Core::getInstance()->db->execute($str);
    }

    public static function getCountSignupsPerMonth($year)
    {
        return Core::getInstance()->db->countPerMonthInYear(self::$tableName, 'signup_date', $year);
    }

    public static function getUserCountByAccess()
    {
        $str = "select count(*) as 'count', access_level from user group by access_level order by access_level desc;";
        return Core::getInstance()->db->execute($str);
    }

    public static function getLastLoginedUsers($limit)
    {
        $str = "select id, login, firstname, lastname, access_level, last_login_date from user 
                                                                     order by last_login_date desc limit {$limit}";
        return Core::getInstance()->db->execute($str);
    }
}