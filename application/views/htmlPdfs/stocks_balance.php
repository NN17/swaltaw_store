<table class="table table-striped table-hover myanmar">
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