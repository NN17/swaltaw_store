<div class="ui clearing segment red">
	<h3 class="ui left floated header red"><?=$this->lang->line('customer')?></h3>
	<a href="new-customer" class="ui button right floated red"><i class="ui icon plus circle"></i> New</a>
</div>

<table class="ui table red" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('name')?></th>
			<th><?=$this->lang->line('email')?></th>
			<th><?=$this->lang->line('contact_phone1')?></th>
			<th><?=$this->lang->line('contact_address1')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th>..</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$i = 1;
			foreach($customers as $customer):
		?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=$customer->customerName?></td>
				<td><?=$customer->email?></td>
				<td><?=$customer->phone1?></td>
				<td><?=$customer->address1?></td>
				<td><?=$customer->remark?></td>
				<td>
					<a href="edit-customer/<?=$customer->customerId?>" class="ui button icon tiny circular orange"><i class="ui icon cog"></i></a>
					<a href="javascript:void(0)" class="ui button icon tiny circular red" id="delete" data-url="ignite/deleteCustomer/<?=$customer->customerId?>"><i class="ui icon remove"></i></a>
				</td>
			</tr>
		<?php
			$i++;
			endforeach;
		?>
	</tbody>
</table>