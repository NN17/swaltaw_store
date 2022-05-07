<div class="ui two column centered grid">
	<div class="column">
		<div class="ui segment teal">
			<div class="text-center">
				<h4>Date : <?=date('d-M-Y',strtotime($invoice->created_date)).' ( '.date('h:i A',strtotime($invoice->created_time)).' )'?></h4>
				<h3>Serial : <?=$invoice->invoiceSerial?></h3>
			</div>

			<?php 
				if($invoice->byCustomer):
					$customer = $this->ignite_model->get_limit_data('customers_tbl', 'customerId', $invoice->customerId)->row();
			?>
			<div class="customer-info">
			<label><strong>Customer Name - <?=$customer->customerName?></strong></label><br>
			<label>Phone Number - <?=$customer->phone1?></label>
			</div>	
			<?php 
				endif; 
			?>

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
							<td class="ui right aligned" style="width:200px"><?=number_format($amount)?></td>
						</tr>
					<?php
						$i ++;
						endforeach; 
					?>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Sub Total</strong></td>
						<td class="ui right aligned"><strong><?=number_format($total)?></strong></td>
					</tr>
					<tr>
						<td colspan="4" class="ui right aligned">
							<?php if(count($discounts) > 0): ?>
							<a href="javascript:void(0);" id="dis" onclick="openModal('discountModal')" >
								<i class="ui icon plus pink"></i>
							</a>&nbsp;&nbsp;&nbsp; 
							<?php endif; ?>
							<strong>Discount</strong>
						</td>
						<td class="ui right aligned red"><strong id="disTotal"><?=$invoice->discountAmt?></strong></td>
					</tr>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Grand Total</strong></td>
						<td class="ui right aligned"><strong id="gTotal"><?=number_format($total-$invoice->discountAmt)?></strong></td>
					</tr>
					<?php if($invoice->paymentType != "CRD"): ?>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Payment</strong></td>
						<td class="ui right aligned">
							<?php if($invoice->payment == 0): ?>
							<input type="number" name="payment" id="payment" autofocus onkeydown="igniteAjax.refundPayment(event, <?=$invoice->invoiceId?>)" />
							<?php else: ?>
								<strong><?=number_format($invoice->payment)?></strong>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Refund</strong></td>
						<td class="ui right aligned">
							<strong><?=number_format($invoice->payment > 0?$invoice->payment-($total-$invoice->discountAmt):0)?></strong>
						</td>
					</tr>
					<?php else: ?>
					<?php 
						if(($total - $invoice->discountAmt) > $invoice->depositAmt) {
							$balance = ($total - $invoice->discountAmt) - $invoice->depositAmt;
						}
							else{
								$balance = $invoice->depositAmt - ($total - $invoice->discountAmt);
							}
					?>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Deposit</strong></td>
						<td class="ui right aligned"><strong><?=number_format($invoice->depositAmt)?></strong></td>
					</tr>
					<tr>
						<td colspan="4" class="ui right aligned"><strong>Total Balance</strong></td>
						<td class="ui right aligned"><strong><?=number_format($balance)?></strong></td>
					</tr>
					
					<?php endif; ?>
				</tbody>
			</table>


			<div class="text-center">
				<div class="three ui buttons">
					<a href="home" class="ui button large red">Exit</a>
					<a href="print/<?=$invoice->invoiceId?>" class="ui button large green">Print Receipt</a>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Discount Modal -->
<div class="ui mini modal" id="discountModal">
	<i class="close icon"></i>
  	<div class="ui icon header">
    	<i class="icon tags orange"></i> Discount
  	</div>
  	<div class="content ui form">
  		<div class="field">
  			<label>Discount Type</label>
  			<select class="ui search dropdown" name="disType" id="disType" data-total="<?=$total?>">
  				<option>-- Select Discount --</option>
  				<?php foreach($discounts as $discount): ?>
  					<option value="<?=$discount->discountId?>"><?=$discount->discountTitle?></option>
  				<?php endforeach; ?>
  			</select>
    	</div>
  		<div class="field disabled" id="disAmt">
  			<label>Amount</label>
    		<input type="number" value=0 />
    	</div>
	  	<div class="action text-center">
	  		<button class="ui circular button green" onclick="orderAjax.addDiscount(<?=$total?>, <?=$invoice->invoiceId?>)">Add Discount</button>
	  	</div>
  	</div>
</div>