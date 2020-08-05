<div class="ui clearing orange segment">
	<h3 class="ui left floated header"><?=$this->lang->line('stock_out')?></h3>
	<a href="create-transfer" class="ui right floated button orange">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui table" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('sale_date')?></th>
			<th><?=$this->lang->line('item_code')?></th>
			<th><?=$this->lang->line('item_name')?></th>
			<th><?=$this->lang->line('warehouse')?></th>
			<th class="ui right aligned"><?=$this->lang->line('quantity')?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($issues as $issue):
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$issue['issueDate']?></td>
			<td><?=$issue['codeNumber']?></td>
			<td><?=$issue['itemName'] .' ( '.$issue['brandName'].' )'?></td>
			<td><?=$issue['warehouseName']?></td>
			<td class="ui right aligned"><?=$issue['qty']?></td>
		</tr>
		<?php
			$i ++;
			endforeach;
		?>
	</tbody>
</table>
