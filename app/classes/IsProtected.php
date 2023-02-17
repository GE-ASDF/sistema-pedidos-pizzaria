<?php
namespace app\classes;

use Exception;

class IsProtected{
    public static function isProtected(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            throw new Exception("Esta ação não é possível com o seu nível de acesso atual.");
            die();
        }
        return redirect(URL_BASE);
    }
}