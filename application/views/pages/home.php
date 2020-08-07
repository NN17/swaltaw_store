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
<div class="ui grid">
	<div class="ten wide column">

		<div class="vr_wrapper">
			<table class="ui table">
				<thead>
					<tr>
						<th class="text-right">#</th>
						<th>Description</th>
						<th>Qty</th>
						<th>Price</th>
						<th>Amount</th>
					</tr>
				</thead>
			</table>
		</div>

	</div>
	<div class="six wide column" id="currency">Six</div>
</div>

<div class="ui large modal" id="itemSearch">
	<div class="itemSearch-header">
  	<div class="ui huge fluid icon input">
		<input name="itemCode" type="text" placeholder="Item Code .." autofocus id="saleItemSearch">
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
            </tr>
        </thead>
        <tbody id="searchContent">
        	
        </tbody>
        </table>
  	</div>
</div>