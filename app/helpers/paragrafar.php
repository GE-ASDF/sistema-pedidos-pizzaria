<?php

function paragrafar(string $texto, bool $limitar = false, int $numCaract = 100, $simbolo = "..."){
    
    $p = $texto;
    for($i = 0; $i < strlen($texto);$i++){
        if($texto[$i] == "\n"){
            $p = $limitar == true ? mb_strimwidth(str_replace('\n', "</p>", $texto),0, $numCaract, $simbolo):str_replace('\n', "</p>", $texto);
        }
    } 
    return $p;
}

function limitar(string $texto, int $numCaract = 100, $simbolo = "..."){
    if($texto){
        return mb_strimwidth($texto,0, $numCaract, $simbolo);
    }
}