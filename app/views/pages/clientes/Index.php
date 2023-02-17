<div class="container mt-4">
    <div id="cadastro-mensagem-cliente" class="mensagem">
            <?php echo getFlash("success"); ?>
            <?php echo getFlash("fail"); ?>
    </div>
    <h1 class="title fs-2">Lista de clientes</h1>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="text p-3 fw-bold fs-5">Pesquisar: </span>
            <input type="text" id="txtBusca" class="form-control w-50" name="txtBusca">
        </div>
    </div>
    <table class="table table-dark table-striped">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Nome do cliente</th>
                <th>Telefone do cliente</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tbody">
            <?php if($clientes): ?>
                <?php foreach($clientes as $cliente): ?>
                    <tr class="text-center linha">
                        <td><?php echo $cliente->CodigoCliente ?></td>
                        <td><?php echo $cliente->NomeCliente ?></td>
                        <td><?php echo $cliente->TelCliente ?></td>
                        <td class="d-flex">
                            <form class="p-1" action="clientes/editar" method="POST">
                                <input type="hidden" name="CodigoCliente" value="<?php echo $cliente->CodigoCliente ?>">
                                <button type="submit" class="btn btn-primary">Editar</button>
                            </form>
                            <form class="p-1 form-delete" action="<?php echo URL_BASE ?>listarclientes/delete" method="GET">
                                <input type="hidden" name="CodigoCliente" value="<?php echo $cliente->CodigoCliente ?>">
                                <button type="submit" class="btn btn-danger">Apagar</button>
                            </form>
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