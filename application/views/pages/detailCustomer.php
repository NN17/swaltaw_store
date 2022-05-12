<div class="ui clearing segment red">
	<h3 class="ui left floated header red"><?=$customer->customerName.' [ '.$customer->phone1.' ]'?></h3>

	<a href="customers" class="ui button orange right floated"><i class="icon angle left"></i> Back</a>
</div>

<table class="ui table red" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('date')?></th>
			<th><?=$this->lang->line('inv_serial')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_items')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
			<th>Created By</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($invoices as $inv):
				$totalItems = $this->ignite_model->get_dTotalItems($inv->invoiceId);
				$totalAmount = $this->ignite_model->get_dTotalAmount($inv->invoiceId);
		?>
			<tr>
				<td><?=$i?></td>
				<td><?=date('d-M-Y', strtotime($inv->created_date))?></td>
				<td><a href="javascript:void(0)" onclick="igniteAjax.detailInv('<?=$inv->invoiceId?>')"><?=$inv->invoiceSerial?></a></td>
				<td class="ui right aligned"><?=$totalItems?></td>
				<td class="ui right aligned"><?=number_format($totalAmount)?></td>
				<td><?=$this->ignite_model->get_username($inv->created_by)?></td>
			</tr>
		<?php 
			$i++;
			endforeach;
		?>
	</tbody>
</table>

<!-- Invoice Detail Modal -->
<div class="ui large modal" id="invDetail">
	<div class="itemSearch-header">
  		<h3 id="invDetailHead">Invoice Detail</h3>
	</div>
  	<div class="scrolling content">
  			<div id="invDate" class="text-right"></div>
    		<table class="ui table">
    				<thead>
    						<tr>
    							<th>No</th>
    							<th>Description</th>
    							<th class="ui right aligned">Rate</th>
    							<th class="ui right aligned">Qty</th>
    							<th class="ui right aligned">Amount</th>
    						</tr>
    				</thead>
    				<tbody id="invDetailBody">
    					
    				</tbody>
    		</table>
  	</div>
</div>