<div class="container mt-4">
    <div id="cadastro-mensagem-cargo" class="mensagem">
        <?php echo getFlash("success"); ?>
        <?php echo getFlash("fail"); ?>
        <?php echo getFlash("finalizado"); ?>
        <?php echo getFlash("update"); ?>
        <?php echo getFlash("notupdate"); ?>
    </div>
   
    <h1>Detalhes do pedido: #<?php echo $pedido->CodigoPedido ?></h1>

    <div class="card mt-3">
        <form id="form-cadastro-pedidos" action="<?php echo URL_BASE ?>detalhespedido/insert" method="POST">
            <div class="form-group d-grid">
                <h2 class="text fs-5">Informações do pedido</h2>
                <div class="row">
                   <div class="form-group col-md">
                        <label for="CodigoProduto" class="form-label">Escolha um produto</label>
                        <select name="CodigoProduto" class="form-select" id="CodigoProduto">
                            <option value="0">Selecione um produto</option>
                            <?php if($produtos): ?>
                                <?php foreach($produtos as $produto): ?>
                                    <option value="<?php echo $produto->CodigoProduto ?>" class=""><?php echo $produto->NomeProduto ." | Preço: R$ ". $produto->PrecoProduto ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small id="small-cliente-text"></small>
                    </div>
                    <div class="form-group col-md">
                        <label for="Quantidade" class="form-label">Quantidade</label>
                        <input type="number" min="1" name="Quantidade" id="Quantidade" class="form-control">
                        <input type="hidden" value="<?php echo $pedido->CodigoPedido ?>" name="CodigoPedido" id="CodigoPedido" class="form-control">
                    </div>
                </div>
                <div class="row mt-4">
                    <label for="ObservacaoPedido" class="form-label">Observação do pedido</label>
                    <textarea name="ObservacaoPedido" class="form-control" id="ObservacaoPedido" cols="3" rows="3"></textarea>
                </div>
            </div>
            <button class="btn btn-primary mt-4" type="submit">Registrar</button>
        </form>
    </div>

    <div class="mt-4">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Nome do produto</th>
                    <th>Quantidade</th>
                    <th>Preço unitário</th>
                    <th>Valor da linha</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="pedidos-tbody">
               <?php if($detalhes):?>
                <?php foreach($detalhes as $detalhe): ?>
                    <tr class="text-center">
                        <td> <?php echo $detalhe->NomeProduto ?> </td>
                        <td> <?php echo $detalhe->Quantidade ?> </td>
                        <td> <?php echo $detalhe->PrecoProduto ?> </td>
                        <td><?php echo number_format($detalhe->PrecoProduto * $detalhe->Quantidade, 2, ",",".")  ?> </td>
                        <td>
                            <form action="<?php echo URL_BASE ?>detalhespedido/delete">
                                <input type="hidden" name="CodigoDetalhe" value="<?php echo $detalhe->CodigoDetalhe ?>">
                                <input type="hidden" name="CodigoPedido" value="<?php echo $pedido->CodigoPedido ?>">
                                <button class="btn btn-danger">Apagar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <form action="<?php echo URL_BASE ?>detalhespedido/finalizar" method="POST">
                            <input type="hidden" name="CodigoPedido" value="<?php echo $pedido->CodigoPedido ?>">
                            <div class="form-group d-flex flex-wrap">
                                <div class="form-group mx-2">
                                    <label for="">Tipo de pagamento</label>
                                    <select class="form-select" name="CodigoTipoPagamento" id="CodigoTipoPagamento">
                                        <option value="-1">Escolher...</option>
                                        <option <?php echo getOld("TipoPagamento") == 1 ? "selected":"" ?> value="1">Dinheiro</option>
                                        <option <?php echo getOld("TipoPagamento") == 2 ? "selected":"" ?> value="2">Crédito</option>
                                        <option <?php echo getOld("TipoPagamento") == 3 ? "selected":"" ?> value="3">Débito</option>
                                        <option <?php echo getOld("TipoPagamento") == 4 ? "selected":"" ?> value="4">PIX</option>
                                    </select>
                                </div>
                                 <div class="form-group mx-2">
                                    <label for="ValorPago">Valor pago</label>
                                    <input value="<?php echo getOld("ValorPago") ?>" type="text" name="ValorPago" class="form-control">
                                </div>
                                <div id="Parcelas" class="form-group">
                                    
                                </div>
                                <div id="ValorParcela" class="form-group">
                                    
                                </div>

                                <div class="form-group mx-2">
                                    <label for="">Troco</label>
                                    <input value="<?php echo getOld("ValorTroco") ?>" readonly type="text" name="ValorTroco" class="form-control">
                                </div>
                                <div class="form-group mx-2">
                                    <label for="">Total do pedido</label>
                                    <input readonly type="text" name="TotalPedido" class="form-control">
                                </div>
                                <input type="hidden" name="Finalizado" value="1">
                                <button class="btn btn-danger m-2 ">FINALIZAR</button>
                            </div>                            
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

</div>
<script>
    const CodigoProdutoDetalhe = document.querySelector("#CodigoProduto")
    const smallClienteText = document.getElementById("small-cliente-text")

    CodigoProdutoDetalhe.addEventListener("change", (e)=>{
        let CodigoProdutoValue = CodigoProdutoDetalhe.value;
        xmlHttpGet("<?php echo URL_BASE ?>AjaxRequisicoes/possuiEstoque", ()=>{
                beforeSend(()=>{
                    smallClienteText.innerText = "aguarde..."
                })
                success(()=>{
                    const response = xhttp.response;
                    const estoque = JSON.parse(response);
                    if(estoque){
                        if(estoque.CodigoResposta == 1){
                            smallClienteText.innerHTML = `
                                ${estoque.message}
                                <a href='<?php echo URL_BASE ?>clientes' class='btn btn-primary'>Novo cliente</a>
                            `
                        }else{
                            if(estoque.CodigoResposta == 1){
                                smallClienteText.innerHTML = `
                                ${estoque.message}
                                <input value="${estoque.CodigoCliente}" type="hidden" name="CodigoCliente" class="form-control">
                                `;
                                
                            }else{
                                smallClienteText.innerText = estoque.message;
                            }
                        }
                    }                
                })
            },"?CodigoProduto="+CodigoProdutoValue)
    })
</script>
<script>

    let TotalPedido = document.querySelector("input[name='TotalPedido']")
    let ValorPago = document.querySelector("input[name='ValorPago']")
    let ValorTroco = document.querySelector("input[name='ValorTroco']")
    let CodigoTipoPagamento = document.querySelector("#CodigoTipoPagamento");
    let Parcelas = document.querySelector("#Parcelas")
    let ValorParcela = document.querySelector("#ValorParcela")
    let valorParcelaInput = ''
    
    function calcTotal(){
        let lastTds = Array.from(document.querySelectorAll("#pedidos-tbody td:nth-child(4)"))
        let newLastTds = []
        
        lastTds.forEach(j =>{
            newLastTds.push(j.innerText.replace(",","."))
        })
        let reduced = newLastTds.reduce((total, i)=>{
            return (Number(total) + Number(i)).toFixed(2);
        })
        return reduced;
    }

    function explode(valorString, searchSymbol, newSymbol = ''){
        return valorString.replace(searchSymbol. newSymbol);
    }

    function formatNumber(valueNumber){
        if(valueNumber.indexOf(",") != -1){
                let newValue = explode(valueNumber, ",", ".");
                return parseFloat(newValue).toFixed(2);
            }else{
                let newValue = parseFloat(valueNumber).toFixed(2)
                return newValue;
            }
    }

    function calcChange(input){

        let valorInput = input.value;
        console.log(valorInput)
        if(valorInput){
            let valueFormatted = formatNumber(valorInput)
            console.log(valueFormatted);
            if((valueFormatted - calcTotal()).toFixed(2) > 0){
                return (valueFormatted - calcTotal()).toFixed(2);
            }else{
                return "0.00";
            }
        }
    }

    ValorPago.addEventListener("blur", ()=>{
        if(CodigoTipoPagamento.value == 1){
            if(ValorPago.value){
                ValorTroco.value =  calcChange(ValorPago);        
            }
        }else{
            ValorTroco.value =  "0.00";
        }
        if(ValorPago.value){
            ValorPago.value = formatNumber(ValorPago.value);
        }
    })

    CodigoTipoPagamento.addEventListener("change", (e)=>{

        if(CodigoTipoPagamento.value == 2){
            ValorTroco.value =  "0.00";  
            const labelParcelas = createElementHTML("label"); 
            const labelValorParcela = createElementHTML("label"); 

            const inputParcelas = createElementHTML("input");
            const inputValorParcela = createElementHTML("input")
            
            labelParcelas.setAttribute("for", "Parcelas");
            labelParcelas.innerText = "Parcelas";

            labelValorParcela.setAttribute("for", "ValorParcela");
            labelValorParcela.innerText = "Valor das parcelas";

            inputParcelas.setAttribute("type", "number");
            inputParcelas.setAttribute("min", "1");
            inputParcelas.setAttribute("value", "1");
            inputParcelas.setAttribute("class", "form-control");
            inputParcelas.setAttribute("name", "Parcelas");
            inputParcelas.setAttribute("id", "ParcelasInput");

            inputValorParcela.setAttribute("type", "text");
            inputValorParcela.setAttribute("readonly", "readonly");
            inputValorParcela.setAttribute("class", "form-control");
            inputValorParcela.setAttribute("name", "ValorParcela");

            Parcelas.appendChild(labelParcelas)
            Parcelas.appendChild(inputParcelas)

            ValorParcela.appendChild(labelValorParcela)
            ValorParcela.appendChild(inputValorParcela)
  
            valorParcelaInput = document.querySelector("input[name='ValorParcela']")

            if(ValorPago.value){
                valorParcelaInput.value = calcValorParcela();
            }else{
                ValorPago.addEventListener("blur", ()=>{
                    valorParcelaInput.value = calcValorParcela();
                })
            }

        }else{
            Parcelas.innerHTML = '';
            ValorParcela.innerHTML = '';    
        }

        if(CodigoTipoPagamento.value == 1){
            if(ValorPago.value){
                ValorTroco.value =  calcChange(ValorPago);        
            }      
        }else{
            ValorTroco.value = '0.00';
        }

    })

    function calcValorParcela(){
        let qtdParcelas = document.getElementById("ParcelasInput");
        let parcelas = 1;
        let valorPagoNumber = 0;
        let resultado = ValorPago.value;

        qtdParcelas.addEventListener("change", (e)=>{
            
            valorPagoNumber = Number(ValorPago.value);
            parcelas = parseInt(qtdParcelas.value);
            console.log(parcelas);
            console.log(valorPagoNumber / parcelas);
            resultado = valorPagoNumber / parcelas;
            valorParcelaInput.value = resultado.toFixed(2);
            
        })   
        return resultado;
    }


    function createElementHTML(element){
        let elementCreated = document.createElement(element);
        return elementCreated;        
    }

    TotalPedido.value = calcTotal()

</script>