<?php


function setOld($key, $valor){

    if(isset($_SESION["old"][$key])){
        unset($_SESSION["old"][$key]);
    }

    if(!isset($_SESSION["old"][$key])){
        $_SESSION["old"][$key] = $valor;
    }

}

function getOld($key){

    if(isset($_SESSION["old"][$key])){
        $valor = $_SESSION["old"][$key];
        unset($_SESSION["old"][$key]);
        return $valor;
    }
    
}