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
			<th><?=$this->lang->line('voucher_serial')?></th>
			<th><?=$this->lang->line('supplier')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_cat')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_pamt')?></th>
			<th></th>
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
			<td><a href="detail-purchase/<?=$voucher->voucherId?>"><?=$voucher->vSerial?></a></td>
			<td><?=$this->ignite_model->supplier($voucher->supplier)?></td>
			<td class="ui right aligned"><?=$total_item?></td>
			<td class="ui right aligned"><strong><?=number_format($total_amount)?></strong></td>
			<td class="ui right aligned">
				<?php if($this->ignite_model->check_active_purchase($voucher->voucherId) == 'empty'): ?>
					<button class="ui tiny button circular icon" onclick="igniteAjax.setAllPurchaseActive(<?=$voucher->voucherId?>)"><i class="icon shopping bag"></i></button>
				<?php elseif($this->ignite_model->check_active_purchase($voucher->voucherId) == 'less'): ?>
					<button class="ui tiny button circular icon yellow" onclick="igniteAjax.setAllPurchaseActive(<?=$voucher->voucherId?>)"><i class="icon shopping bag"></i></button>
				<?php elseif($this->ignite_model->check_active_purchase($voucher->voucherId) == 'passed'): ?>
					<button class="ui tiny button circular icon olive"><i class="icon shopping bag"></i></button>
				<?php endif; ?>
			</td>
			
		</tr>
		<?php 
			$i ++;
			endforeach;
		?>
	</tbody>
</table>