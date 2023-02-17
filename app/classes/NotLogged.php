<?php
namespace app\classes;


class NotLogged{
    public static function notLogged(){
        if(!isset($_SESSION[SESSION_LOGIN])){
            return redirect(URL_BASE . "login");
        }
    }
}