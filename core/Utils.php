<?php

namespace core;

class Utils
{
    public static function filterArray($array, $fieldsList)
    {
        $newArray = [];
        foreach ($array as $key => $value)
            if (in_array($key, $fieldsList))
                $newArray[$key] = $value;
        return $newArray;
    }

    public static function moveUploadedFileWithNewName($directoryName, $photoPath)
    {
        do {
            $fileName = uniqid() . '.jpg';
            $newPath = "files/{$directoryName}/{$fileName}";
        } while (file_exists($newPath));
        move_uploaded_file($photoPath, $newPath);
        return $fileName;
    }

    public static function getOnePage($rows, $page, $limit)
    {
        $start = ($page - 1) * $limit;
        return array_slice($rows, $start, $limit);
    }
}