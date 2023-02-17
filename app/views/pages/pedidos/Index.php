<div class="container mt-4">
    <div id="cadastro-mensagem-cliente" class="mensagem">
            <?php echo getFlash("success"); ?>
            <?php echo getFlash("fail"); ?>
    </div>
    <h1 class="title fs-2">Lista de pedidos</h1>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="text p-3 fw-bold fs-5">Pesquisar: </span>
            <input type="text" id="txtBusca" class="form-control w-50" name="txtbusca">
        </div>
    </div>
    <table class="table table-dark table-striped">
        <thead>
            <tr class="text-center">
                <th>Número do pedido</th>
                <th>Data do pedido</th>
                <th>Hora do pedido</th>
                <th>Finalizado</th>
                <th>Pronto</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tbody">
            <?php if($pedidos): ?>
                <?php foreach($pedidos as $Pedido): ?>
                    <tr class="text-center linha">
                        <td><?php echo $Pedido->CodigoPedido ?></td>
                        <td><?php echo $Pedido->DataPedido ?></td>
                        <td><?php echo $Pedido->HoraPedido ?></td>
                        <td><?php echo $Pedido->Finalizado ? "Sim":"Não" ?></td>
                        <td><?php echo $Pedido->PedidoPronto ? "Sim":"Não"  ?></td>
                        <td class="d-flex">
                            <form class="p-1" action="<?php echo URL_BASE ?>detalhespedido/<?php echo $Pedido->Finalizado == 1 ? 'visualizar':''?>" method="GET">
                                <input type="hidden" name="CodigoPedido" value="<?php echo $Pedido->CodigoPedido ?>">
                                <button type="submit" class="btn btn-primary"><?php echo $Pedido->Finalizado == 1 ? 'Visualizar':'Editar'?></button>
                            </form>
                            <form class="p-1 form-delete" action="<?php echo URL_BASE ?>listarpedidos/delete" method="GET">
                                <input type="hidden" name="CodigoPedido" value="<?php echo $Pedido->CodigoPedido ?>">
                                <button type="submit" class="btn btn-danger">Apagar</button>
                            </form>
                            <a class="btn m-1 btn-success d-flex justify-content-center align-items-center" href="<?php echo URL_BASE?>nota?CodigoPedido=<?php echo $Pedido->CodigoPedido ?>">Nota</a>
                        </td>
                                                  
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
    const formDelete = Array.from(document.querySelectorAll(".form-delete"))
    formDelete.forEach(form => {
        form.addEventListener("submit", (e)=>{
            let confirm = window.confirm("Deseja realmente apagar este registro?");
            if(!confirm){
                e.preventDefault();
            }
        })
    })
</script>