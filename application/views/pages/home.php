<div class="ui grid">
	<div class="ten wide column">
		<div class="ui huge fluid icon input">
			<input name="itemCode" type="text" placeholder="Item Code .." id="saleCode">
			<i class="barcode icon"></i>
		</div>
	</div>
	<div class="three wide column">
		<div class="ui huge fluid icon input">
			<input name="itemQty" type="number" placeholder="Qty" id="itemQty">
			<i class="shopping cart icon"></i>
		</div>
	</div>
	<div class="three wide column">
		<div class="ui huge fluid icon input">
			<input name="itemPrice" type="number" placeholder="Price" id="itemPrice">
			<i class="dollar sign icon"></i>
		</div>
	</div>
</div>
<div class="ui divider"></div>
<div class="ui grid">
	<div class="ten wide column">

		<div class="vr_wrapper">
			<table class="ui table olive">
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
		<table class="ui table">
			<tr>
				<td><strong>Subtotal</strong></td>
				<td class="ui right aligned"><strong id="subTotal">0</strong></td>
			</tr>
			<tr>
				<td><strong>Discount</strong></td>
				<td class="ui right aligned"><strong id="discount">0</strong></td>
			</tr>
			<tr>
				<td><strong>Tax (5%)</strong></td>
				<td class="ui right aligned"><strong id="tax">0</strong></td>
			</tr>
			<tr>
				<td><strong>Grand Total</strong></td>
				<td class="ui right aligned"><strong id="grandTotal">0</strong></td>
			</tr>
		</table>
	</div>
</div>

<!-- Search Modal -->
<div class="ui large modal" id="itemSearch">
	<div class="itemSearch-header">
  	<div class="ui huge fluid icon input">
		<input name="itemCode" type="text" placeholder="Item Code .." autofocus id="saleItemSearch" onkeyup="orderAjax.itemSearch()">
		<i class="search icon"></i>
	</div>
	</div>
  	<div class="scrolling content">
    	<table class="ui table">
        <thead>
            <tr>
                <th><?=$this->lang->line('item_name')?></th>
                <th><?=$this->lang->line('item_model')?></th>
                <th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
                <th class="ui right aligned"><?=$this->lang->line('retail_price')?></th>
                <th class="ui right aligned"><?=$this->lang->line('wholesale_price')?></th>
                <th class="ui right aligned"><?=$this->lang->line('available_qty')?></th>
            </tr>
        </thead>
        <tbody id="searchContent">
        	
        </tbody>
        </table>
  	</div>
</div>