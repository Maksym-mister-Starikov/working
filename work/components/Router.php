<?php

class Router
{
    private $routes;
    
    public function __construct() 
    {
        //находим путь к rotes 
        $routesPath= ROOT.'/config/routes.php';
        //подключаем его
        $this->routes= include($routesPath);
        
    }
    
    private function getURI()
    {
        //проверяем строку запроса на запрос
        if(!empty($_SERVER['REQUEST_URI']))
        {
            //обрезаем /,и передаем его в run
            return trim($_SERVER['REQUEST_URI'], '/');
        }  
    }
    
    public function run() 
    {
        //Получить строку запроса
        $uri = $this->getURI();
        //проверить наличие запроса
        foreach ($this->routes as $uriPattern => $path)
        {
            //определить если есть совпадение 
            if(preg_match("~$uriPattern~", $uri))
            {
                
                //получаем внутрений путь из внешнего согласно правилу 
                $internalRoute= preg_replace("~$uriPattern~", $path, $uri);
                //контролер и екшен разделяем 
                $segments= explode('/', $internalRoute);
                
                //имя контролера
                //Извлекаем первый елемент масива из segments=>name->Controller
                $controllerName= array_shift($segments).'Controller';
                //переводим 1 букву в верхний регистор
                $controllerName= ucfirst($controllerName);
                //имя екшена
                $actionName='action'.ucfirst(array_shift($segments));
                
                $parametrs=$segments;
                
                
                //подключить фаил класса контролера
                $controllerFile=ROOT.'/controllers/'.$controllerName.'.php';
                if(file_exists($controllerFile)){
                include_once ($controllerFile); }
            
                //создать обьек и вызвать метод
                $controllerObject= new $controllerName;
                //передаем параметры в наш екшен через обьек в читабельном виде
                $result=call_user_func_array(array($controllerObject,$actionName),$parametrs);
                if($result != null){
                   break;
                }
            }
        }
    }
}
