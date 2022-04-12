<div class="ui clearing green segment">
	<h3 class="ui left floated header"><?=$this->lang->line('purchase')?></h3>
	<a href="create-purchase" class="ui right floated button green">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>

<table class="ui table" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('purchase_date')?></th>
			<th><?=$this->lang->line('supplier')?></th>
			<th><?=$this->lang->line('voucher_serial')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_cat')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_pamt')?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($vouchers as $voucher):
				$total_item = $this->ignite_model->get_total_pItem($voucher->voucherId);
				$total_amount = $this->ignite_model->get_total_pAmt($voucher->voucherId);
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=date('d-m-Y',strtotime($voucher->vDate))?></td>
			<td><a href="purchase_detail/<?=$voucher->voucherId?>"><?=$this->ignite_model->supplier($voucher->supplier)?></a></td>
			<td><?=$voucher->vSerial?></td>
			<td class="ui right aligned"><?=$total_item?></td>
			<td class="ui right aligned"><strong><?=number_format($total_amount)?></strong></td>
			
		</tr>
		<?php 
			$i ++;
			endforeach;
		?>
	</tbody>
</table>