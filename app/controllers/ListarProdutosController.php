<?php

namespace app\controllers;

use app\core\Uri;
use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\core\MethodExtract;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class ListarProdutosController extends Controller{

    public function __construct(){
        NotLogged::notLogged();
        BlockNotLogged::block($this, ExtractPermissions::extract("listarprodutos"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("listarprodutos"),"Você não tem permissão para realizar esta ação.");
    }

   public function index(){

        $pizzas = (new QueryBase)->fetchAll("Produtos");
        
        $dados =[
            "title" => "Lista de produtos",
            "view" => "pages/produtos/Index",
            "pizzas" => $pizzas
        ];

        $this->load("template", $dados); 
   } 
   public function delete(){

    $dados = [
        "CodigoProduto" => "required|existe:produtos",
    ];

    $validar = (new Validacao)->validacao($dados);

    if($validar){
        $delete = (new QueryBase)->delete("produtos", $validar, critery:" WHERE CodigoProduto = :CodigoProduto");
        if($delete){
            setFlash("success", "O produto foi apagado com sucesso.", 'success');
            redirect(URL_BASE."listarprodutos");
        }else{
            setFlash("fail", "O produto não foi apagado.");
            redirect(URL_BASE."listarprodutos");
        }
    }else{
        setFlash("fail", "Não foi possível validar os dados. Tente novamente!");
        redirect(URL_BASE."listarprodutos");
    }

}
}
