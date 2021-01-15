<h3><?=$this->lang->line('define_price') .' ( '.$item->itemName.' )'?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="seven wide column">
<!-- Price Information (Purchase) -->
    <h4 class="ui olive dividing header">
    	Price Information (Purchase)
    </h4>

    <a href="javascript:void(0)" class="ui button olive compact icon" onclick="purchase_price_modal()" ><i class="icon plus"></i></a>
    <table class="ui table mid-margin">
    	<thead>
    		<tr>
    			<th><?=$this->lang->line('item_count_type')?></th>
    			<th class="ui right aligned"><?=$this->lang->line('quantity')?></th>
    			<th class="ui right aligned"><?=$this->lang->line('item_price')?></th>
    			<th>..</th>
    		</tr>
    	</thead>
    	<tbody id="p_body">
    		<tr>
    			<td>-</td>
    			<td class="ui right aligned">-</td>
    			<td class="ui right aligned">-</td>
    			<td>-</td>
    		</tr>
    	</tbody>
    </table>
   	
   	<!-- Price Information (Sale) -->
   	<h4 class="ui green dividing header">
    	Price Information (Sale)
    </h4>

    <a href="javascript:void(0)" class="ui button green compact icon" onclick="sale_price_modal()"><i class="icon plus"></i></a>
    <table class="ui table mid-margin">
    	<thead>
    		<tr>
    			<th><?=$this->lang->line('item_count_type')?></th>
    			<th class="ui right aligned"><?=$this->lang->line('quantity')?></th>
    			<th class="ui right aligned"><?=$this->lang->line('item_price')?></th>
    			<th>..</th>
    		</tr>
    	</thead>
    	<tbody id="s_body">
    		<tr>
    			<td>-</td>
    			<td class="ui right aligned">-</td>
    			<td class="ui right aligned">-</td>
    			<td>-</td>
    		</tr>
    	</tbody>
    </table>

    <div class="ui center aligned">
    	<button class="ui button green" onclick="priceAjax.savePrice(<?=$item->itemId?>)">Save</button>
    </div>
	</div>
</div>

<!-- Purchase Price Modal -->
<div class="ui tiny modal purchase">
	<div class="header">
	    <?=$this->lang->line('new_count_type')?> (Purchase)
	</div>
	<div class="content ui form">
		    <div class="field">
		    	<label><?=$this->lang->line('item_count_type')?></label>
		    	<?=form_input('countType','','placeholder="'.$this->lang->line('item_count_type').'" id="p_countType" required')?>
		    </div>

		    <div class="field">
		    	<label><?=$this->lang->line('quantity')?></label>
		    	<?=form_number('qty','','placeholder="'.$this->lang->line('quantity').'" id="p_qty" required')?>
		    </div>

		    <div class="field">
		    	<label><?=$this->lang->line('item_price')?></label>
		    	<?=form_number('price','','placeholder="'.$this->lang->line('item_price').'" id="p_price" required')?>
		    </div>

		    <div class="field">
		    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
		    	<?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'" id="p_remark"')?>
		    </div>
	</div>
	<div class="actions">
	    <button class="ui button olive" onclick="priceAjax.addPrice('P')">Save</button>
	</div>
</div>

<!-- Sale Price Modal -->
<div class="ui tiny modal sale green">
	<div class="header">
	    <?=$this->lang->line('new_count_type')?> (Sale)
	</div>
	<div class="content ui form">
		    <div class="field">
		    	<label><?=$this->lang->line('item_count_type')?></label>
		    	<?=form_input('countType','','placeholder="'.$this->lang->line('item_count_type').'" id="s_countType" required')?>
		    </div>

		    <div class="field">
		    	<label><?=$this->lang->line('quantity')?></label>
		    	<?=form_number('qty','','placeholder="'.$this->lang->line('quantity').'" id="s_qty" required')?>
		    </div>

		    <div class="field">
		    	<label><?=$this->lang->line('item_price')?></label>
		    	<?=form_number('price','','placeholder="'.$this->lang->line('item_price').'" id="s_price" required')?>
		    </div>

		    <div class="field">
		    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
		    	<?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'" id="s_remark"')?>
		    </div>
	</div>
	<div class="actions">
	    <button class="ui button green" onclick="priceAjax.addPrice('S')">Save</button>
	</div>
</div>