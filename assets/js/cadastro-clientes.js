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
