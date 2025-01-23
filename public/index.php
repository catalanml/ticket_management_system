<?php

require_once __DIR__ . '/../src/Core/Autoloader.php';
require_once __DIR__ . '/../src/Core/Router.php';

use App\Core\Router;


$router = new Router();
$router->handleRequest();

