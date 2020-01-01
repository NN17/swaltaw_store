<h3><?=$this->lang->line('category')?></h3>
<div class="ui divider"></div>

<a href="create-category" class="ui button purple">
    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
</a>

<table class="ui table">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('category_name')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($categories as $cat):
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$cat['categoryName']?></td>
			<td><?=!empty($cat['remark'])?$cat['remark']:'...'?></td>
			<td>
				<a href="edit-category/<?=$cat['categoryId']?>" class="ui button icon tiny circular orange"><i class="ui icon cog"></i></a>
				<a href="javascript:void(0)" class="ui button icon tiny circular red" id="delete" data-url="ignite/deleteCategory/<?=$cat['categoryId']?>"><i class="ui icon remove"></i></a>
			</td>
		</tr>
		<?php
			$i ++;
			endforeach;
		?>
	</tbody>
</table>