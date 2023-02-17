<?php

function filtrarProfessor($professores, $curso){
 
    if(is_array($curso)){
        array_filter($professores, function($professor) use ($curso){
            if($curso["professor"] == $professor->idprofessor) echo $professor->nome;
        });
    }

    if(is_object($curso)){
            array_filter($professores, function($professor) use ($curso){ 
            if($curso->professor == $professor->idprofessor) echo $professor->nome; 
        });
    }

    return false;
}