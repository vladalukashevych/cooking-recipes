<?php

use core\Core;
use core\DB;
use models\User;

include ('config/database.php');
include ('config/params.php');

spl_autoload_register(function($className) {
    $path = $className.'.php';
    if (is_file($path))
        require($path);
});

$core = Core::getInstance();
$core->Initialize();
$core->Run();
$core->Done();
