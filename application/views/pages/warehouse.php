<div class="ui clearing segment">
	<h3 class="ui violet left floated header"><?=$this->lang->line('warehouse')?></h3>
	<a href="create-warehouse" class="ui button right floated violet">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui padded violet table" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('name')?></th>
			<th><?=$this->lang->line('serial')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th><?=$this->lang->line('status')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($warehouses as $wHouse):
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$wHouse['warehouseName']?> <?=$wHouse['shop']?'<i class="icon teal dolly"></i>':''?></td>
			<td><?=sprintf('%05d', $wHouse['serial'])?></td>
			<td><?=!empty($wHouse['remark'])?$wHouse['remark']:'...'?></td>
			<td><?=$wHouse['activeState']?'<i class="icon check circle outline green"></i> Active':'<i class="icon ban red"></i> Disable'?></td>
			<td>
				<a href="edit-warehouse/<?=$wHouse['warehouseId']?>" class="ui button icon circular orange"><i class="icon cog"></i></a>
				<a href="javascript:void(0)" class="ui button icon circular red" id="delete" data-url="ignite/deleteWarehouse/<?=$wHouse['warehouseId']?>"><i class="icon remove"></i></a>
			</td>
		</tr>
		<?php 
			$i++;
			endforeach;
		 ?>
	</tbody>
</table>