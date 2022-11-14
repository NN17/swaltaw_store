<div class="ui clearing segment teal">
	<h3 class="ui left floated header teal"><?=$this->lang->line('reports')?></h3>
</div>

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
	<canvas id="dailyChart" data-month="<?=date('m', strtotime($dDate))?>" data-token="<?=$this->security->get_csrf_hash()?>" style="width:70vw; height: 35vh"></canvas>

	<div class="ui divider"></div>
	<div class="filter ui form">
		<div class="ui grid">
			<div class="four wide column">
				<?=form_open('reports/daily', 'id="dailyFilter"')?>
				<div class="ui icon input">
				  	<input type="text" name="dailyDate" placeholder="Date" value="<?=$dDate?>" id="datepicker2" onchange="orderAjax.submitForm('dailyFilter')">
				    <i class="ui icon calendar alternate outline"></i>
				</div>
				<?=form_close()?>
			</div>
			<div class="twelve wide column text-right">
				<div class="ui tag label blue">
					<strong class="red">Total item : <?=$dailyItems?></strong>
				</div>
				<div class="ui tag label green">
					<strong class="red">Total Sell (MMK) : <?=number_format($dailyTotal)?></strong>
				</div>
			</div>
		</div>
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
				<th class="ui right aligned"><?=$this->lang->line('net_profit')?></th>
				<th class="ui center aligned"><?=$this->lang->line('margin')?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				$i = 1;
				$totalAmt = 0;
				$totalGross = 0;
				$totalNet = 0;
				foreach($dailyData as $dRow):
					$dTotalItems = $this->ignite_model->get_dTotalItems($dRow->invoiceId);
					$dTotalAmount = $this->ignite_model->get_dTotalAmount($dRow->invoiceId);
					$dGrossProfit = $this->ignite_model->get_dGrossProfit($dRow->invoiceId);
					$dNetProfit = $this->ignite_model->get_dNetProfit($dRow->invoiceId);
					$dProfitMargin = $this->ignite_model->get_profitMargin($dRow->invoiceId);

					$totalAmt += $dTotalAmount;
					$totalGross += $dGrossProfit - $dRow->discountAmt;
					$totalNet += round($dNetProfit - $dRow->discountAmt, 2);
			?>
				<tr>
					<td class="ui right aligned"><?=$i?></td>
					<td><?=date('d M Y',strtotime($dRow->created_date))?></td>
					<td><a href="javascript:void(0)" onclick="igniteAjax.detailInv('<?=$dRow->invoiceId?>')"><?=$dRow->invoiceSerial?></a></td>
					<td class="ui right aligned"><?=number_format($dTotalItems)?></td>
					<td class="ui right aligned"><?=number_format($dTotalAmount)?></td>
					<td class="ui right aligned"><?=number_format($dGrossProfit - $dRow->discountAmt)?></td>
					<td class="ui right aligned"><?=number_format($dNetProfit - $dRow->discountAmt, 2)?></td>
					<td class="ui center aligned"><?=$dProfitMargin?> %</td>
				</tr>
			<?php
				$i ++; 
				endforeach; 
			?>

		</tbody>
	</table>
</div>

<div class="ui clearing segment teal text-right">
	<strong>Total Sale Amount : <?=number_format($totalAmt)?></strong> , &nbsp;
	<strong>Total Gross Profit : <?=number_format($totalGross)?></strong> , &nbsp;
	<strong>Total Net Profit : <?=number_format($totalNet)?></strong>
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
