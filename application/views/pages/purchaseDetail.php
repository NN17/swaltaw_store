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
			<th class="ui right aligned"><?=$this->lang->line('amount')?></th>
			<th><?=$this->lang->line('warehouse')?></th>
			<th><?=$this->lang->line('status')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			$total = 0;
			foreach($purchaseItem as $item):
				$total += $item['price'] * $item['quantity'];
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$item['purchaseDate']?></td>
			<td><?=$item['codeNumber']?></td>
			<td><?=$item['itemName']?> <?=!empty($item['brnadName'])?' ( '.$item['brandName'].' )':''?></td>
			<td><?=$item['itemModel']?></td>
			<td class="ui right aligned"><?=number_format($item['price'])?></td>
			<td class="ui right aligned"><?=number_format($item['quantity'])?></td>
			<td class="ui right aligned"><strong><?=number_format($item['price'] * $item['quantity'])?></strong></td>
			<td><?=$item['warehouseName']?></td>
			<td>
				<?php if($this->ignite_model->check_purchase($item['purchaseId']) == false): ?>
					<button class="ui tiny button circular icon" onclick="igniteAjax.setPurchaseActive(<?=$item['purchaseId']?>, '<?=$item['itemName']?>')"><i class="icon shopping bag"></i></button>
				<?php else: ?>
					<button class="ui tiny button circular icon green"><i class="icon shopping bag"></i></button>
				<?php endif; ?>
			</td>
			<td>
				<a <?=$this->auth->checkModify($this->session->userdata('Id'), 'purchase')?'href="edit-purchase/'.$item['purchaseId'].'"':'disabled'?> class="ui button circular orange tiny icon <?=$this->ignite_model->check_purchase($item['purchaseId'])?'disabled':''?>"><i class="ui icon cog"></i></a>
				<a href="javascript:void(0)" class="ui button circular red tiny icon <?=$this->ignite_model->check_purchase($item['purchaseId'])?'disabled':''?>" <?=$this->auth->checkModify($this->session->userdata('Id'), 'purchase')?'id="delete" data-url="ignite/delPurchase/'.$item['purchaseId'].'"':'disabled'?> ><i class="ui icon remove"></i></a>
			</td>
		</tr>
		<?php 
			$i ++;
			endforeach;
		?>
		<!-- <tr>
			<td colspan="7" class="ui right aligned">Total Amount</td>
			<td class="ui right aligned"><strong><?=number_format($total)?></strong></td>
			<td colspan="2"></td>
		</tr> -->
	</tbody>
</table>

<div class="ui stacked segment text-center green">
	<strong>TOTAL PURCHASE : <span class="text-red"><?=number_format($total)?></span></strong>
</div>