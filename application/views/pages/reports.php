<div class="ui clearing segment teal">
	<h3 class="ui left floated header teal"><?=$this->lang->line('reports')?></h3>
</div>

<div class="ui top attached tabular menu grey">
  <div class="<?=$tab == 'daily'?'active':''?> item" data-tab="daily">Daily</div>
  <div class="<?=$tab == 'monthly'?'active':''?> item" data-tab="monthly">Monthly</div>
  <div class="<?=$tab == 'yearly'?'active':''?> item" data-tab="yearly">Yearly</div>
</div>

<!-- Daily Tab -->
<div class="ui bottom attached <?=$tab == 'daily'?'active':''?> tab segment" data-tab="daily">
	<?php 
		$dailyTotal = 0;
		$dailyItems = 0;
		foreach($dailyData as $dRow){
			$dTotalItems = $this->ignite_model->get_dTotalItems($dRow->invoiceId);
			$dTotalAmount = $this->ignite_model->get_dTotalAmount($dRow->invoiceId);
			$dailyItems += $dTotalItems;
			$dailyTotal += $dTotalAmount;			
		}
	?>
	<!-- Chart for Daily records -->
	<canvas id="dailyChart" style="width:70vw; height: 35vh"></canvas>

	<div class="ui divider"></div>
	<div class="filter ui form">
		<?=form_open('reports/daily', 'id="dailyFilter"')?>
		<div class="ui grid">
			<div class="three wide column">
				<div class="ui icon input">
				  	<input type="text" name="dailyDate" placeholder="Date" value="<?=$dDate?>" id="datepicker2" onchange="orderAjax.submitForm('dailyFilter')">
				    <i class="ui icon calendar alternate outline"></i>
				</div>
			</div>
			<div class="thirteen wide column text-right">
				<div class="ui tag label blue">
					<strong class="red">Total item : <?=$dailyItems?></strong>
				</div>
				<div class="ui tag label green">
					<strong class="red">Total Sell (MMK) : <?=number_format($dailyTotal)?></strong>
				</div>
			</div>
		</div>
		<?=form_close()?>
	</div>
	<div class="ui divider"></div>
	<table class="ui table" id="dataTable">
		<thead>
			<tr>
				<th class="ui right aligned">#</th>
				<th><?=$this->lang->line('date')?></th>
				<th><?=$this->lang->line('inv_serial')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_items')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
				<th class="ui right aligned"><?=$this->lang->line('gross_profit')?></th>
				<th class="ui center aligned"><?=$this->lang->line('margin')?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 1; 
				foreach($dailyData as $dRow):
					$dTotalItems = $this->ignite_model->get_dTotalItems($dRow->invoiceId);
					$dTotalAmount = $this->ignite_model->get_dTotalAmount($dRow->invoiceId);
					$dNetProfit = $this->ignite_model->get_dNetProfit($dRow->invoiceId);
			?>
				<tr>
					<td class="ui right aligned"><?=$i?></td>
					<td><?=date('d M Y',strtotime($dRow->created_date))?></td>
					<td><a href="javascript:void(0)" onclick="igniteAjax.detailInv('<?=$dRow->invoiceId?>')"><?=$dRow->invoiceSerial?></a></td>
					<td class="ui right aligned"><?=number_format($dTotalItems)?></td>
					<td class="ui right aligned"><?=number_format($dTotalAmount)?></td>
					<td class="ui right aligned"><?=number_format($dNetProfit->sTotal - $dNetProfit->pTotal)?></td>
					<td class="ui center aligned"><?=round((($dNetProfit->sTotal - $dNetProfit->pTotal)/$dNetProfit->pTotal) * 100, 2)?> %</td>
				</tr>
			<?php
				$i ++; 
				endforeach; 
			?>

		</tbody>
	</table>
</div>

<!-- Monthly Tab -->
<div class="ui bottom attached <?=$tab == 'monthly'?'active':''?> tab segment" data-tab="monthly">
	<!-- Chart Canvas -->
	<canvas id="mChart" style="width:70vw; height: 35vh"></canvas>

	<div class="ui divider"></div>

	<div class="search">
		<form class="ui form" method="POST" action="reports/monthly" id="monthlyFilter">
		<div class="ui six column grid">
			<div class="column">
				<select name="mMonth" class="ui selection dropdown" onchange="orderAjax.submitForm('monthlyFilter')">
					<option value="">Select Month</option>
					<?php for($i=1; $i<=12; $i++): ?>
						<option value="<?=$i?>" <?=$mMonth==$i?'selected':''?>><?=$this->ignite_model->getMonth($i)?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="column">
				<select name="mYear" class="ui selection dropdown" onchange="orderAjax.submitForm('monthlyFilter')">
					<option value="">Select Year</option>
					<?php for($j=date('Y'); $j>=(date('Y') - 10); $j--): ?>
						<option value="<?=$j?>" <?=$mYear==$j?'selected':''?>><?=$j?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="column"></div>
			<div class="column"></div>
			<div class="column"></div>
			<div class="column">
				<?php 
					$days = cal_days_in_month(CAL_GREGORIAN,$mMonth, $mYear);
					$monthlyTotal = 0;
					for($i = 1; $i <= $days; $i++){
						$invTotal = $this->ignite_model->get_M_invTotal($i, $mMonth, $mYear);
						$amtTotal = $this->ignite_model->get_M_Total($i, $mMonth, $mYear);
						$monthlyTotal += $amtTotal['total'];
					}
				?>
				<div class="ui tag label green">
					<strong class="red">Total Sell (MMK) : <?=number_format($monthlyTotal)?></strong>
				</div>
			</div>
		</div>
		</form>
	</div>

	<div class="ui divider"></div>

	<table class="ui table">
		<thead>
			<tr>
				<th>#</th>
				<th><?=$this->lang->line('date')?></th>
				<th><?=$this->lang->line('total_inv')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
				<th class="ui right aligned"><?=$this->lang->line('gross_profit')?></th>
				<th class="ui center aligned"><?=$this->lang->line('margin')?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				for($i = 1; $i <= $days; $i++):
					$invTotal = $this->ignite_model->get_M_invTotal($i, $mMonth, $mYear);
					$amtTotal = $this->ignite_model->get_M_Total($i, $mMonth, $mYear);
			?>
				<tr>
					<td><?=$i?></td>
					<td><?=date('d M Y', strtotime($mYear.'-'.$mMonth.'-'.$i))?></td>
					<td><?=$invTotal?></td>
					<td class="ui right aligned"><?=number_format($amtTotal['total'])?></td>
					<td class="ui right aligned"><?=number_format($amtTotal['profit'])?></td>
					<td class="ui center aligned"><?=$amtTotal['pTotal'] > 0 ?round(($amtTotal['profit'] / $amtTotal['pTotal']) * 100, 2): '0'?> %</td>
				</tr>
			<?php 
				endfor;
			?>
		</tbody>
	</table>
</div>

<!-- Yearly Tab -->
<div class="ui bottom attached <?=$tab == 'yearly'?'active':''?> tab segment" data-tab="yearly">
	<!-- Chart for Yearly Data -->
	<canvas id="yChart" style="width:70vw; height: 35vh !important"></canvas>
	<div class="ui divider"></div>

	<div class="search">
		<form class="ui form" method="POST" action="reports/yearly" id="yearlyFilter">
		<div class="ui six column grid">
			
			<div class="column">
				<select name="yYear" class="ui selection dropdown" onchange="orderAjax.submitForm('yearlyFilter')">
					<option value="">Select Year</option>
					<?php for($j=date('Y'); $j>=(date('Y') - 10); $j--): ?>
						<option value="<?=$j?>" <?=$yYear==$j?'selected':''?>><?=$j?></option>
					<?php endfor; ?>
				</select>
			</div>
			<div class="column"></div>
			<div class="column"></div>
			<div class="column"></div>
			<div class="column"></div>
			<div class="column">
				<?php 
					$yearlyTotal = 0;
					
					for($k = 1; $k <= 12; $k++){
						$y_Total = $this->ignite_model->get_Y_Total($k, $yYear);
						$yearlyTotal += $y_Total['total'];
					}
				?>
				<div class="ui tag label green">
					<strong class="red">Total Sell (MMK) : <?=number_format($yearlyTotal)?></strong>
				</div>
			</div>
		</div>
		</form>
	</div>

	<div class="ui divider"></div>
	<table class="ui table">
		<thead>
			<tr>
				<th>#</th>
				<th><?=$this->lang->line('date')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_inv')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
				<th class="ui right aligned"><?=$this->lang->line('gross_profit')?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				for($k = 1; $k <= 12; $k++): 
					$y_invTotal = $this->ignite_model->get_Y_invTotal($k, $yYear);
					$y_Total = $this->ignite_model->get_Y_Total($k, $yYear);
			?>
				<tr>
					<td><?=$k?></td>
					<td><?=$this->ignite_model->getMonth($k)?></td>
					<td class="ui right aligned"><?=$y_invTotal?></td>
					<td class="ui right aligned"><?=number_format($y_Total['total'])?></td>
					<td class="ui right aligned"><?=number_format($y_Total['total'] - $y_Total['gpTotal'])?></td>
				</tr>
			<?php endfor; ?>
		</tbody>
	</table>
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
</div>
