<?php

namespace app\controllers;

use app\core\Controller;
use app\models\QueryBase;
use app\classes\NotLogged;
use app\classes\Validacao;
use app\classes\BlockNotAdmin;
use app\classes\BlockNotLogged;
use app\classes\ExtractPermissions;

class DetalhesPedidoController extends Controller{

    public function __construct(){   
        
        NotLogged::notLogged();
        BlockNotLogged::block($this,ExtractPermissions::extract("detalhespedido"));
        BlockNotAdmin::block($this, ExtractPermissions::extract("detalhespedido"), "Você não tem permissão para realizar esta ação.");

    }

   public function index(){
      
            $detalhes = '';
            
            $dados = [
                "CodigoPedido" => "required|existe:pedidos",
            ];
            
            $validar = (new Validacao)->validacao($dados);
       
            
            if($validar){

                $findBy = (new QueryBase)->fetch("pedidos", $validar, critery:" WHERE CodigoPedido = :CodigoPedido");

                if($findBy->Finalizado == 1){
                    setFlash("fail", "O pedido já foi finalizado.");
                    redirect(URL_BASE."detalhespedido/visualizar?CodigoPedido={$validar['CodigoPedido']}");
                }

                $pedido = (new QueryBase)->fetch("pedidos", $validar, critery:" WHERE CodigoPedido = :CodigoPedido");
                $detalhes = (new QueryBase)->fetchAll("detalhes_pedido AS DP, produtos AS P", $validar, 
                critery:" WHERE DP.CodigoPedido = :CodigoPedido AND DP.CodigoProduto = P.CodigoProduto");
                $produtos = (new QueryBase)->fetchAll("produtos");
                $CodigoPedido = $validar["CodigoPedido"];
            }else{
                setFlash("fail", "Não foi possível acessar a página de detalhes do pedido.");
                redirect(URL_BASE);
            }
        $dados = [
            "title" => "Detalhes do pedido",
            "view" => "pages/detalhespedido/Index",
            "CodigoPedido"=>$CodigoPedido,
            "detalhes" => $detalhes,
            "produtos" => $produtos,
            "pedido" => $pedido,
        ];
        $this->load("template", $dados); 
   } 
   public function visualizar(){

            $detalhes = '';

            $dados = [
                "CodigoPedido" => "required|existe:pedidos",
            ];
            
            $validar = (new Validacao)->validacao($dados);

            if($validar){
                $pedido = (new QueryBase)->fetch("pedidos", $validar, critery:" WHERE CodigoPedido = :CodigoPedido");
                if($pedido->Finalizado == 1){
                    setFlash("finalizado", "Este pedido já foi finalizado e alterações não são permitidas.", "primary");
                }
                if($pedido->PedidoPronto == 1){
                    setFlash("success", "O pedido já está pronto!", "success");
                }
                $detalhes = (new QueryBase)->fetchAll("detalhes_pedido AS DP, produtos AS P", $validar, 
                critery:" WHERE DP.CodigoPedido = :CodigoPedido AND DP.CodigoProduto = P.CodigoProduto");
                $produtos = (new QueryBase)->fetchAll("produtos");
                $CodigoPedido = $validar["CodigoPedido"];
            }else{
                setFlash("fail", "Não foi possível acessar a página de detalhes do pedido.");
                redirect(URL_BASE);
            }
        $dados = [
            "title" => "Detalhes do pedido",
            "view" => "pages/detalhespedido/Edit",
            "CodigoPedido"=>$CodigoPedido,
            "detalhes" => $detalhes,
            "produtos" => $produtos,
            "pedido" => $pedido,
        ];
     
        $this->load("template", $dados); 
   }
   public function insert(){
        
        $dados = [
            "CodigoProduto" => "required|existe:produtos",
            "Quantidade" => "required",
            "ObservacaoPedido" => "optional",
            "CodigoPedido" => "required|existe:pedidos",
        ];

       
        $validar = (new Validacao)->validacao($dados);
        
        if($validar){

        if($validar['Quantidade'] < 1){
            setFlash("fail", "A quantidade a ser pedida deve ser no mínimo 1.");
            redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            die();
        }
        
            $finalizado = (new QueryBase)->fetch("pedidos", ["CodigoPedido" => $validar["CodigoPedido"]], "CodigoPedido, Finalizado", critery: " WHERE CodigoPedido = :CodigoPedido");
            if($finalizado->Finalizado == 1){
                setFlash("insert", "Não é possível inserir novos itens no pedido, pois ele já foi finalizado.");
                redirect(URL_BASE."detalhespedido?CodigoPedido={$finalizado->CodigoPedido}");
                die();
            }

            $estoque = (new QueryBase)->fetch("produtos", ["CodigoProduto" => $validar["CodigoProduto"]], critery:" WHERE CodigoProduto = :CodigoProduto AND TemEstoque = 1");

            if($estoque){
                $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $validar["CodigoProduto"], "Quantidade" => $validar["Quantidade"]], critery:" WHERE CodigoProduto = :CodigoProduto AND Quantidade < 0 OR Quantidade < :Quantidade");
                $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $validar["CodigoProduto"], "Quantidade" => $validar["Quantidade"]], critery:" WHERE CodigoProduto = :CodigoProduto AND Quantidade < 0 OR Quantidade < :Quantidade");
                if($quantidade){
                    setFlash("fail", "Não há quantidade suficiente no estoque do produto {$estoque->NomeProduto}. Qtd. em estoque: {$quantidade->Quantidade}");
                    redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
                    die();
                }else{
                    $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $validar["CodigoProduto"]], critery:" WHERE CodigoProduto = :CodigoProduto");
                $dadosAtualizar = [
                    "CodigoProduto" => $validar["CodigoProduto"],
                    "Quantidade" => $quantidade->Quantidade - $validar["Quantidade"],
                ];
                $update = (new QueryBase)->update("estoque_produtos", $dadosAtualizar, toIgnore:"CodigoProduto", critery:" WHERE CodigoProduto = :CodigoProduto");
                if($update){
                    $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $validar["CodigoProduto"]], critery:" WHERE CodigoProduto = :CodigoProduto");
                    setFlash("update", "A quantidade do produto {$estoque->NomeProduto} em estoque foi atualizada: {$quantidade->Quantidade}", "success");
                }else{
                    setFlash("notupdate", "A quantidade do produto {$estoque->NomeProduto} em estoque NÃO foi atualizada: {$quantidade->Quantidade}");
                }
                }
            }
            
            $insert = (new QueryBase)->insert("detalhes_pedido", $validar);
            if($insert){
                setFlash("success", "Um novo item foi adicionado ao pedido.", 'success');
                redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            }else{
                setFlash("fail", "Não foi possível registrar o item do pedido. Tente novamente");
                redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            }   
        }else{
            $CodigoPedido = isset($_POST["CodigoPedido"]) ? strip_tags($_POST["CodigoPedido"]):"";
            if($CodigoPedido){
                setFlash("fail", "Não foi possível validar os dados do pedido. Tente novamente!");
                redirect(URL_BASE."detalhespedido?CodigoPedido={$CodigoPedido}");
            }else{
                setFlash("fail", "Não foi possível validar os dados do pedido. Tente novamente!");
                redirect(URL_BASE);
            }
        }
        
   }
   public function finalizar(){

    if($_POST){
   
    $CodigoPedido = isset($_POST["CodigoPedido"]) ? strip_tags($_POST["CodigoPedido"]):strip_tags($_GET["CodigoPedido"]);
    
    $dados = [
        "CodigoPedido" => "required|existe:pedidos",
        "Finalizado" => "required",
        "CodigoTipoPagamento" => "required",
        "ValorTroco" => "optional",
        "TotalPedido" => "required",
        "ValorPago" => "required",
        "Parcelas" => "optional",
        "ValorParcela" => "optional",
    ];

    $validar = (new Validacao)->validacao($dados);
    
    if($validar){
        $totalPedidoVerificacao = (float) number_format($validar["TotalPedido"], 2);
        $valorPago = (float) number_format($validar["ValorPago"], 2);
        $fetchPedido = (new QueryBase)->fetchAll("detalhes_pedido, produtos", ["CodigoPedido"=> $validar["CodigoPedido"]], critery: " WHERE CodigoPedido = :CodigoPedido AND detalhes_pedido.CodigoProduto = produtos.CodigoProduto");

        $subTotal = (float) 0.00;

        foreach($fetchPedido as $pedido){
            $subTotal = $subTotal + ($pedido->Quantidade * $pedido->PrecoProduto);
        }
 
        if(number_format($subTotal, 2) != $totalPedidoVerificacao){
            setOld("TipoPagamento", $validar["CodigoTipoPagamento"]);
            setOld("ValorTroco", $validar["ValorTroco"]);
            setOld("ValorPago", $validar["ValorPago"]);
            setOld("Parcelas", $validar["Parcelas"]);
            setOld("ValorParcela", $validar["ValorParcela"]);
            setFlash("fail", "O total do pedido está divergente do calculado. Tente novamente!");
            redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            die();
        }
        if(number_format($valorPago, 2) < $totalPedidoVerificacao){
            setOld("TipoPagamento", $validar["CodigoTipoPagamento"]);
            setOld("ValorTroco", $validar["ValorTroco"]);
            setOld("ValorPago", $validar["ValorPago"]);
            setOld("Parcelas", $validar["Parcelas"]);
            setOld("ValorParcela", $validar["ValorParcela"]);
            setFlash("fail", "O valor pago deve ser maior ou igual ao valor total do pedido.");
            redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            die();
        }
        $findBy = (new QueryBase)->fetch("pedidos", ["CodigoPedido" => $validar["CodigoPedido"]], critery:" WHERE CodigoPedido = :CodigoPedido");
        if($findBy->Finalizado == 1){
            setFlash("fail", "Este pedido já foi finalizado e não pode ser alterado.");
            redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            die();
        }
        $update = (new QueryBase)->update("pedidos", $validar, toIgnore:"CodigoPedido", critery:" WHERE CodigoPedido = :CodigoPedido");
        if($update){
            setFlash("success", "O pedido foi finalizado com sucesso!", "success");
            redirect(URL_BASE."Nota?CodigoPedido={$validar['CodigoPedido']}");
        }else{
            setFlash("fail", "Não foi possível finalizar o pedido. Tente novamente!");
            redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
        }

    }else{
        setFlash("fail", "Não foi possível finalizar o pedido. Tente novamente!");
        if($CodigoPedido){
            redirect(URL_BASE."detalhespedido?CodigoPedido={$CodigoPedido}");
        }else{
            redirect(URL_BASE."listarpedidos");
        }
    }
    
    }else{
        redirect(URL_BASE."listarpedidos");
    }
}
    public function delete(){
        
        $dados = [
            "CodigoPedido" => "required|existe:pedidos",
            "CodigoDetalhe" => "required|existe:detalhes_pedido",
        ];

        $validar = (new Validacao)->validacao($dados);
 
        if($validar){

            $pedido = (new QueryBase)->fetch("detalhes_pedido, pedidos", $validar, critery:" WHERE pedidos.CodigoPedido = :CodigoPedido AND detalhes_pedido.CodigoDetalhe = :CodigoDetalhe");
            if($pedido->Finalizado == 1 || $pedido->PedidoPronto == 1){
                setFlash("fail", "Não é possível apagar itens neste pedido, pois ele já foi finalizado.");
                redirect(URL_BASE."detalhespedido/visualizar?CodigoPedido={$pedido->CodigoPedido}");
                die();
            }
            $estoque = (new QueryBase)->fetch("detalhes_pedido AS DP, produtos AS P", ["CodigoDetalhe" => $validar["CodigoDetalhe"]], critery:" WHERE DP.CodigoDetalhe = :CodigoDetalhe AND DP.CodigoProduto = P.CodigoProduto AND P.TemEstoque = 1");
            if($estoque){
                $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $estoque->CodigoProduto], critery:" WHERE CodigoProduto = :CodigoProduto");
                $quantidadePedida = (new QueryBase)->fetch("detalhes_pedido", ["CodigoDetalhe" => $validar["CodigoDetalhe"]], critery:" WHERE CodigoDetalhe = :CodigoDetalhe");
                
                $dadosAtualizar = [
                    "CodigoProduto" => $quantidade->CodigoProduto,
                    "Quantidade" => $quantidade->Quantidade + $quantidadePedida->Quantidade,
                ];
                
                $update = (new QueryBase)->update("estoque_produtos", $dadosAtualizar, toIgnore:"CodigoProduto", critery:" WHERE CodigoProduto = :CodigoProduto");
                if($update){
                    $quantidade = (new QueryBase)->fetch("estoque_produtos", ["CodigoProduto" => $estoque->CodigoProduto], critery:" WHERE CodigoProduto = :CodigoProduto");
                    setFlash("update", "A quantidade do produto {$estoque->NomeProduto} em estoque foi atualizada: {$quantidade->Quantidade}", "success");
                }else{
                    setFlash("notupdate", "A quantidade do produto {$estoque->NomeProduto} em estoque NÃO foi atualizada: {$quantidade->Quantidade}");
                }
            }
            $delete = (new QueryBase)->delete("detalhes_pedido", ["CodigoDetalhe" => $validar["CodigoDetalhe"]], critery:" WHERE CodigoDetalhe = :CodigoDetalhe");
            if($delete){
                setFlash("success", "O item foi apagado com sucesso do pedido.","success");
                redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            }else{
                setFlash("fail", "O item não foi apagado do pedido.");
                redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            }
        }else{
            setFlash("fail", "Não foi possível validar os dados. Tente novamente!");
            if($validar["CodigoPedido"]){
                redirect(URL_BASE."detalhespedido?CodigoPedido={$validar['CodigoPedido']}");
            }
            redirect(URL_BASE);
        }

    }
}
