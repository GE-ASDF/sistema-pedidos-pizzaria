<?php
namespace app\classes;

use app\core\MethodExtract;
use app\core\MethodExtractTwo;

class Block{
    public static function getMethodsTolock($controller, array $blockMethods){
        $methods = get_class_methods($controller);
        [$actualMethod] = MethodExtractTwo::extract($controller);         
        $blockMethod = false;
        foreach($methods as $method){
        
            if(in_array($method, $blockMethods) and $method == $actualMethod){
                $blockMethod = true;
            }

        }
       
        return $blockMethod;
    }
}