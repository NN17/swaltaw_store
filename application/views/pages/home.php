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
					  <input type="checkbox" name="public" id="byCustomer">
					  <label><?=$this->lang->line('by_customer')?></label>
					</div>
				</div>
			
				<div class="field">
					<select name="customer" class="ui search dropdown disabled" id="customer" onchange="orderAjax.credit()">
						<option value="">Select Customer</option>
						<?php foreach($customers as $customer): ?>
							<option value="<?=$customer->customerId?>"><?=$customer->customerName?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="inline disabled field">
					<label><?=$this->lang->line('credit')?></label>
					<input type="text" name="credit" id="credit" readonly="" />
				</div>

				<div class="field">
					<div class="ui slider checkbox disabled">
					  	<input type="checkbox" name="wholesale" id="wholesale">
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
				<td><strong>Gov Tax (5%)</strong></td>
				<td class="ui right aligned"><strong id="tax">0</strong></td>
			</tr>
			<tr>
				<td><strong>Grand Total</strong></td>
				<td class="ui right aligned"><strong id="grandTotal">0</strong></td>
			</tr>
		</table>
		<!-- <table class="ui striped table orange">
			<tr class="negative">
				<td class=""><strong>Credit</strong></td>
				<td class="ui right aligned"><strong id="credit">0</strong></td>
			</tr>
			<tr class="disabled" id="addCash" onclick="openModal('cashModal')">
				<td class=""><strong>Cash</strong></td>
				<td class="ui right aligned"><strong id="cash">0</strong></td>
			</tr>
			<tr class="">
				<td class=""><strong>Total Due</strong></td>
				<td class="ui right aligned"><strong id="due">0</strong></td>
			</tr>
		</table> -->

		<button class="ui button circular green huge fluid disabled" id="btnCheckOut" onclick="orderAjax.checkOutOrder()"><?=$this->lang->line('create_invoice')?></button>

		<div class="ui segment all-link text-center">
			<?php foreach($allLink as $link): ?>
				<a href="<?=$link->machine?>" class="ui button circular icon <?=$link->color?>" id="popup_link" data-content="<?=$link->linkName?>"><i class="icon <?=$link->icon_class?> "></i></a>
			<?php endforeach; ?>
		</div>
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
<div class="ui mini modal" id="cashModal">
	<i class="close icon"></i>
  	<div class="ui icon header">
    	<i class="icon dollar sign olive"></i> In Cash
  	</div>
  	<div class="content ui form">
  		<div class="field">
  			<label>Amount</label>
    		<input type="number" value=0 id="cashAmt" autofocus onkeypress="orderAjax.addCash()" />
    	</div>
  	</div>
</div>

