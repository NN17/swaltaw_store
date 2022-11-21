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
<!-- Monthly Tab -->
<div class="ui bottom attached <?=$tab == 'monthly'?'active':''?> tab segment">
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
						$monthlyTotal += $amtTotal;
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
				<th class="ui right aligned"><?=$this->lang->line('total_inv')?></th>
				<th class="ui right aligned"><?=$this->lang->line('total_amount')?></th>
				<th class="ui right aligned"><?=$this->lang->line('gross_profit')?></th>
				<th class="ui right aligned"><?=$this->lang->line('net_profit')?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$totalAmt = 0;
				$totalGross = 0;
				$totalNet = 0; 
				$invOfMonth = 0;
				for($i = 1; $i <= $days; $i++):
					$invTotal = $this->ignite_model->get_M_invTotal($i, $mMonth, $mYear);
					$amtTotal = $this->ignite_model->get_M_Total($i, $mMonth, $mYear);
					$grossProfit = $this->ignite_model->get_M_Gross($i, $mMonth, $mYear);
					$netProfit = $this->ignite_model->get_M_Net($i, $mMonth, $mYear);

					$totalAmt += $amtTotal;
					$totalGross += $grossProfit;
					$totalNet += round($netProfit, 2);
					$invOfMonth += $invTotal;
			?>
				<tr>
					<td><?=$i?></td>
					<td><?=date('d M Y', strtotime($mYear.'-'.$mMonth.'-'.$i))?></td>
					<td class="ui right aligned"><?=$invTotal?></td>
					<td class="ui right aligned"><?=number_format($amtTotal)?></td>
					<td class="ui right aligned"><?=number_format($grossProfit)?></td>
					<td class="ui right aligned"><?=number_format(round($netProfit,2),2)?></td>
				</tr>
			<?php 
				endfor;
			?>

			<tr>
				<td class="ui right aligned" colspan="2"><strong>Total</strong></td>
				<td class="ui right aligned"><strong><?=$invOfMonth?></strong></td>
				<td class="ui right aligned"><strong><?=number_format($totalAmt)?></strong></td>
				<td class="ui right aligned"><strong><?=number_format($totalGross)?></strong></td>
				<td class="ui right aligned"><strong><?=number_format(round($totalNet, 2), 2)?></strong></td>
			</tr>
		</tbody>
	</table>
</div>

