<?php

use React\Http\Message\Response;
use React\MySQL\ConnectionInterface;
use Ruggmatt\Camp\Server\CORS;
use Ruggmatt\Camp\Users\UserCreatorController;
use Ruggmatt\Camp\Users\UserListController;
use Ruggmatt\Camp\Users\UserLookupController;

require __DIR__ . '/../vendor/autoload.php';

$credentials = "{$_ENV['MYSQLUSER']}:{$_ENV['MYSQLPASSWORD']}@{$_ENV['MYSQLHOST']}:{$_ENV['MYSQLPORT']}/{$_ENV['MYSQLDATABASE']}?idle=0.001";
$db = (new \React\MySQL\Factory())->createLazyConnection($credentials);

$container = new FrameworkX\Container([
    ConnectionInterface::class => fn () => $db
]);
$app = new FrameworkX\App($container, CORS::class);
$app->get('/', fn () => Response::json("Hello wörld!"));
$app->get('/users/{id}', UserLookupController::class);
$app->post('/users/new', UserCreatorController::class);
$app->get('/users', UserListController::class);
$app->run();
