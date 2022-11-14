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
				<th class="ui right aligned"><?=$this->lang->line('net_profit')?></th>
				<th class="ui right aligned"><?=$this->lang->line('gross_profit')?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
				for($k = 1; $k <= 12; $k++): 
					$y_invTotal = $this->ignite_model->get_Y_invTotal($k, $yYear);
					$y_Total = $this->ignite_model->get_Y_Total($k, $yYear);
					$yInvoices = $this->ignite_model->get_monthly_invoices($k, $yYear);
					$yProfitTotal = 0;
					foreach($yInvoices as $yInv){
							$yNetProfit = $this->ignite_model->get_dNetProfit($yInv->invoiceId, $yInv->saleType);
							$yProfitTotal += $yNetProfit - $yInv->discountAmt;
					}
			?>
				<tr>
					<td><?=$k?></td>
					<td><?=$this->ignite_model->getMonth($k)?></td>
					<td class="ui right aligned"><?=$y_invTotal?></td>
					<td class="ui right aligned"><?=number_format($y_Total['total'])?></td>
					<td class="ui right aligned"><?=number_format($yProfitTotal)?></td>
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