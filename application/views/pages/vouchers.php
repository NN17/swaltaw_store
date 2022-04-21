<div class="ui clearing segment purple">
	<h3 class="ui left floated header purple"><?=$this->lang->line('voucher')?></h3>
	
	<a href="create-voucher" class="ui button right floated purple <?=$this->auth->checkModify($this->session->userdata('Id'), 'vouchers')?'':'disabled'?>">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui table purple" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('voucher_date')?></th>
			<th><?=$this->lang->line('voucher_serial')?></th>
			<th><?=$this->lang->line('supplier')?></th>
			<th class="ui right aligned"><?=$this->lang->line('extra_charges')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($vouchers as $voucher): 
		?>
			<tr>
				<td><?=$i?></td>
				<td><?=date('d-m-Y',strtotime($voucher->vDate))?></td>
				<td><?=$voucher->vSerial?></td>
				<td><?=$this->ignite_model->supplier($voucher->supplier)?></td>
				<td class="ui right aligned"><?=number_format($voucher->chargeAmt)?></td>
				<td><?=$voucher->remark?></td>
				<td>
					<a href="modify-vouchers/<?=$voucher->voucherId?>" class="ui button icon tiny circular orange <?=$this->auth->checkModify($this->session->userdata('Id'), 'vouchers')?'':'disabled'?>"><i class="ui icon cog"></i></a>
					<a href="javascript:void(0)" class="ui button icon tiny circular red <?=$this->auth->checkModify($this->session->userdata('Id'), 'vouchers')?'':'disabled'?>" id="delete" data-url="remove-vouchers/<?=$voucher->voucherId?>"><i class="ui icon remove"></i></a>
				</td>
			</tr>
		<?php 
			$i++;
			endforeach; 
		?>
	</tbody>
</table>