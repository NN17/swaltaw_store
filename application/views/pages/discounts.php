<div class="ui clearing segment orange">
	<h3 class="ui left floated header orange"><?=$this->lang->line('discounts')?></h3>
	
	<a href="create-discount" class="ui button right floated orange">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>


<table class="ui table orange" id="dataTable">
	<thead>
		<tr>
			<th class="ui right aligned">#</th>
			<th><?=$this->lang->line('title')?></th>
			<th><?=$this->lang->line('discount_type')?></th>
			<th><?=$this->lang->line('discount_rate')?></th>
			<th><?=$this->lang->line('remark')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$i = 1;
			foreach($discounts as $discount): 
		?>
			<tr>
				<td class="ui right aligned"><?=$i?></td>
				<td><?=$discount->discountTitle?></td>
				<td><?=$discount->discountType?></td>
				<td>
					<?php
						if($discount->discountType == 'DP'){
							echo $discount->discountRate.' %';
						}else if($discount->discountType == 'DA'){
							echo $discount->discountRate. 'MMK';
						}else{
							echo '-';
						}
					?>
				</td>
				<td><?=$discount->remark?></td>
				<td>
					<a href="modify-discount/<?=$discount->discountId?>" class="ui button icon circular orange"><i class="icon cog"></i></a>
					<a href="javascript:void(0)" class="ui button icon circular red" id="delete" data-url="delete-discount/<?=$discount->discountId?>"><i class="icon remove"></i></a>
				</td>
			</tr>
		<?php 
			$i++;
			endforeach; 
		?>
	</tbody>
</table>