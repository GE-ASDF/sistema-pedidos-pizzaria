<?php

function printAll($dados){
    if(is_array($dados)){
        foreach($dados as $key => $dado){
            echo $dado[$key];
        }
    }
}

function countAll($dados){
    return @count($dados);
}