<?php
//FRONTCONTROLLER

//Общие настройки

//подключение файлов системы
define('ROOT', dirname(__FILE__));
require_once(ROOT.'/components/Autoload.php');

//ROUTER called
$router = new Router();
$router->run();