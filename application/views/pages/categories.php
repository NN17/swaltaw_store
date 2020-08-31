<div class="ui clearing segment grey">
	<h3 class="ui grey left floated header"><?=$this->lang->line('category')?></h3>
	<a href="create-category" class="ui right floated button grey">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui table grey" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('category_name')?></th>
			<th><?=$this->lang->line('cat_code')?></th>
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
			<td><?=$cat['letterCode']?></td>
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