<?php
namespace app\classes;

class BlockRequestPostGet{

    public static function block(string $message = '', string $redirect = ''){
        
        if($_SERVER["REQUEST_METHOD"] === 'POST' || $_SERVER["REQUEST_METHOD"] === 'GET'){
                setFlash("fail", $message);
                redirect(URL_BASE . $redirect);
                die();
            }
        }
}