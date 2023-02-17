
<div class="container mt-4">
    <div id="cadastro-mensagem-cliente" class="mensagem">
        <?php echo getFlash("success"); ?>
        <?php echo getFlash("fail"); ?>
    </div>
    <h1>Cadastro de clientes</h1>

    <div class="card mt-3">
        <form id="form-cadastro-clientes" action="<?php echo URL_BASE ?>clientes/insert" method="POST">
            <div class="form-group d-grid">
                <h2 class="text fs-5">Informações pessoais</h2>
                <div class="row">
                    <div class="form-group col-md">
                        <label for="" class="form-label">Nome do cliente</label>
                        <input type="text" name="NomeCliente" class="form-control">
                        <?php echo getFlash("NomeCliente"); ?>
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Telefone do cliente</label>
                        <input type="tel" name="TelCliente" class="form-control">
                        <?php echo getFlash("TelCliente"); ?>
                    </div>
                    <script>
                        const allowedKeys = [0,1,2,3,4,5,6,7,8,9];
                        const TelCliente = document.querySelector("input[name='TelCliente']")
                        TelCliente.addEventListener("keydown", (e)=>{
                            let value = TelCliente.value.replace(/[^0-9.]/g, '')
                            TelCliente.value = value                          
                        })
                        
                    </script>
                </div>
            </div>
            <div class="form-group d-grid mt-4">
                <h2 class="text fs-5">Endereço do cliente</h2>
                <div class="row">
                    <div class="form-group col-md">
                        <label for="" class="form-label">Rua</label>
                        <input type="text" name="Rua" class="form-control">
                        <?php echo getFlash("Rua"); ?>
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Número</label>
                        <input type="text" name="Numero" class="form-control">
                        <?php echo getFlash("Numero"); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md">
                        <label for="" class="form-label">Bairro</label>
                        <input type="text" name="Bairro" class="form-control">
                        <?php echo getFlash("Bairro"); ?>
                    </div>
                    <div class="form-group col-md">
                        <label for="" class="form-label">Complemento</label>
                        <input type="text" name="Complemento" class="form-control">
                        <?php echo getFlash("Complemento"); ?>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary mt-4" type="submit">Registrar</button>
                
        </form>
        <?php $old = getOld("CodigoCliente") ?>
                <form method="POST" action="<?php echo URL_BASE ?>pedidos/insert" id="novoPedidoParaCliente">
                
                <input type="hidden" value="<?php echo $old ?>" name="CodigoCliente" id="CodigoCliente">
                <input readonly type="hidden" name="DataPedido" id="DataPedido" class="form-control">
                <input readonly type="hidden" name="HoraPedido" id="HoraPedido" class="form-control">
                <input readonly type="hidden" name="CodigoFuncionario" id="CodigoFuncionario" value="<?php echo $_SESSION[SESSION_LOGIN]['CodigoFuncionario'] ?>" class="form-control">
                <script>
                    let objData = new Date();
                    const DataPedido = document.querySelector("#DataPedido")
                    const HoraPedido = document.querySelector("#HoraPedido")
                    
                    setInterval(() => {
                        objData = new Date();
                        let data = objData.toLocaleDateString();
                        let hora = objData.toLocaleTimeString();
                        HoraPedido.value = hora;
                        DataPedido.value = data;
                    }, 1000);
                </script>
                    <?php if($old): ?>
                    <div class="form-group mt-4">

                        <label for="CodigoTipoPedido">Tipo de pedido:</label>
                        <select id="CodigoTipoPedido" name="CodigoTipoPedido" class="form-select" id="CodigoTipoPedido">
                            <?php if($tipoPedidos): ?>
                                <?php foreach($tipoPedidos as $tipoPedido): ?>
                                    <option value="<?php echo $tipoPedido->CodigoTipoPedido ?>"><?php echo $tipoPedido->NomeTipoPedido ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                    </div>
                                <button class="btn btn-primary mt-4" type="submit">Novo pedido para o cliente: <?php echo $old ?></button>
                    <?php endif; ?>
            </form>
    </div>

</div>

<script>
    const formCadastroClientes = document.querySelector("#form-cadastro-clientes") ? document.querySelector("#form-cadastro-clientes"):"";
const cadastroMensagemCliente = document.querySelector("#cadastro-mensagem-cliente") ? document.querySelector("#cadastro-mensagem-cliente"):"";

if(formCadastroClientes){
    formCadastroClientes.addEventListener("submit", (e)=>{
        e.preventDefault();
        let dataCliente = new FormData(formCadastroClientes);
        xmlHttpPost("clientes/insert", ()=>{
            beforeSend(()=>{
                cadastroMensagemCliente.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-primary"> 
                        Aguarde..
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `
            })
            success(()=>{
                let response = JSON.parse(xhttp.response);
   
                let btnClosesClientes = document.getElementsByClassName("btn-close");

                    cadastroMensagemCliente.innerHTML = `
                    <span class="alert d-flex justify-content-between align-items-center alert-${response.typeMessage}"> 
                        ${response.message}
                        <span style="cursor:pointer" class="btn-close"></span>
                    </span>
                `                  
                
                Array.from(btnClosesClientes).forEach(btnCloseCliente => {
                    btnCloseCliente.addEventListener("click", (e)=>{
                        e.target.parentNode.remove();
                    })
                })
                let CodigoCliente = document.getElementById("CodigoCliente");
                if(response.CodigoResposta == 1){
                    let novoPedidoParaCliente = document.getElementById("novoPedidoParaCliente");
                    console.log(response.CodigoCliente)
                    CodigoCliente.value = response.CodigoCliente;
                    let confirm = window.confirm("Deseja fazer um novo pedido para este cliente?");
                    if(confirm){
                        let dadosPedido = new FormData(novoPedidoParaCliente);
                        xmlHttpPost("pedidos/insert2", ()=>{
                            beforeSend(()=>{
                                cadastroMensagemCliente.innerHTML = `
                                    <span class="alert d-flex justify-content-between align-items-center alert-primary"> 
                                        Aguarde..
                                        <span style="cursor:pointer" class="btn-close"></span>
                                    </span>
                                `
                            })
                            success(()=>{
                                let response = JSON.parse(xhttp.response);
                                cadastroMensagemCliente.innerHTML = `
                                    <span class="alert d-flex justify-content-between align-items-center alert-${response.typeMessage}"> 
                                        ${response.message}
                                        <span style="cursor:pointer" class="btn-close"></span>
                                    </span>
                                `            

                                setTimeout(() => {
                                    window.location.href = `
                                        http://localhost/pizzaria/detalhespedido/?CodigoPedido=${response.CodigoPedido}
                                    `
                                }, 3000); 
                            })
                        }, dadosPedido)
                    }else{

                    }
                }
            })
        }, dataCliente)

    })
}

</script>