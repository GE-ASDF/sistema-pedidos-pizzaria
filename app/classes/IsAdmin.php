<?php
namespace app\classes;

class IsAdmin{
    public static function isAdmin(){
        $colaborador = $_SESSION[SESSION_LOGIN]['Cargo'];
        
        if($colaborador == null){
            return false;
        }
        
        if(strtolower($colaborador) != "administrador"){
            return false;
        }
        return true;
    }
}