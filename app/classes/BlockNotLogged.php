<?php
namespace app\classes;

class BlockNotLogged{
    public static function block($controller, array $blockMethods, string $goTo = ''){

        $methodToBlock = Block::getMethodsTolock($controller, $blockMethods);
        
        if(!isset($_SESSION[SESSION_LOGIN]) && $methodToBlock){
            if(BlockRequestPostGet::block("Você não tem permissão para realizar esta ação.", "login")){
                die();
            }
            redirect(URL_BASE . $goTo);
        }
        if(!isset($_SESSION[SESSION_LOGIN])){
            if(BlockRequestPostGet::block("Você não tem permissão para realizar esta ação.", "login")){
                die();
            }
        }
    }
}