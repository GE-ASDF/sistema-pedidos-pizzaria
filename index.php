<?php

session_start();

require 'config/config.php';
require 'app/core/Core.php';
require 'vendor/autoload.php';
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
$core = new Core;

try{
    $core->run();
}catch(Throwable $e){
    $whoops = new Run;
    $whoops->writeToOutput(true);
    $whoops->pushHandler(new PrettyPageHandler);
    $html = $whoops->handleException($e);
}
/*
echo "contoller: " .$core->getController();
echo "<br>Método : " .$core->getMetodo();
$parametros = $core->getParametros();
foreach ($parametros as $param)
    echo "<br>Parâmetro : " .$param;*/

