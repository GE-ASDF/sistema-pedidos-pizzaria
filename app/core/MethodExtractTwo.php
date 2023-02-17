<?php
namespace app\core;

class MethodExtractTwo{
    public static function extract($controller){

        $uri = Uri::uri();
        $folder = FolderExtract::extract($uri);
        $method = 'index';
        if(!$folder){
            $method = strtolower(Uri::uriExist($uri, 1));
            if(!method_exists($controller, $method)){
                $method = strtolower(Uri::uriExist($uri, 2));
                if(!method_exists($controller, $method)){
                    $method = "index";
                }
            }
        }

        if($method === ''){
            $method = "index";
        } 
        
        if(!method_exists($controller, $method)){
            $method = "index";
            $sliceIndexStartFrom = (!$folder) ? 1:2;
        }else{
            $sliceIndexStartFrom = (!$folder) ? 2:3;
        }

        return [
            $method, $sliceIndexStartFrom
        ];
    }
}