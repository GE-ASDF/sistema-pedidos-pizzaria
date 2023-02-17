<?php

namespace app\controllers;

use app\classes\IsAdmin;
use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class ListarClientesController extends Controller{
  
    public function __construct(){  

        NotLogged::notLogged();
        BlockNotLogged::block($this,ExtractPermissions::extract("cargos"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("cargos"), "Você não tem permissão para realizar esta ação.");
        
    }
  

   public function index(){

        $clientes = (new QueryBase)->fetchAll("clientes");

        $dados =[
            "title" => "Lista de clientes",
            "view" => "pages/clientes/Index",
            "clientes" => $clientes,
        ];

        $this->load("template", $dados); 
   } 
   public function delete(){
    
    if(!IsAdmin::isAdmin()): setFlash("fail", "Você não tem permissão para realizar esta ação.");
        redirect(URL_BASE."listarclientes"); die(); 
    endif;

    $dados = [
        "CodigoCliente" => "required|existe:clientes",
    ];

    $validar = (new Validacao)->validacao($dados);
    
    if($validar){
        $delete = (new QueryBase)->delete("clientes", $validar, " WHERE CodigoCliente = :CodigoCliente");
        if($delete){
            setFlash("success", "O cliente foi deletado com sucesso", "success");
            redirect(URL_BASE."listarclientes");
        }else{
            setFlash("success", "O cliente não foi deletado.");
            redirect(URL_BASE."listarclientes");
        }
    }else{
        setFlash("success", "O cliente não foi deletado, pois não foi possível validar os dados.");
        redirect(URL_BASE."listarclientes");
    }

}
}
