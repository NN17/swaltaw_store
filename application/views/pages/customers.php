<div class="ui clearing segment red">
	<h3 class="ui left floated header red"><?=$this->lang->line('customer')?></h3>
	<a href="new-customer" class="ui button right floated red <?=$this->auth->checkModify($this->session->userdata('Id'), 'customers')?'':'disabled'?>"><i class="ui icon plus circle"></i> New</a>
</div>

<table class="ui table red" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('name')?></th>
			<th><?=$this->lang->line('email')?></th>
			<th><?=$this->lang->line('contact_phone1')?></th>
			<th><?=$this->lang->line('contact_address1')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_inv')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
			<th class="ui right aligned"><?=$this->lang->line('total_pay')?></th>
			<th class="ui right aligned"><?=$this->lang->line('credit_bal')?></th>
			<th class="ui right aligned"><?=$this->lang->line('customer_point')?></th>
			<th>..</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($customers as $customer):
				$invTotal = $this->ignite_model->get_invTotalbyCustomer($customer->customerId);
				$totalAmt = $this->ignite_model->get_amtTotalbyCustomer($customer->customerId);
				$crdBalance = $this->ignite_model->get_creditBalance($customer->customerId);
				$totalPay = $this->ignite_model->get_totalPayment($customer->customerId);
		?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=anchor('customer-detail/'.$customer->customerId,$customer->customerName)?></td>
				<td><?=$customer->email?></td>
				<td><?=$customer->phone1?></td>
				<td><?=$customer->address1?></td>
				<td class="ui right aligned"><?=$invTotal?></td>
				<td class="ui right aligned"><?=number_format($totalAmt)?></td>
				<td class="ui right aligned"><?=number_format($totalPay)?></td>
				<td class="ui right aligned"><?=number_format($totalAmt - $totalPay)?></td>
				<td></td>
				<td>
					<a href="javascript:void(0)" class="ui button icon tiny circular olive <?=$this->auth->checkModify($this->session->userdata('Id'), 'customers')?'':'disabled'?>" onclick="igniteAjax.payModal(<?=$customer->customerId?>)"><i class="ui icon handshake outline"></i></a>
					<a href="edit-customer/<?=$customer->customerId?>" class="ui button icon tiny circular orange <?=$this->auth->checkModify($this->session->userdata('Id'), 'customers')?'':'disabled'?>"><i class="ui icon cog"></i></a>
					<a href="javascript:void(0)" class="ui button icon tiny circular red <?=$this->auth->checkModify($this->session->userdata('Id'), 'customers')?'':'disabled'?>" id="delete" data-url="ignite/deleteCustomer/<?=$customer->customerId?>"><i class="ui icon remove"></i></a>
				</td>
			</tr>
		<?php
			$i++;
			endforeach;
		?>
	</tbody>
</table>


<!-- Payment Modal -->
<div class="ui mini modal" id="payCrd">
	<div class="header">
	    <?=$this->lang->line('payment')?>
	</div>
	<div class="content ui form">

		    <div class="field">
		    	<label><?=$this->lang->line('pay_amount')?></label>
		    	<?=form_number('payAmt','','placeholder="'.$this->lang->line('pay_amount').'" required id="payAmt"')?>
		    </div>

		    <div class="field">
		    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
		    	<?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'" id="payRemark"')?>
		    </div>
	</div>
	<div class="actions">
		<button class="ui button cancel" >Cancel</button>
	    <button class="ui button olive" id="paymentBtn">Save</button>
	</div>
</div>