<?php

namespace app\classes;

class AjaxType{

    public function isAjax(){
        $header = ( array_key_exists( 'HTTP_X_REQUESTED_WITH', $_SERVER ) ?
        $_SERVER['HTTP_X_REQUESTED_WITH'] : '' );
        return ( strcmp( $header, 'xmlhttprequest' ) == 0 );
    }
    
}