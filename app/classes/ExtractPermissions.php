<?php
namespace app\classes;

class ExtractPermissions{
    
    public static function extract(string $uri = 'home', bool $index = false){

        $metodos = array();
        $exploded = array();
        
        foreach(PERMISSOES as $key => $value){
            if(strtolower(PERMISSOES[$key]["NomeControle"]) === strtolower($uri)){
                $metodos = $value["Metodos"];
            }
        }

        foreach($metodos as $metodo){
            $exploded[] = $metodo->NomeMetodo;
        }
       
        if($index){
            array_push($exploded, "index", $metodos);
            return $exploded;
        }else{
            return $exploded;
        }
        
    }
}