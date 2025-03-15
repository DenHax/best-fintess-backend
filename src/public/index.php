<?php

use App\Controller\UserController;
use App\Core\Config;
use App\Core\Router;
use App\Repository\User\UserRepo;

require_once __DIR__ . "/../vendor/autoload.php";

$config_path = '../config.ini';

$config = new Config($config_path);

$userRepo = new UserRepo($config->getSection("database"));

$userHandler = new UserController($userRepo);

$router = new Router();

$router->get('/users/', [$userHandler, 'handleAllUsers']);

$router->post('/users/', [$userHandler, 'handleUserRegistration']);

$router->resolve();
