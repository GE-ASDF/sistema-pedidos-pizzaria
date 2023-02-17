<?php
namespace app\classes;

use app\classes\Block;
use app\classes\IsAdmin;
use app\classes\NotLogged;
use app\classes\BlockRequestPostGet;

class BlockNotAdmin{

    public static function block($controller, array $methodsToBlock, string $goTo = ''){
        
        $block = Block::getMethodsTolock($controller, $methodsToBlock);
       
    }

}