<div class="container-fluid mt-4">
<div class="mensagem">
        <?php echo getFlash("success"); ?>
        <?php echo getFlash("fail"); ?>
    </div>
    <div class="form-group mb-4">
        <button onclick="window.location.reload()" id="buscar-pedidos" class="btn btn-primary">Buscar pedidos</button>
        <button id="novoPedidoBtn" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novoPedido">Novo pedido</button>
    </div>
    <div id="tela-pedidos" class="table d-grid flex-wrap">
        
<div class="row">

<?php foreach($pedidos as $pedido): ?>
    <div class="col-md-4">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Número do pedido: #<?php echo $pedido["CodigoPedido"] ?></th>
                    <th></th>
                </tr>
                <tr class="text-center">
                    <th>Sabor da pizza</th>
                    <th>Quantidade</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($pedido["Detalhes"] as $detalhe): ?>
                <tr class="fs-5">
                    <td><?php echo $detalhe->NomeProduto ?></td>
                    <td class="text-center fw-bold"><?php echo $detalhe->Quantidade ?></td>
                    <td class="text-center fw-bold"><?php echo $detalhe->ObservacaoPedido ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align:right">
                        Pedido pronto?
                    </td>
                    <td class="d-flex justify-content-center">
                        <form method="POST" action="home/pronto">
                            <input type="hidden" value="<?php echo $pedido["CodigoPedido"] ?>" name="CodigoPedido">
                            <select class="form-select select-pedido-pronto" name="PedidoPronto" id="">
                                <option value="0">Pendente</option>
                                <option value="1">Pronto</option>
                            </select>
                        </form>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
        <?php endforeach; ?>
</div>
    <script src="<?php echo URL_BASE ?>/assets/js/pizzas.js"></script>
</div>

<!-- Modal -->
<div id="novoPedido" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Novo pedido</h5>
        <button type="button" class="close btn fs-4" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">X</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="novoPedidoForm" action="pedidos/insert" method="POST">
            <div class="form-group">
                    
                <input readonly type="hidden" name="DataPedido" id="DataPedido" class="form-control">
                <input readonly type="hidden" name="HoraPedido" id="HoraPedido" class="form-control">
                <script>
                    let objData = new Date();
                    const DataPedido = document.querySelector("#novoPedidoForm #DataPedido")
                    const HoraPedido = document.querySelector("#novoPedidoForm #HoraPedido")
                    
                    setInterval(() => {
                        objData = new Date();
                        let data = objData.toLocaleDateString();
                        let hora = objData.toLocaleTimeString();
                        HoraPedido.value = hora;
                        DataPedido.value = data;
                    }, 1000);
                </script>
            </div>
            <div class="form-group mt-2 mb-2">
                <label for="CodigoCliente">Digite o telefone do cliente:</label>
                <!-- <select id="CodigoCliente" name="CodigoCliente" class="form-select" id="CodigoCliente">
                    <?php if($clientes): ?>
                        <?php foreach($clientes as $cliente): ?>
                            <option value="<?php echo $cliente->CodigoCliente ?>"><?php echo $cliente->NomeCliente ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select> -->
                <input type="text" name="TelCliente" id="TelCliente" placeholder="Sem parênteses e sem espaços, ex.: 85912341234" class="form-control">
                <small id="small-cliente-text"></small>
            </div>
            <div class="form-group">
                <label for="CodigoFuncionario">Escolha o funcionario:</label>
                <select id="Codigofuncionario" name="CodigoFuncionario" class="form-select" id="CodigoFuncionario">
                    <?php if($funcionarios): ?>
                        <?php foreach($funcionarios as $funcionario): ?>
                            <option value="<?php echo $funcionario->CodigoFuncionario ?>"><?php echo $funcionario->NomeFuncionario ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group mt-2">
                <label for="CodigoTipoPedido">Tipo de pedido:</label>
                <select id="CodigoTipoPedido" name="CodigoTipoPedido" class="form-select" id="CodigoTipoPedido">
                    <?php if($tipoPedidos): ?>
                        <?php foreach($tipoPedidos as $tipoPedido): ?>
                            <option value="<?php echo $tipoPedido->CodigoTipoPedido ?>"><?php echo $tipoPedido->NomeTipoPedido ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Registrar</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo URL_BASE ?>assets/js/xhttp.js"></script>
<script>

    const allFormSelectReady = Array.from(document.getElementsByClassName("select-pedido-pronto"));
    const mensagem = document.querySelector(".mensagem")
    const TelCliente = document.querySelector("#TelCliente");
    const smallClienteText = document.getElementById("small-cliente-text")

    if(TelCliente){
        TelCliente.addEventListener("blur", (e)=>{
            xmlHttpGet("AjaxRequisicoes/findBy", ()=>{
                beforeSend(()=>{
                    smallClienteText.innerText = "aguarde..."
                })
                success(()=>{
                    const response = xhttp.response;
                    const response2 = JSON.parse(response);
                    if(response2){
                        if(response2.CodigoResposta == 2){
                            smallClienteText.innerHTML = `
                                ${response2.message}
                                <a href='<?php echo URL_BASE ?>clientes' class='btn btn-primary'>Novo cliente</a>
                            `
                        }else{
                            if(response2.CodigoResposta == 1){
                                smallClienteText.innerHTML = `
                                ${response2.message}
                                <input value="${response2.CodigoCliente}" type="hidden" name="CodigoCliente" class="form-control">
                                `;
                                
                            }else{
                                smallClienteText.innerText = response2.message;
                            }
                        }
                    }                
                })
            },"?TelCliente="+e.target.value)
        })
    }
    allFormSelectReady.forEach(formSelectReady => {
        formSelectReady.addEventListener("change", (e)=>{
            let form = new FormData(e.target.parentNode);
            xmlHttpPost("home/pronto",()=>{
                beforeSend(()=>{
                    mensagem.innerHTML = `
                    <span class='alert d-flex message justify-content-between align-items-center alert-primary'>
                        <span>Aguarde...</span>
                        <span class='btn-close'></span>
                    </span>
                    `
                })
                success(()=>{
                    let btnClosesfuncionarios = document.getElementsByClassName("btn-close");
                    mensagem.innerHTML = `
                    <span class='alert d-flex message justify-content-between align-items-center alert-success'>
                        <span>A situação do pedido mudou para: PRONTO.</span>
                        <span class='btn-close'></span>
                    </span>
                    `
                    Array.from(btnClosesfuncionarios).forEach(btnClosefuncionario => {
                    btnClosefuncionario.addEventListener("click", (e)=>{
                        e.target.parentNode.remove();
                    })
                    })
                })
                erro(()=>{
                    mensagem.innerHTML = `
                    <span class='alert d-flex message justify-content-between align-items-center alert-danger'>
                        <span>Ocorreu um erro ao tentar mudar a situação do pedido</span>
                        <span class='btn-close'></span>
                    </span>
                    `
                })
            }, form)
        })
    })
</script>



<!-- Modal -->
<div style="max-height:100vh;overflow:hidden" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div style="overflow-y: scroll; max-height:600px" class="modal-content">
      <div class="modal-header w-100">
        <h5 class="modal-title" id="exampleModalLabel">Pedidos prontos</h5>
        <button type="button" class="close btn fs-4" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">X</span>
        </button>
      </div>
      <div class="card-header">
      <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="m-1">
                <span class="text fw-bold fs-5">Pesquisar: </span>
                <input type="text" id="txtBusca" class="form-control w-100" name="txtBusca">
            </div>
            <form id="filtrar-por-data" class="d-flex justify-content-center align-items-end">
                <div class="m-1">
                    <span class="text fw-bold fs-5">Por data</span>
                    <input  class="form-control" type="date" name="DataPedido" id="DataPedido">
                    <input  class="form-control" type="hidden" value="1" name="PedidoPronto" id="PedidoPronto">
                </div>
                <button type="submit" class="btn mb-1 btn-primary">Filtrar</button>
            </form>
        </div>
    </div>
      </div>
      <div id="tbody" class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>

    const pedidosProntosContainer = document.querySelector("#tbody")
    const filtrarPorDataOculto = document.getElementById("filtrar-por-data-oculto")
    const DataPedidoInputOculto = filtrarPorDataOculto.querySelector("input[name='DataPedido']")

    const objDate = new Date();
    DataPedidoInputOculto.value = objDate.toLocaleDateString('pt-br')

    filtrarPorDataOculto.addEventListener("submit", e =>{
        e.preventDefault();
        const formularioDados = new FormData(filtrarPorDataOculto);
      
        xmlHttpPost("<?php echo URL_BASE ?>ajaxrequisicoes/fetchPedidoPronto",()=>{

            beforeSend(()=>{
                pedidosProntosContainer.innerHTML = `
                    <span class='alert d-flex message justify-content-between align-items-center alert-primary'>
                        <span>Aguarde...</span>
                        <span class='btn-close'></span>
                    </span>
                `
            })
            success(()=>{
                const response = xhttp.response;
                const pedidos = JSON.parse(response) ? JSON.parse(response):response;   
                pedidosProntosContainer.innerHTML = '';
                if(pedidos.CodigoResposta == 1){
                pedidos.Pedidos.forEach(pedido => {
                pedidosProntosContainer.innerHTML += `
                    <div class="card linha">
                        <div class="card-body">
                            <p class="fs-5">Nº. pedido: ${pedido.CodigoPedido}</p>
                            <p class="fs-5"> Nome do cliente: ${pedido.NomeCliente} </p>
                            <form class="p-1" action="<?php echo URL_BASE ?>detalhespedido/visualizar" method="GET">
                                <input type="hidden" name="CodigoPedido" value="${pedido.CodigoPedido}">
                                <button type="submit" class="btn btn-primary">Visualizar</button>
                            </form>
                        </div>
                    </div>
                `
                })
            }else{
                pedidosProntosContainer.innerHTML = `
                    <span class='alert d-flex message justify-content-between align-items-center alert-${pedidos.typeMessage}'>
                        <span>${pedidos.message}</span>
                        <span class='btn-close'></span>
                    </span>
                `
            }
            btnClose();
            })
            
        }, formularioDados)
      
    })

</script>

<script>
    const inputTypeDate = document.querySelector("input[type='date']");
    const filtrarPorDataForm = document.querySelector("#filtrar-por-data")
    filtrarPorDataForm.addEventListener("submit", (e)=>{
        e.preventDefault();
        const formularioDados = new FormData(filtrarPorDataForm);
        pedidosProntosContainer.innerHTML = ''
        xmlHttpPost("<?php echo URL_BASE ?>ajaxrequisicoes/fetchPedidoPronto",()=>{
            beforeSend(()=>{
                pedidosProntosContainer.innerHTML = `
                    <span class='alert d-flex message justify-content-between align-items-center alert-primary'>
                        <span>Aguarde...</span>
                        <span class='btn-close'></span>
                    </span> 
                `
            })
            success(()=>{
                const response =xhttp.response;
                const pedidos = JSON.parse(response);
                pedidosProntosContainer.innerHTML = '';
                if(pedidos.CodigoResposta == 1){
                pedidos.Pedidos.forEach(pedido => {
                
                pedidosProntosContainer.innerHTML += `
                    <div class="card linha">
                        <div class="card-body">
                            <p class="fs-5">Nº. pedido: ${pedido.CodigoPedido}</p>
                            <p class="fs-5"> Nome do cliente: ${pedido.NomeCliente} </p>
                            <form class="p-1" action="<?php echo URL_BASE ?>detalhespedido/visualizar" method="GET">
                                <input type="hidden" name="CodigoPedido" value="${pedido.CodigoPedido}">
                                <button type="submit" class="btn btn-primary">Visualizar</button>
                            </form>
                        </div>
                    </div>
                `
                })
            }else{
                pedidosProntosContainer.innerHTML = `
                    <span class='alert d-flex message justify-content-between align-items-center alert-${pedidos.typeMessage}'>
                        <span>${pedidos.message}</span>
                        <span class='btn-close'></span>
                    </span>
                `
            }
            btnClose();
            })
            
        }, formularioDados)
    })
</script>