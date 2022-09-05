<?php 
	$totalInvest = 0;
	foreach($items as $item) {
		$totalQty = 0;
		foreach($warehouse as $row) {
			$balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $row->warehouseId])->row();
			if(isset($balance->qty)){
				$totalQty += $balance->qty;
			}
		}
		$totalInvest += $item->price * $totalQty;
	}

 ?>

<div class="ui clearing segment blue">
	<h3 class="ui left floated blue header"><?=$this->lang->line('stocks')?></h3>
	<div class="ui tag label blue left floated">
		<strong class="red">Total Investment (MMK) : <?=number_format($totalInvest)?></strong>
	</div>
	<a href="export-excel" class="ui right floated button olive">
	    <i class="icon file excel outline"></i> Export Excel
	</a>
	<a href="export-pdf/stocks-balance" target="_blank" class="ui right floated button red">
	    <i class="icon file pdf outline"></i> Export PDF
	</a>
</div>

<div class="ui top attached tabular menu grey">
  	<div class="item active" data-tab="all">All</div>
  	<div class="item" data-tab="non-zero">Non Zero Balance</div>
  	<div class="item" data-tab="zero">Zero Balance</div>
</div>

<div class="ui bottom attached active tab segment" data-tab="all">
	<table class="ui celled table" id="dataTable">
		<thead>
			<tr>
				<th class="ui right aligned">#</th>
				<th><?=$this->lang->line('item_code')?></th>
				<th><?=$this->lang->line('item_name')?></th>
				<th class="text-center"><?=$this->lang->line('image')?></th>
				<th class="ui right aligned"><?=$this->lang->line('sell_price')?></th>
				<th class="ui right aligned"><?=$this->lang->line('wholesale_price')?></th>
				<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
				<!-- Loop for warehouse -->
				<?php foreach($warehouse as $row): ?>
					<th class="ui right aligned"><?=$row->warehouseName?></th>
				<?php endforeach; ?>
				<!-- End of warehouse loop -->
				<th class="ui right aligned">Total</th>
				<th class="ui right aligned">Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=1; ?>
			<?php 
				foreach($items as $item): 
					$retailPrice = $this->ignite_model->get_sellPrice($item->itemId, 'R')->row();
					$wholeSalePrice = $this->ignite_model->get_sellPrice($item->itemId, 'W')->row();
			?>
				<tr>
					<td class="ui right aligned"><?=$i?></td>
					<td><?=$item->codeNumber?></td>
					<td><?=$item->itemName?></td>
					<td class="text-center"><button class="ui basic button tiny icon olive <?=!empty($item->imgPath)?'':'disabled'?>" onclick="viewImg('<?=$item->imgPath?>')"><i class="ui icon eye"></i></button></td>
					<td class="ui right aligned"><?=number_format($retailPrice->price)?></td>
					<td class="ui right aligned"><?=number_format(@$wholeSalePrice->price)?></td>
					<td class="ui right aligned negative"><?=number_format($item->price)?></td>

					<!-- Loop for warehouse -->
					<?php $totalQty = 0; ?>
					<?php foreach($warehouse as $row): ?>
						<?php 
							$balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $row->warehouseId])->row();
							if(isset($balance->qty)){
								$totalQty += $balance->qty;
							}
						?>
						<td class="ui right aligned"><?=isset($balance->qty)?$balance->qty:0?></td>
					<?php endforeach; ?>
					<!-- End of warehouse loop -->
					<td class="ui right aligned <?=$totalQty<5?'negative':'positive'?>"><strong><?=$totalQty?></strong></td>
					<td class="ui right aligned"><?=number_format($totalQty * $item->price)?></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
			
		</tbody>
	</table>
</div>

<div class="ui bottom attached tab segment" data-tab="non-zero">
	<table class="ui celled table" id="dataTable2">
		<thead>
			<tr>
				<th class="ui right aligned">#</th>
				<th><?=$this->lang->line('item_code')?></th>
				<th><?=$this->lang->line('item_name')?></th>
				<th class="text-center"><?=$this->lang->line('image')?></th>
				<th class="ui right aligned"><?=$this->lang->line('sell_price')?></th>
				<th class="ui right aligned"><?=$this->lang->line('wholesale_price')?></th>
				<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
				<!-- Loop for warehouse -->
				<?php foreach($warehouse as $row): ?>
					<th class="ui right aligned"><?=$row->warehouseName?></th>
				<?php endforeach; ?>
				<!-- End of warehouse loop -->
				<th class="ui right aligned">Total</th>
				<th class="ui right aligned">Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=1; ?>
			<?php 
				foreach($items as $item): 
					$retailPrice = $this->ignite_model->get_sellPrice($item->itemId, 'R')->row();
					$wholeSalePrice = $this->ignite_model->get_sellPrice($item->itemId, 'W')->row();
					$checkQty = 0;
					foreach($warehouse as $row){						
						$balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $row->warehouseId])->row();
						if(isset($balance->qty)){
							$checkQty += $balance->qty;
						}						
					}
					if($checkQty > 0):
			?>
				<tr>
					<td class="ui right aligned"><?=$i?></td>
					<td><?=$item->codeNumber?></td>
					<td><?=$item->itemName?></td>
					<td class="text-center"><button class="ui basic button tiny icon olive <?=!empty($item->imgPath)?'':'disabled'?>" onclick="viewImg('<?=$item->imgPath?>')"><i class="ui icon eye"></i></button></td>
					<td class="ui right aligned"><?=number_format($retailPrice->price)?></td>
					<td class="ui right aligned"><?=number_format(@$wholeSalePrice->price)?></td>
					<td class="ui right aligned negative"><?=number_format($item->price)?></td>

					<!-- Loop for warehouse -->
					<?php $totalQty = 0; ?>
					<?php foreach($warehouse as $row): ?>
						<?php 
							$balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $row->warehouseId])->row();
							if(isset($balance->qty)){
								$totalQty += $balance->qty;
							}
						?>
						<td class="ui right aligned"><?=isset($balance->qty)?$balance->qty:0?></td>
					<?php endforeach; ?>
					<!-- End of warehouse loop -->
					<td class="ui right aligned <?=$totalQty<5?'negative':'positive'?>"><strong><?=$totalQty?></strong></td>
					<td class="ui right aligned"><?=number_format($totalQty * $item->price)?></td>
				</tr>
				<?php endif; ?>
				<?php $i++; ?>
			<?php endforeach; ?>
			
		</tbody>
	</table>
</div>

<div class="ui bottom attached tab segment" data-tab="zero">
	<table class="ui celled table" id="dataTable3">
		<thead>
			<tr>
				<th class="ui right aligned">#</th>
				<th><?=$this->lang->line('item_code')?></th>
				<th><?=$this->lang->line('item_name')?></th>
				<th class="text-center"><?=$this->lang->line('image')?></th>
				<th class="ui right aligned"><?=$this->lang->line('sell_price')?></th>
				<th class="ui right aligned"><?=$this->lang->line('wholesale_price')?></th>
				<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
				<!-- Loop for warehouse -->
				<?php foreach($warehouse as $row): ?>
					<th class="ui right aligned"><?=$row->warehouseName?></th>
				<?php endforeach; ?>
				<!-- End of warehouse loop -->
				<th class="ui right aligned">Total</th>
				<th class="ui right aligned">Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=1; ?>
			<?php 
				foreach($items as $item): 
					$retailPrice = $this->ignite_model->get_sellPrice($item->itemId, 'R')->row();
					$wholeSalePrice = $this->ignite_model->get_sellPrice($item->itemId, 'W')->row();
					$checkQty = 0;
					foreach($warehouse as $row){						
						$balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $row->warehouseId])->row();
						if(isset($balance->qty)){
							$checkQty += $balance->qty;
						}						
					}
					if($checkQty == 0):
			?>
				<tr>
					<td class="ui right aligned"><?=$i?></td>
					<td><?=$item->codeNumber?></td>
					<td><?=$item->itemName?></td>
					<td class="text-center"><button class="ui basic button tiny icon olive <?=!empty($item->imgPath)?'':'disabled'?>" onclick="viewImg('<?=$item->imgPath?>')"><i class="ui icon eye"></i></button></td>
					<td class="ui right aligned"><?=number_format($retailPrice->price)?></td>
					<td class="ui right aligned"><?=number_format($wholeSalePrice->price)?></td>
					<td class="ui right aligned negative"><?=number_format($item->price)?></td>

					<!-- Loop for warehouse -->
					<?php $totalQty = 0; ?>
					<?php foreach($warehouse as $row): ?>
						<?php 
							$balance = $this->ignite_model->get_limit_datas('stocks_balance_tbl', ['itemId' => $item->itemId, 'warehouseId' => $row->warehouseId])->row();
							if(isset($balance->qty)){
								$totalQty += $balance->qty;
							}
						?>
						<td class="ui right aligned"><?=isset($balance->qty)?$balance->qty:0?></td>
					<?php endforeach; ?>
					<!-- End of warehouse loop -->
					<td class="ui right aligned <?=$totalQty<5?'negative':'positive'?>"><strong><?=$totalQty?></strong></td>
					<td class="ui right aligned"><?=number_format($totalQty * $item->price)?></td>
				</tr>
				<?php endif; ?>
				<?php $i++; ?>
			<?php endforeach; ?>
			
		</tbody>
	</table>
</div>


<!-- Image Modal -->
<div class="ui tiny modal imgPreview">
	<div class="header">
	    Preview Image
	</div>
	<div class="content centered" id="imgContent">
	    
	</div>
</div>