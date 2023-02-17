<?php
namespace app\classes;

use app\classes\Block;
use app\classes\IsAdmin;
use app\classes\NotLogged;
use app\classes\BlockRequestPostGet;

class BlockNotAdmin{

    public static function block($controller, array $methodsToBlock, string $message = '', string $goTo = ''){
        
        $block = Block::getMethodsTolock($controller, $methodsToBlock);
        
        if(IsAdmin::isAdmin() === false && !$block){
            
            if(BlockRequestPostGet::block($message, $goTo)){
                setFlash("fail", "Você não tem permissão para realizar esta ação.");
                redirect(URL_BASE . $goTo);
                die();
            }
            
            
            if(!BlockRequestPostGet::block($message, $goTo) && !NotLogged::notLogged()){
                setFlash("fail", "Você não tem permissão para realizar esta ação.");
                redirect(URL_BASE . $goTo);
                die();
            }
            setFlash("fail", "Você não tem permissão para realizar esta ação.");
            redirect(URL_BASE . $goTo);
            die();
        }
       
    }

}