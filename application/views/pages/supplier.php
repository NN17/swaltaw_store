<div class="ui clearing segment">
	<h3 class="ui violet left floated header"><?=$this->lang->line('supplier')?></h3>
	<a href="create-supplier" class="ui right floated button purple">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui table padded purple tablet stackable" id="dataTable">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('supplier_name')?></th>
			<th><?=$this->lang->line('email')?></th>
			<th><?=$this->lang->line('contact_person')?></th>
			<th><?=$this->lang->line('contact_phone1')?></th>
			<th><?=$this->lang->line('contact_address1')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($suppliers as $supplier):
		 ?>
		 	<tr>
		 		<td><?=$i?></td>
		 		<td><?=$supplier['supplierName']?></td>
		 		<td><?=$supplier['emailAddress']?></td>
		 		<td><?=$supplier['contactPerson']?></td>
		 		<td><?=$supplier['contactPhone1']?></td>
		 		<td><?=$supplier['contactAddress1']?></td>
		 		<td>
		 			<a href="edit-supplier/<?=$supplier['supplierId']?>" class="ui button icon circular orange"><i class="icon cog"></i></a>
		 			<a href="javascript:void(0)" class="ui button icon circular red" id="delete" data-url="ignite/deleteSupplier/<?=$supplier['supplierId']?>"><i class="icon remove"></i></a>
		 		</td>
		 	</tr>
		 <?php 
		 	$i ++;
		 	endforeach;
		  ?>
	</tbody>
</table>