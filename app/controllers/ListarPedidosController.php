<?php

namespace app\controllers;

use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class ListarPedidosController extends Controller{

    public function __construct(){   

        NotLogged::notLogged();
        BlockNotLogged::block($this, ExtractPermissions::extract("listarpedidos"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("listarpedidos"), "Você não tem permissão para realizar esta ação");

    }

   public function index(){

        $pedidos = (new QueryBase)->fetchAll("pedidos");
        
        $dados =[
            "title" => "Lista de produtos",
            "view" => "pages/pedidos/Index",
            "pedidos" => $pedidos
        ];

        $this->load("template", $dados); 
   } 

   public function delete(){

    
    $dados = [
        "CodigoPedido" => "required|existe:pedidos",
    ];

    $validar = (new Validacao)->validacao($dados);
    if($validar){
        $pedido = (new QueryBase)->fetch("pedidos", $validar, critery:" WHERE CodigoPedido = :CodigoPedido AND Finalizado != 1 AND PedidoPronto != 1");
        $dadosAtualizar = [];
        
        
        if($pedido){
            $detalhes = (new QueryBase)->fetchAll("detalhes_pedido", ["CodigoPedido"=>$pedido->CodigoPedido], fields:"CodigoProduto, Quantidade", critery:" WHERE CodigoPedido = :CodigoPedido");

            if($detalhes){
                foreach($detalhes as $detalhe){
                    $produto = (new QueryBase)->fetch("produtos AS P, estoque_produtos AS EP", ["CodigoProduto" => $detalhe->CodigoProduto], fields:"EP.CodigoProduto, EP.Quantidade", critery:" WHERE EP.CodigoProduto = :CodigoProduto AND P.TemEstoque = 1");
                    if($produto){
                        $dadosAtualizar = [
                            "CodigoProduto" => $produto->CodigoProduto,
                            "Quantidade" => $produto->Quantidade + $detalhe->Quantidade,
                        ];
                    }
                    
                }
                $update = (new QueryBase)->update("estoque_produtos", $dadosAtualizar, toIgnore:"CodigoProduto", critery:" WHERE CodigoProduto = :CodigoProduto");
                if($update){
                    $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $dadosAtualizar['CodigoProduto']], critery:" WHERE CodigoProduto = :CodigoProduto");
                    setFlash("update", "A quantidade do produto {$quantidade->NomeProduto} em estoque foi atualizada: {$quantidade->Quantidade}", "success");
                }else{
                    $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $dadosAtualizar['CodigoProduto']], critery:" WHERE CodigoProduto = :CodigoProduto");
                    setFlash("notupdate", "A quantidade do produto {$quantidade->NomeProduto} em estoque NÃO foi atualizada: {$quantidade->Quantidade}");
                }
            }
        }
        $delete = (new QueryBase)->delete("pedidos", $validar, critery:" WHERE CodigoPedido = :CodigoPedido");
        if($delete){
            setFlash("success", "O pedido foi apagado com sucesso.", 'success');
            redirect(URL_BASE."listarpedidos");
        }else{
            setFlash("fail", "O pedido não foi apagado.");
            redirect(URL_BASE."listarpedidos");
        }
    }else{
        setFlash("fail", "Não foi possível validar os dados. Tente novamente!");
        redirect(URL_BASE."listarpedidos");
    }

}

}
