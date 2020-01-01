<h3><?=$this->lang->line('brand')?></h3>
<div class="ui divider"></div>

<a href="create-brand" class="ui button purple">
    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
</a>

<table class="ui table">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('brand_name')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($brands as $brand):
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$brand['brandName']?></td>
			<td><?=!empty($brand['remark'])?$brand['remark']:'...'?></td>
			<td>
				<a href="edit-brand/<?=$brand['brandId']?>" class="ui button icon tiny circular orange"><i class="ui icon cog"></i></a>
				<a href="javascript:void(0)" class="ui button icon tiny circular red" id="delete" data-url="ignite/deleteBrand/<?=$brand['brandId']?>"><i class="ui icon remove"></i></a>
			</td>
		</tr>
		<?php
			$i ++;
			endforeach;
		?>
	</tbody>
</table>