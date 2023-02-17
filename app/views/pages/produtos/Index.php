<div class="container mt-4">
    
    <h1 class="title fs-2"><?php echo $title ?></h1>
    <div id="cadastro-mensagem-produto" class="cadastro-mensagem-produto d-flex justify-content-center align-items-center">
    <?php echo getFlash("success"); ?>    
    <?php echo getFlash("fail"); ?>    
</div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <span class="text p-3 fw-bold fs-5">Pesquisar: </span>
            <input type="text" id="txtbusca" class="form-control w-50" name="txtbusca">
        </div>
    </div>
    <table class="table table-dark table-striped">
        <thead>
            <tr class="text-center">
                <th>ID</th>
                <th>Nome do produto</th>
                <th>Preço do produto</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="tbody">
            <?php if($pizzas): ?>
                <?php foreach($pizzas as $pizza): ?>
                    <tr class="text-center linha">
                        <td><?php echo $pizza->CodigoProduto ?></td>
                        <td><?php echo $pizza->NomeProduto ?></td>
                        <td><?php echo $pizza->PrecoProduto ?></td>
                        <td class="d-flex">
                        <form class="p-1" action="<?php echo URL_BASE ?>produtos/editar" method="GET">
                            <input type="text" name="CodigoProduto" value="<?php echo $pizza->CodigoProduto ?>">
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                            <form  class="p-1 form-delete" action="<?php echo URL_BASE ?>listarprodutos/delete" method="GET">
                                <input type="hidden" name="CodigoProduto" value="<?php echo $pizza->CodigoProduto ?>">
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