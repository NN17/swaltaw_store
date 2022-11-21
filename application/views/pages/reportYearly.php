<div class="ui top attached tabular menu grey">
  <div class="<?=$tab == 'daily'?'active':''?> item" ><a href="reports/daily">Daily</a></div>
  <div class="<?=$tab == 'monthly'?'active':''?> item"><a href="reports/monthly">Monthly</a></div>
  <div class="<?=$tab == 'yearly'?'active':''?> item"><a href="reports/yearly">Yearly</a></div>

  <div class="item right floated">
  	<i class="ui icon square light-green"></i><label>Total Sale</label> &nbsp;
  	<i class="ui icon square slate-grey"></i><label>Gross Profit</label> &nbsp;
  	<i class="ui icon square orange"></i><label>Net Profit</label>
  </div>
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
					<?php for($x=date('Y'); $x>=(date('Y') - 10); $x--): ?>
						<option value="<?=$x?>" <?=$yYear==$x?'selected':''?>><?=$x?></option>
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
					
					for($y = 1; $y <= 12; $y++){
						$y_Total = $this->ignite_model->get_Y_Total($y, $yYear);
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
				<th class="ui right aligned"><?=$this->lang->line('net_profit')?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$totalInv = 0;
				$totalAmt = 0;
				$totalGross  = 0;
				$totalNet = 0;
				for($y = 1; $y <= 12; $y++): 
					$y_invTotal = $this->ignite_model->get_Y_invTotal($y, $yYear);
					$y_Total = $this->ignite_model->get_Y_Total($y, $yYear);
					$y_Gross = $this->ignite_model->get_Y_gross($y, $yYear);
					$y_Net = $this->ignite_model->get_Y_net($y, $yYear);

					$totalInv += $y_invTotal;
					$totalAmt += $y_Total['total'];
					$totalGross += $y_Gross;
					$totalNet += $y_Net;
			?>
				<tr>
					<td><?=$y?></td>
					<td><?=$this->ignite_model->getMonth($y)?></td>
					<td class="ui right aligned"><?=$y_invTotal?></td>
					<td class="ui right aligned"><?=number_format($y_Total['total'])?></td>
					<td class="ui right aligned"><?=number_format($y_Gross)?></td>
					<td class="ui right aligned"><?=number_format(round($y_Net, 2), 2)?></td>
				</tr>
			<?php endfor; ?>

			<tr>
				<td class="ui right aligned" colspan="2"><strong>Total</strong></td>
				<td class="ui right aligned"><strong><?=$totalInv?></strong></td>
				<td class="ui right aligned"><strong><?=number_format($totalAmt)?></strong></td>
				<td class="ui right aligned"><strong><?=number_format($totalGross)?></strong></td>
				<td class="ui right aligned"><strong><?=number_format(round($totalNet, 2), 2)?></strong></td>
			</tr>
		</tbody>
	</table>
</div>