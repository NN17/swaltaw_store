<div class="ui clearing segment orange">
	<h3 class="ui left floated header orange"><?=$this->lang->line('damages')?></h3>
	
	<a href="create-damage" class="ui button right floated orange <?=$this->auth->checkModify($this->session->userdata('Id'), 'damages')?'':'disabled'?>">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui table orange" id="dataTable">
	<thead>
		<tr>
			<th class="ui right aligned">#</th>
			<th><?=$this->lang->line('date')?></th>
			<th><?=$this->lang->line('item_name')?></th>
			<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
			<th class="ui right aligned"><?=$this->lang->line('sell_price')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($damages as $dmg):
				$item = $this->ignite_model->itemDetail($dmg->related_item_id);
		?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=date('d-m-Y', strtotime($dmg->created_at))?></td>
				<td><?=$item['itemName']?></td>
				<td class="ui right aligned"><?=number_format($item['purchasePrice'])?></td>
				<td class="ui right aligned"><?=number_format($item['sellPrice'])?></td>
				<td><?=$dmg->remark?></td>
				<td>
					<a href="modify-damage/<?=$dmg->damageId?>" class="ui button tiny icon circular orange <?=$this->auth->checkModify($this->session->userdata('Id'), 'damages')?'':'disabled'?>"><i class="icon cog"></i></a>
					<a href="javascript:void(0)" class="ui button icon tiny circular red <?=$this->auth->checkModify($this->session->userdata('Id'), 'damages')?'':'disabled'?>" id="delete" data-url="delete-damage/<?=$dmg->damageId?>"><i class="icon remove"></i></a>
				</td>
			</tr>
		<?php
			$i++; 
			endforeach; 
		?>
	</tbody>
</table>