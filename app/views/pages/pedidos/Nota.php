<style>
   
.text-center {
  text-align: center;
}
.ttu {
  text-transform: uppercase;
}

.printer-ticket {

	padding:10px;
	margin-left: auto;
    margin-right: auto;
	background: #ffffe0;
  display: table !important;
  width: 100%;
  max-width: 400px;
  font-weight: light;
  line-height: 1.3em;
}
.printer-ticket,
.printer-ticket * {
  font-family: Tahoma, Geneva, sans-serif;
  font-size: 10px;
}
.printer-ticket th:nth-child(2),
.printer-ticket td:nth-child(2) {
  width: 50px;
}
.printer-ticket th:nth-child(3),
.printer-ticket td:nth-child(3) {
  width: 90px;
  text-align: right;
}
.printer-ticket th {
  font-weight: inherit;
  padding: 10px 0;
  text-align: center;
  border-bottom: 1px dashed #BCBCBC;
}
.printer-ticket tbody tr:last-child td {
  padding-bottom: 10px;
}
.printer-ticket tfoot .sup td {
  padding: 10px 0;
  border-top: 1px dashed #BCBCBC;
}
.printer-ticket tfoot .sup.p--0 td {
  padding-bottom: 0;
}
.printer-ticket .title {
  font-size: 1.5em;
  padding: 15px 0;
}
.printer-ticket .top td {
  padding-top: 10px;
}
.printer-ticket .last td {
  padding-bottom: 10px;
}
@media print{
	#navbar{
		display: none;
	}
}
</style>
<div style="margin-top:20px">
<table class="printer-ticket">
 	<thead>
		<tr>
			<th class="title" colspan="3"><?php echo $config->Nome ?></th>
		</tr>
		<tr>
			<th colspan="3"><?php echo $pedido->DataPedido ?> - <?php echo $pedido->HoraPedido ?></th>
		</tr>
		<tr>
			<th colspan="3">
				<?php echo $cliente->NomeCliente ?> <br />
				<?php echo $cliente->TelCliente ? $cliente->TelCliente: "" ?>
			</th>
		</tr>
		<tr>
			<th class="ttu" colspan="3">
				<b>Cupom não fiscal</b>
			</th>
		</tr>
	</thead>
	<tbody>
        <?php $subTotal = 0; $total = 0;  foreach($produtos as $produto): $subTotal = $produto["PrecoProduto"] * $produto["Quantidade"]; ?>
		<tr class="top">
			<td colspan="3">Produto: <?php echo $produto["NomeProduto"] ?></td>
		</tr>
		<tr>
			<td>Preço Unit.:R$ <?php echo number_format($produto["PrecoProduto"], 2, ",", ".") ?></td>
			<td>Qtd.:<?php echo $produto["Quantidade"] ?></td>
			<td>Total: R$ <?php echo number_format($subTotal, 2, ",", ".") ?></td>
		</tr>
        <?php 
            $total += $subTotal;
        endforeach; ?>
	</tbody>
	<tfoot>
		<tr class="sup ttu p--0">
			<td colspan="3">
				<b>Totais</b>
			</td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Sub-total</td>
			<td align="right">R$ <?php echo number_format($total, 2, ",", ".") ?></td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Taxa de serviço</td>
			<td align="right">R$ <?php echo isset($pedido->TaxaServico) ? $pedido->TaxaServico: "0,00" ?></td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Desconto</td>
			<td align="right"><?php echo isset($pedido->Desconto) ? $pedido->Desconto: "0%" ?></td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Total</td>
			<td align="right"> R$ 
            <?php
                $taxaServico =  isset($pedido->TaxaServico) ? $pedido->TaxaServico:0;
                $desconto = isset($pedido->Desconto) ? $pedido->Desconto:0;
                $totalPedido = ($total + $taxaServico) - (($total + $taxaServico) * $desconto);
                echo number_format($totalPedido, 2, ",",".");
            ?>
            </td>
		</tr>
		<tr class="sup ttu p--0">
			<td colspan="3">
				<b>Pagamentos</b>
			</td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Voucher</td>
			<td align="right">R$0,00</td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Tipo de pagamento: <?php echo $pedido->CodigoTipoPagamento ?></td>
			<td align="right"><?php if($pedido->CodigoTipoPagamento == 1): echo "Dinheiro"; endif; ?></td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Total pago</td>
			<td align="right">R$ <?php echo number_format($pedido->ValorPago, 2, ",",".") ?></td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Troco</td>
			<td align="right">R$ <?php echo number_format($pedido->ValorTroco, 2, ",",".") ?></td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Parcelas</td>
			<td align="right"><?php echo $pedido->Parcelas ? $pedido->Parcelas:0 ?></td>
		</tr>
		<tr class="ttu">
			<td colspan="2">Valor da parcela</td>
			<td align="right">R$ <?php echo number_format($pedido->ValorParcela, 2, ",",".") ?></td>
		</tr>
		<tr class="sup">
			<td colspan="3" align="center">
				<b>Pedido: #<?php echo $pedido->CodigoPedido ?></b>
			</td>
		</tr>
		<tr class="sup">
			<td colspan="3" align="center">
				www.site.com
			</td>
		</tr>
	</tfoot>
</table>
		<button id="button-print" type="button" class="btn btn-primary">Imprimir</button>
</div>

	<script>
		const buttonPrint = document.querySelector("#button-print")

		buttonPrint.addEventListener("click", (e)=>{
			e.preventDefault();
			window.print()
		})
		window.addEventListener("load", (e)=>{
			buttonPrint.click();
		})
	</script>