<div class="ui clearing segment green">
	<h3 class="ui left floated header green"><?=$this->lang->line('return')?></h3>
	
	<a href="create-return" class="ui button right floated green <?=$this->auth->checkModify($this->session->userdata('Id'), 'damages')?'':'disabled'?>">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>

<table class="ui table green" id="dataTable">
	<thead>
		<tr>
			<th class="ui right aligned">#</th>
			<th><?=$this->lang->line('date')?></th>
			<th><?=$this->lang->line('item_name')?></th>
			<th class="ui right aligned"><?=$this->lang->line('quantity')?></th>
			<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
			<th class="ui right aligned"><?=$this->lang->line('sell_price')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($return as $rtn):
				$item = $this->ignite_model->itemDetail($rtn->itemId);
		?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=date('d-m-Y', strtotime($rtn->created_at))?></td>
				<td><?=$item['itemName']?></td>
				<td class="ui right aligned"><?=$rtn->qty?></td>
				<td class="ui right aligned"><?=number_format($item['purchasePrice'])?></td>
				<td class="ui right aligned"><?=number_format($item['sellPrice'])?></td>
				<td><?=$rtn->remark?></td>
				<td>
					<a href="modify-return/<?=$rtn->returnId?>" class="ui button tiny icon circular orange <?=$this->auth->checkModify($this->session->userdata('Id'), 'purchase-return')?'':'disabled'?>"><i class="icon cog"></i></a>
					<a href="javascript:void(0)" class="ui button icon tiny circular red <?=$this->auth->checkModify($this->session->userdata('Id'), 'purchase-return')?'':'disabled'?>" id="delete" data-url="delete-return/<?=$rtn->returnId?>"><i class="icon remove"></i></a>
				</td>
			</tr>
		<?php
			$i++; 
			endforeach; 
		?>
	</tbody>
</table>