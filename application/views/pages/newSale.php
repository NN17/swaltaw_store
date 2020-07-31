<h3><?=$this->lang->line('new_sale')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/addStockOut', 'class="ui form" id="formStockOut"')?>

    <div class="field">
    	<?=form_label($this->lang->line('warehouse'))?>
    	<div class="ui grid">
			<div class="twelve wide column">
				<select name="warehouse" class="ui search dropdown" id="warehouseIssue" required>
					<option value="">Select</option>
					<?php foreach($warehouses as $warehouse): ?>
						<option value="<?=$warehouse['warehouseId']?>"><?=$warehouse['warehouseName']?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
    </div>
    <div class="field">
		<?=form_label($this->lang->line('item_name'))?>
		<div class="ui grid">
			<div class="twelve wide column">
				<select name="item" class="ui search dropdown" id="itemIssue" required>
					<option value="">Select</option>
					<?php foreach($items as $item):?>
					<option value="<?=$item['itemId']?>"><?=$item['itemName']?> ( <?=$item['categoryName'].' / '.$item['brandName']?> ) ~ <?=number_format($item['purchasePrice']).' '.$item['currency']?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>

    <div class="field">
        <label><?=$this->lang->line('sale_date')?></label>
        <div class="ui grid">
        	<div class="twelve wide column">
        		<?=form_input('iDate','','placeholder="'.$this->lang->line('purchase_date').'" id="datepicker" required')?>
        	</div>
        </div>
    </div>
    <div class="field">
        <label><?=$this->lang->line('quantity')?></label>
        <div class="ui grid">
        	<div class="twelve wide column">
        	<?=form_number('qty','','placeholder="'.$this->lang->line('quantity').'" min="1" id="qtyIssue" required')?>
        	</div>
        	<div class="four wide column">
        		<span id="qtyErr"></span>
        	</div>
    	</div>
    </div>
    <div class="field">
        <label><?=$this->lang->line('remark')?> ( Optional )</label>
        <?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
    </div>
    <div class="field">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
        <?=anchor('sales',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
	<?=form_close()?>
	</div>
</div>

