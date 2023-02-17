<?php
namespace app\classes;

use Throwable;
use app\core\Uri;

class Api{

    public static function api(){
        
        $uri = self::getUri();
        $method = self::getMethod();
        $url = self::getUrl($uri);
        $cursos = self::checkUrl($url);

        if($cursos){
            return self::instanceClass($cursos, $url, $method);                   
        }else{
            return redirect(URL_BASE."api/cursos/");
        }
        
    }

    private static function getUri(){
        $uri = Uri::uri();
        return $uri;
    }

    private static function getMethod(){
        $method = $_SERVER["REQUEST_METHOD"];
        return $method;
    }

    private static function getUrl($uri){
        $url = [];
        foreach($uri as $newUrl){
            if($newUrl !== '' && strtolower($newUrl) !== 'cronoestudos'){
                $url[] = $newUrl;
            }
        }
        return $url;
    }

    private static function checkUrl($url){
        $cursos = '';
        if(in_array("api", $url)){
            $cursos =  "app\models\\".ucfirst($url[1])."\\".ucfirst($url[1]);
        }else{
            return redirect(URL_BASE."api/cursos/");
        }
        return $cursos;
    }

    private static function instanceClass($cursos, $url, $method){
        if(self::classExist($cursos)){
            array_shift($url);
            if($url){
                array_shift($url);
                try{
                    $methodExist = self::methodExist($cursos, $method);
                    if($methodExist){
                        $response = self::callMethod($cursos, $method, $url);
                        echo json_encode($response);
                    }else{
                        return redirect(URL_BASE);
                    }
                }catch(Throwable $e){
                    var_dump($e->getMessage());
                }
            }
        }else{
            return redirect(URL_BASE."api/cursos/");
        }
    }

    private static function classExist($cursos){
        return class_exists($cursos);
    }

    private static function methodExist($cursos, $method){
        return method_exists(new $cursos, $method);
    }

    private static function callMethod($cursos, $method, $url){
        return call_user_func_array(array(new $cursos, strtolower($method)), $url);
    }
    
}