<div class="ui two column centered grid">
	<div class="column">
		<div class="ui segment teal">
			<div class="text-center">
				<h4>Date : <?=$invoice->created_date.' ( '.date('h:i A',strtotime($invoice->created_time)).' )'?></h4>
				<h3>Serial : <?=$invoice->invoiceSerial?></h3>
			</div>

			<?php if(isset($customer->customerName)): ?>
			<label>Customer Name - <?=$customer->customerName?></label><br>
			<label>Phone Number - <?=$customer->phone1?></label>
			<?php endif; ?>

			<table class="ui table">
				<thead>
					<tr>
						<th class="ui right aligned">#</th>
						<th>Description</th>
						<th class="ui right aligned">Price</th>
						<th class="ui right aligned">Qty</th>
						<th class="ui right aligned">Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$total = 0;
						foreach($items as $item):
							$amount = $item->itemQty * $item->itemPrice;
							$total += $amount;
					?>
						<tr>
							<td class="ui right aligned"><?=$i?></td>
							<td><?=$item->itemName?></td>
							<td class="ui right aligned"><?=number_format($item->itemPrice)?></td>
							<td class="ui right aligned"><?=$item->itemQty?></td>
							<td class="ui right aligned"><?=number_format($amount)?></td>
						</tr>
					<?php
						$i ++;
						endforeach; 
					?>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Total</strong></td>
						<td class="ui right aligned"><strong><?=number_format($total)?></strong></td>
					</tr>
					<?php if(isset($credit->balance)): ?>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Credit</strong></td>
						<td class="ui right aligned"><strong><?=number_format($credit->balance - $total)?></strong></td>
					</tr>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Cash</strong></td>
						<td class="ui right aligned"><strong><?=number_format($credit->cashAmount)?></strong></td>
					</tr>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Grand Total</strong></td>
						<td class="ui right aligned"><strong><?=number_format($credit->balance)?></strong></td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>

			<div class="text-center">
				<div class="three ui buttons">
					<a href="home" class="ui button large red">Exit</a>
					<a href="" class="ui button large green">Print Receipt</a>
				</div>
			</div>
		</div>
	</div>
</div>