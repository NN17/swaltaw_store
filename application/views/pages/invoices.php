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
  
  	<div class="ui large icon input">
	  <input type="text" placeholder="Search..." onkeyup="igniteAjax.searchInvoice()" id="invSearch">
	  <i class="search icon"></i>
	</div>

	<table class="ui table">
		<thead>
			<tr>
				<th class="ui right aligned">#</th>
				<th><?=$this->lang->line('date')?></th>
				<th><?=$this->lang->line('inv_serial')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_items')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
				<th><?=$this->lang->line('payment')?></th>
				<th><?=$this->lang->line('status')?></th>
				<th>Referred</th>
				<th>Created By</th>
				<th></th>
			</tr>
		</thead>
		<tbody id="invBody">
			<?php 
				$i = $page+1;
				foreach($invoices as $inv):
					$totalItems = $this->ignite_model->get_dTotalItems($inv->invoiceId);
					$totalAmount = $this->ignite_model->get_dTotalAmount($inv->invoiceId);
			?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=date('d-M-Y',strtotime($inv->created_date))?></td>
				<td><a href="javascript:void(0)" onclick="igniteAjax.detailInv('<?=$inv->invoiceId?>')"><?=$inv->invoiceSerial?></a></td>
				<td class="ui right aligned"><?=$totalItems?></td>
				<td class="ui right aligned"><?=number_format($totalAmount)?></td>
				<td><?=$inv->paymentType?></td>
				<td>
					<?php if($inv->delivered == false && $inv->paymentType == 'COD'): ?>
					<button class="ui tiny button circular orange icon" <?=$this->auth->checkModify($this->session->userdata('Id'), 'invoices/~')?'onclick="igniteAjax.delivered('.$inv->invoiceId.')"':'disabled'?>><i class="ui icon shipping fast"></i></button>
					<?php elseif($inv->pReceived == false && $inv->paymentType == 'MBK'): ?>
						<button class="ui tiny button circular orange icon" <?=$this->auth->checkModify($this->session->userdata('Id'), 'invoices/~')?'onclick="igniteAjax.receivePayment('.$inv->invoiceId.')"':'disabled'?>><i class="ui icon hourglass outline"></i></button>
					<?php else: ?>
					<button class="ui tiny button circular green icon"><i class="ui icon check circle"></i></button>
					<?php endif; ?>
				</td>
				<td><?=$inv->referId > 0?'<button class="ui button icon circular olive tiny" onclick="igniteAjax.detailInv('.$inv->referId.')"><i class="ui icon thumbtack"></i></button>':'-'?></td>
				<td><?=$this->ignite_model->get_username($inv->created_by)?></td>
				<td>
					<a <?=$this->auth->checkModify($this->session->userdata('Id'), 'invoices/~')?'href="refer-invoice/'.$inv->invoiceId.'"':'id="popup" data-content="You don\'t have authorized to refer invoice !"'?> class="ui button icon circular tiny yellow"><i class="ui icon edit outline"></i></a>
					<a href="javascript:void(0)" class="ui tiny button icon circular red" <?=$this->auth->checkModify($this->session->userdata('Id'), 'invoices/~')?'id="delete" data-url="del-invoice/'.$inv->invoiceId.'"':'id="popup" data-content="You don\'t have authorized to delete invoice !"'?> ><i class="ui icon trash alternate outline"></i></a>
				</td>
			</tr>
			<?php 
				$i++;
				endforeach;
			?>
		</tbody>
	</table>

	<div class="ui pagination center aligned">
		<?=$this->pagination->create_links();?>
	</div>
</div>

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
  	<div class="invDetail-footer mid-padding text-center">
  		<button class="ui button large green" id="printReceipt" onclick="igniteAjax.receiptPrint()">Print Receipt</button>
  	</div>
</div>
