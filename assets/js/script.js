if(window.SimpleAnime){
    new SimpleAnime();
}

const btnCloses = Array.from(document.getElementsByClassName("btn-close"))
const novoPedidoBtn = document.querySelector("#novoPedidoBtn");
const novoPedidoForm = document.getElementById("novoPedidoForm")
const CodigoTipoPedido = document.getElementById("CodigoTipoPedido")



btnCloses.forEach(btnClose => {
    btnClose.addEventListener("click", e=>{
        e.target.parentNode.remove();
    })
})


function createNewFormCostumer(cliente = ''){
    return  `
    <div class="form-group d-flex">
    <div class='m-1'>
    <label for="CodigoCliente">Código do cliente</label>
    <input readonly id="CodigoCliente" value="${cliente.CodigoCliente ? cliente.CodigoCliente:''}" name='CodigoCliente' class="form-control">
    </div>

    <div class='m-1'>
    <label for="NomeCliente">Nome do cliente</label>
    <input id="NomeCliente" value="${cliente.NomeCliente ? cliente.NomeCliente:''}" name='NomeCliente' class="form-control">
    </div>
</div>
<div class="form-group d-flex">
    
</div>
<div class="form-group d-flex flex-wrap">
    <div class='m-1'>
        <label for="Rua">Rua</label>
        <input id="Rua" value="${cliente.Rua ? cliente.Rua:''}" name='Rua' class="form-control">
    </div>
    <div class='m-1'>
        <label for="Numero">Numero</label>
        <input id="Numero"  value="${cliente.Numero ? cliente.Numero:''}" name='Numero' class="form-control">
    </div>
    <div class='m-1'>
        <label for="Bairro">Bairro</label>
        <input id="Bairro" value="${cliente.Bairro ? cliente.Bairro:''}" name='Bairro' class="form-control">
    </div>
    <div class='m-1'>
        <label for="Complemento">Complemento</label>
        <input id="Complemento" value="${cliente.Complemento ? cliente.Complemento:''}" name='Complemento' class="form-control">
    </div>
</div>
    `
}

