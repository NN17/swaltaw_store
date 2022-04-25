<div class="ui clearing segment violet">
	<h3 class="ui violet left floated header"><?=$this->lang->line('invoices')?></h3>
</div>

<div class="ui secondary pointing menu">
  	<a class="item <?=$pType == '~'?'active':''?>" href="invoices/~">
    	All Invoices
  	</a>
  	<a class="item <?=$pType == 'CSH'?'active':''?>" href="invoices/CSH">
    	Cash
  	</a>
  	<a class="item <?=$pType == 'CRD'?'active':''?>" href="invoices/CRD">
    	Credit
  	</a>
  	<a class="item <?=$pType == 'MBK'?'active':''?>" href="invoices/MBK">
    	mBanking
  	</a>
  	<a class="item <?=$pType == 'COD'?'active':''?>" href="invoices/COD">
    	COD (Cash on Delivery)
  	</a>
</div>
<div class="ui segment">
  
	<table class="ui table" id="dataTable">
		<thead>
			<tr>
				<th class="ui right aligned">#</th>
				<th><?=$this->lang->line('date')?></th>
				<th><?=$this->lang->line('inv_serial')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_items')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
				<th><?=$this->lang->line('status')?></th>
				<th></th>
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
				<td class="ui right aligned"><?=$i?></td>
				<td><?=date('d-M-Y',strtotime($inv->created_date))?></td>
				<td><?=$inv->invoiceSerial?></td>
				<td class="ui right aligned"><?=$totalItems?></td>
				<td class="ui right aligned"><?=number_format($totalAmount)?></td>
				<td><?=$inv->paymentType?></td>
				<td></td>
			</tr>
			<?php 
				$i++;
				endforeach;
			?>
		</tbody>
	</table>
</div>
