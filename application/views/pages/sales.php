<h3><?=$this->lang->line('sales')?></h3>
<div class="ui divider"></div>

<a href="create-sales" class="ui button purple">
    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
</a>

<table class="ui table">
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
