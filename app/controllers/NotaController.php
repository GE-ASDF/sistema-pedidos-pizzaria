<?php

namespace app\controllers;

use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class NotaController extends Controller{

    public function __construct(){  
         
        NotLogged::notLogged();
        BlockNotLogged::block($this,ExtractPermissions::extract("nota"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("nota"), "Você não tem permissão para realizar esta ação.");

    }

   public function index(){
        $config = (new QueryBase)->fetch("config");
        
        $dadosValidar = [
            "CodigoPedido" => "required|existe:pedidos",
        ];

        $validar = (new Validacao)->validacao($dadosValidar);

        if($validar){
            $pedido = (new QueryBase)->fetch("pedidos", $validar, critery: " WHERE CodigoPedido = :CodigoPedido");
            $cliente = (new QueryBase)->fetch("clientes", ["CodigoCliente" => $pedido->CodigoCliente], critery:" WHERE CodigoCliente = :CodigoCliente");
            $detalhes = (new QueryBase)->fetchAll("detalhes_pedido", $validar, critery: " WHERE CodigoPedido = :CodigoPedido");
            $paraNota = array();
            foreach($detalhes as $detalhe){
                $produto = (new QueryBase)->fetch("produtos", ["CodigoProduto"=>$detalhe->CodigoProduto], critery: "WHERE CodigoProduto = :CodigoProduto");
                $paraNota[] = [
                    "NomeProduto" => $produto->NomeProduto,
                    "PrecoProduto" => $produto->PrecoProduto,
                    "Quantidade" => $detalhe->Quantidade,
                ];
            }
            
            $dados =[
                "title" => "Cupom fiscal",
                "view" => "pages/pedidos/Nota",
                "config" => $config,
                "cliente" => $cliente,
                "pedido" => $pedido,
                "detalhes" => $detalhes,
                "produtos" => $paraNota,
            ];
            $this->load("template", $dados); 
        }else{
            setFlash("fail", "Não foi possível validar os dados. Tente novamente!");
            redirect(URL_BASE);
        }
        
        
   } 

}
