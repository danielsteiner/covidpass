<?php
set_time_limit(600);
ini_set('max_execution_time', '600');
ini_set('memory_limit', '1G');
require __DIR__ . '/helpers.php';

use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Dotenv\Dotenv;

if(!function_exists("env")) {
    function env($var){
        return $_ENV[$var];
    }
}
if(!function_exists("dd")) {
    function dd($var){
        dump($var);
        die();
    }
}


$log = new Logger('covidpass');
$error = new Logger("covidpass.error");

$log->pushHandler(new StreamHandler(__DIR__."/../logs/log_".date('y-m-d').".log", Logger::INFO));
$error->pushHandler(new StreamHandler(__DIR__."/../logs/errors_".date('y-m-d').".log", Logger::ERROR));
ErrorHandler::register($error);

$dotenv = Dotenv::createImmutable(__DIR__."/../");
$dotenv->load();


if(env("APP_DEBUG")) {
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register(); 
} else {
    set_error_handler('productionErrorHandler');     
}

function productionErrorHandler(int $errNo, string $errMsg, string $file, int $line) {
    $GLOBALS["error"]->error($errMsg." in file ".$file." on line ".$line);
    die();
}