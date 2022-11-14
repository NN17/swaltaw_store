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
				<th class="ui right aligned"><?=$this->lang->line('net_profit')?></th>
				<th class="ui center aligned"><?=$this->lang->line('margin')?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				for($i = 1; $i <= $days; $i++):
					$invTotal = $this->ignite_model->get_M_invTotal($i, $mMonth, $mYear);
					$amtTotal = $this->ignite_model->get_M_Total($i, $mMonth, $mYear);
					$invoices = $this->ignite_model->get_daily_invoices($i, $mMonth, $mYear);
					$nProfitTotal = 0;
					foreach($invoices as $inv){
							$mNetProfit = $this->ignite_model->get_dNetProfit($inv->invoiceId, $inv->saleType);
							$nProfitTotal += $mNetProfit - $inv->discountAmt;
					}
					
			?>
				<tr>
					<td><?=$i?></td>
					<td><?=date('d M Y', strtotime($mYear.'-'.$mMonth.'-'.$i))?></td>
					<td><?=$invTotal?></td>
					<td class="ui right aligned"><?=number_format($amtTotal['total'])?></td>
					<td class="ui right aligned"><?=number_format($amtTotal['profit'])?></td>
					<td class="ui right aligned"><?=number_format($nProfitTotal)?></td>
					<td class="ui center aligned"><?=$amtTotal['pTotal'] > 0 ?round(($amtTotal['profit'] / $amtTotal['pTotal']) * 100, 2): '0'?> %</td>
				</tr>
			<?php 
				endfor;
			?>
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