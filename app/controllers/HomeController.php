<?php

namespace app\controllers;

use app\classes\BlockNotAdmin;
use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotLogged;

class HomeController extends Controller{

    public function __construct(){   
        
        NotLogged::notLogged();
        BlockNotLogged::block($this, ["index", "pronto"], "login");
        
    }

   public function index(){
        
        $tipoPedidos = (new QueryBase)->fetchAll("tipo_pedido");
        $clientes = (new QueryBase)->fetchAll("clientes");
        $funcionarios = (new QueryBase)->fetchAll("funcionarios");
        $pedidos = (new QueryBase)->fetchAll("pedidos", critery:" WHERE Finalizado = 1 AND PedidoPronto != 1 ORDER BY HoraPedido DESC");
        $dadosPedido = array();

        foreach($pedidos as $pedido){
            $detalhes = (new QueryBase)->fetchAll("produtos AS P, detalhes_pedido AS DP, categorias_produto AS CP", ["CodigoPedido" => $pedido->CodigoPedido], critery:" WHERE P.CodigoCategoria = CP.CodigoCategoria AND CP.NomeCategoria LIKE 'pizzas' AND DP.CodigoPedido = :CodigoPedido AND P.CodigoProduto = DP.CodigoProduto");
            $dadosPedido[] = [
                "CodigoPedido" => $pedido->CodigoPedido,
                "PedidoPronto" => $pedido->PedidoPronto,
                "Detalhes" => $detalhes
            ];
        }
        $dados =[
            "title" => "Página inicial",
            "view" => "index",
            "tipoPedidos" => $tipoPedidos, 
            "clientes" => $clientes,
            "funcionarios" => $funcionarios,
            "pedidos" => $dadosPedido,
            "permissoes" => PERMISSOES
        ];

        $this->load("template", $dados); 
   } 

   public function pronto(){

        $dados = [
            "CodigoPedido" => "required|existe:pedidos",
            "PedidoPronto" => "required",
        ];
        
        $validar = (new Validacao)->validacao($dados);
        
        if($validar){
            $update = (new QueryBase)->update("pedidos", $validar, toIgnore:"CodigoPedido", critery:" WHERE CodigoPedido = :CodigoPedido");
            if($update){
                setFlash("success", "O pedido Nº: #{$validar['CodigoPedido']} está pronto.", "success");
                redirect(URL_BASE);
            }else{
                setFlash("fail", "Não foi possível finalizar o pedido. Tente novamente!");
                redirect(URL_BASE);
            }
        }else{
            setFlash("fail", "Não foi possível validar os dados do pedido. Tente novamente!");
            redirect(URL_BASE);
        }
   }

}
