<div class="ui grid">
	<div class="ten wide column">
		<div class="ui huge fluid icon input">
			<input name="itemCode" type="text" placeholder="Item Code .." id="saleCode" />
			<i class="barcode icon"></i>
		</div>
	</div>
	<div class="three wide column">
		<div class="ui huge fluid icon input">
			<input name="itemQty" type="number" placeholder="Qty" id="itemQty" />
			<i class="shopping cart icon"></i>
		</div>
	</div>
	<div class="three wide column">
		<div class="ui huge fluid icon input">
			<input name="itemPrice" type="number" placeholder="Price" id="itemPrice" />
			<i class="dollar sign icon"></i>
		</div>
	</div>
</div>
<div class="ui divider"></div>
<div class="ui grid">
	<div class="ten wide column">
		<div class="ui form">
			<div class="inline fields">
				<div class="field">
					<div class="ui toggle checkbox" style="padding:6px">
						<input type="checkbox" <?=$invoice->byCustomer?'checked':''?> name="byCustomer" id="byCustomer" onchange="orderAjax.byCustomer(this)">
						<label><?=$this->lang->line('by_customer')?></label>
					</div>
				</div>
			
				<div class="field">
					<select name="customer" class="ui search dropdown <?=$invoice->byCustomer?'':'disabled'?>" id="customer" onchange="orderAjax.credit(this)">
						<option value="">Select Customer</option>
						<?php foreach($customers as $customer): ?>
							<option value="<?=$customer->customerId?>" <?=$invoice->customerId == $customer->customerId?'selected':''?>><?=$customer->customerName?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="inline disabled field">
					<label><?=$this->lang->line('credit')?></label>
					<input type="text" name="credit" id="credit" readonly="" />
				</div>

				<div class="field">
					<div class="ui slider checkbox">
					  	<input type="checkbox" <?=$invoice->saleType == 'W'?'checked':''?> name="wholesale" id="wholesale" onchange="orderAjax.wholeSale(this)">
					  	<label><?=$this->lang->line('whole_sale')?></label>
					</div>
				</div>
			
			</div>
		</div>
		<div class="vr_wrapper">
			<table class="ui striped table olive">
				<thead>
					<tr>
						<th class="text-right">#</th>
						<th>Description</th>
						<th class="text-right">Price</th>
						<th class="text-right">Qty</th>
						<th class="text-right">Amount</th>
						<th>..</th>
					</tr>
				</thead>
				<tbody id="vr_preview">
					
				</tbody>
			</table>
		</div>

	</div>
	<div class="six wide column">
		<table class="ui striped table teal">
			<tr>
				<td><strong>Subtotal</strong></td>
				<td class="ui right aligned"><strong id="subTotal" >0</strong></td>
			</tr>
			<tr>
				<td><strong>Deposit</strong></td>
				<td class="ui right aligned"><strong id="depositAmt">0</strong></td>
			</tr>
			<tr>
				<td><strong>Grand Total</strong></td>
				<td class="ui right aligned"><strong id="grandTotal">0</strong></td>
			</tr>
		</table>

		<div class="ui form py-3">
			<div class="inline fields">
				<label>Payment Method : </label>

				<div class="field">
			      <div class="ui checkbox">
			        <input type="radio" name="payment" value="CSH" <?=$invoice->paymentType == 'CSH'?'checked':''?> onchange="orderAjax.payment(this)">
			        <label>Cash</label>
			      </div>
			    </div>
			    <div class="field">
			      <div class="ui checkbox">
			        <input type="radio" value="CRD" name="payment" <?=$invoice->paymentType == 'CRD'?'checked':''?> onchange="orderAjax.payment(this)">
			        <label>Credit</label>
			      </div>
			    </div>
			    <div class="field">
			      <div class="ui checkbox">
			        <input type="radio" value="MBK" name="payment" <?=$invoice->paymentType == 'MBK'?'checked':''?> onchange="orderAjax.payment(this)">
			        <label>mBanking</label>
			      </div>
			    </div>
			    <div class="field">
			      <div class="ui checkbox">
			        <input type="radio" value="COD" name="payment" <?=$invoice->paymentType == 'COD'?'checked':''?> onchange="orderAjax.payment(this)">
			        <label>COD</label>
			      </div>
			    </div>

			    <div class="field">
			    	<button class="ui button orange circular tiny icon <?=$invoice->paymentType == 'CRD'?'':'disabled'?>" id="deposit" onclick="openModal('depositModal')"><i class="ui icon plus circle"></i> Deposit</button>
			    </div>

			</div>
		</div>
		

		<button class="ui button circular green huge fluid disabled" id="btnCheckOut" onclick="orderAjax.checkOutOrder()"><?=$this->lang->line('create_invoice')?></button>

		
	</div>
</div>

<!-- Search Modal -->
<div class="ui large modal" id="itemSearch">
	<div class="itemSearch-header">
  	<div class="ui huge fluid icon input">
		<input name="itemCode" type="text" placeholder="Item Code .." autofocus id="saleItemSearch" onkeyup="orderAjax.itemSearch('retail')">
		<i class="search icon"></i>
	</div>
	</div>
  	<div class="scrolling content">
    	<table class="ui table">
        <thead>
            <tr>
                <th><?=$this->lang->line('item_name')?></th>
                <th><?=$this->lang->line('item_model')?></th>
                <th class="ui right aligned"><?=$this->lang->line('price')?></th>
                <th class="ui right aligned"><?=$this->lang->line('available_qty')?></th>
            </tr>
        </thead>
        <tbody id="searchContent">
        	
        </tbody>
        </table>
  	</div>
</div>

<!-- Cash Modal -->
<div class="ui mini modal" id="depositModal">
	<i class="close icon"></i>
  	<div class="ui icon header">
    	<i class="icon dollar sign olive"></i> Add Deposit Amount
  	</div>
  	<div class="content ui form">
  		<div class="field">
  			<label>Amount</label>
    		<input type="number" autofocus onkeypress="orderAjax.addCash(this)" />
    	</div>
  	</div>
</div>

