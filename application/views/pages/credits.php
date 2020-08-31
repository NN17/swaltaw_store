<div class="ui clearing segment violet">
	<h3 class="ui left floated header violet"><?=$this->lang->line('credit')?></h3>
</div>

<table class="ui table" id="dataTable">
	<thead>
		<tr>
			<th class="ui right aligned">#</th>
			<th><?=$this->lang->line('customer')?></th>
			<th>Total Invoice</th>
			<th class="ui right aligned">Credit Balance</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($customers as $customer):
				$credit = $this->ignite_model->get_credit($customer->customerId)->row();
				$invTotal = $this->ignite_model->get_invTotal($customer->customerId);
				// print_r($credit)
		?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=$customer->customerName?></td>
				<td><?=$invTotal?></td>
				<td class="ui right aligned"><?=isset($credit->balance)?number_format($credit->balance):0?></td>
			</tr>
		<?php 
			$i ++;
			endforeach;
		?>
	</tbody>
</table>