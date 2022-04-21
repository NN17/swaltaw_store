<div class="ui clearing segment blue">
	<h3 class="ui left floated header blue"><?=$this->lang->line('extra_charges')?></h3>
	
	<a href="create-charges" class="ui button right floated blue <?=$this->auth->checkModify($this->session->userdata('Id'), 'setting')?'':'disabled'?>">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui table blue" id="dataTable">
	<thead>
		<tr>
			<th class="ui right aligned">#</th>
			<th><?=$this->lang->line('date')?></th>
			<th><?=$this->lang->line('title')?></th>
			<th class="ui right aligned"><?=$this->lang->line('charge_amount')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($charges as $row):
		?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=date('d-m-Y', strtotime($row->created_at))?></td>
				<td><?=$row->chargeTitle?></td>
				<td class="ui right aligned"><?=$row->chargeAmount?></td>
				<td><?=$row->remark?></td>
				<td>
					<a href="modify-charge/<?=$row->chargeId?>" class="ui button icon circular orange <?=$this->auth->checkModify($this->session->userdata('Id'), 'setting')?'':'disabled'?>"><i class="icon cog"></i></a>
					<a href="javascript:void(0)" class="ui button icon circular red <?=$this->auth->checkModify($this->session->userdata('Id'), 'setting')?'':'disabled'?>" id="delete" data-url="delete-charge/<?=$row->chargeId?>"><i class="icon remove"></i></a>
				</td>
			</tr>
		<?php 
			$i++;
			endforeach;
		?>
	</tbody>
</table>