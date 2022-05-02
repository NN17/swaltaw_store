<div class="ui clearing segment blue">
	<h3 class="ui left floated blue header"><?=$this->lang->line('stocks')?></h3>

	<a href="export-excel" class="ui right floated button olive">
	    <i class="icon file excel outline"></i> Export Excel
	</a>
	<a href="export-pdf/stocks-balance" target="_blank" class="ui right floated button red">
	    <i class="icon file pdf outline"></i> Export PDF
	</a>
</div>

<table class="ui celled table" id="dataTable">
	<thead>
		<tr>
			<th class="ui right aligned">#</th>
			<th><?=$this->lang->line('item_code')?></th>
			<th><?=$this->lang->line('item_name')?></th>
			<th><?=$this->lang->line('item_model')?></th>
			<th><?=$this->lang->line('supplier_name')?></th>
			<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
			<!-- Loop for warehouse -->
			<?php foreach($warehouse as $row): ?>
				<th class="ui right aligned"><?=$row->warehouseName?></th>
			<?php endforeach; ?>
			<!-- End of warehouse loop -->
			<th class="ui right aligned">Total</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; ?>
		<?php foreach($items as $item): ?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=$item->codeNumber?></td>
				<td><?=$item->itemName?></td>
				<td><?=$item->itemModel?></td>
				<td><?=$item->supplierName?></td>
				<td class="ui right aligned"><?=number_format($item->price)?></td>
				<!-- Loop for warehouse -->
				<?php $totalQty = 0; ?>
				<?php foreach($warehouse as $row): ?>
					<?php 
						// $pItems = $this->ignite_model->get_purchase_items_by_warehouse($row->warehouseId, $item->itemId)->row(); 
						// $transItems = $this->ignite_model->get_transfer_items_by_warehouse($row->warehouseId, $item->itemId)->row();
						// // Define purchase quantity is greater than 0
						// if($pItems->qty > 0){
						// 	$pQty = $pItems->qty;
						// }else{
						// 	$pQty = 0;
						// }
						// // Definte transfer qty is greater than 0
						// if($transItems->qty > 0){
						// 	$tQty = $transItems->qty;
						// }else{
						// 	$tQty = 0;
						// }

						// $diff = $pQty-$tQty;
						// $totalQty += $diff;
						$balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $row->warehouseId])->row();
						if(isset($balance->qty)){
							$totalQty += $balance->qty;
						}
					?>
					<td class="ui right aligned"><?=isset($balance->qty)?$balance->qty:0?></td>
				<?php endforeach; ?>
				<!-- End of warehouse loop -->
				<td class="ui right aligned <?=$totalQty<5?'negative':'positive'?>"><strong><?=$totalQty?></strong></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>