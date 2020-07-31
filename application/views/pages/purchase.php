<div class="ui clearing green segment">
	<h3 class="ui left floated header"><?=$this->lang->line('purchase')?></h3>
	<a href="create-purchase" class="ui right floated button green">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>

<table class="ui table" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('purchase_date')?></th>
			<th><?=$this->lang->line('item_code')?></th>
			<th><?=$this->lang->line('item_name')?></th>
			<th><?=$this->lang->line('item_model')?></th>
			<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
			<th class="ui right aligned"><?=$this->lang->line('quantity')?></th>
			<th><?=$this->lang->line('warehouse')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($purchaseItem as $item):
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$item['purchaseDate']?></td>
			<td><?=$item['codeNumber']?></td>
			<td><?=$item['itemName']?> ( <?=$item['brandName']?> )</td>
			<td><?=$item['itemModel']?></td>
			<td class="ui right aligned"><?=number_format($item['purchasePrice'])?></td>
			<td class="ui right aligned"><?=number_format($item['quantity'])?></td>
			<td><?=$item['warehouseName']?></td>
			<td>
				<a href="edit-purchase/<?=$item['purchaseId']?>" class="ui button circular orange tiny icon"><i class="ui icon cog"></i></a>
				<a href="javascript:void(0)" class="ui button circular red tiny icon" id="delete" data-url="ignite/delPurchase/<?=$item['purchaseId']?>"><i class="ui icon remove"></i></a>
			</td>
		</tr>
		<?php 
			$i ++;
			endforeach;
		?>
	</tbody>
</table>