<?php
namespace app\core;

use app\classes\IsAdmin;

class Controller{
     public function load($viewName, $viewData=array()){
       extract($viewData); 
       include "app/views/{$viewName}".".php";
   }

   protected static function isProtected(){
    
        if(!isset($_SESSION[SESSION_LOGIN]) && !IsAdmin::isAdmin()){
            return redirect(URL_BASE . "login");
        }

        if(isset($_SESSION[SESSION_LOGIN]) && !IsAdmin::isAdmin()){
          return redirect(URL_BASE);
        }
   }

}
