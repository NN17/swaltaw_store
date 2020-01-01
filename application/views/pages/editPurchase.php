<h3><?=$this->lang->line('editPurchase')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/updatePurchase/'.$purchase['purchaseId'], 'class="ui form"')?>

    <div class="field">
		<?=form_label($this->lang->line('item_name'))?>
		<div class="ui grid">
			<div class="twelve wide column">
				<select name="item" class="ui search dropdown" id="item" required>
					<option value="">Select</option>
					<?php foreach($items as $item):?>
					<option value="<?=$item['itemId']?>" <?=$item['itemId'] == $purchase['itemId']?'selected':''?>><?=$item['itemName']?> ( <?=$item['categoryName'].' / '.$item['brandName']?> ) ~ <?=number_format($item['purchasePrice']).' '.$item['currency']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="four wide column">
				<a href="new-item/edit-purchase" class="ui icon circular button green"><i class="icon plus"></i></a>
			</div>
		</div>
	</div>

    <div class="field">
    	<?=form_label($this->lang->line('warehouse'))?>
    	<div class="ui grid">
			<div class="twelve wide column">
				<select name="warehouse" class="ui search dropdown" required>
					<option value="">Select</option>
					<?php foreach($warehouses as $warehouse): ?>
						<option value="<?=$warehouse['warehouseId']?>" <?=$warehouse['warehouseId'] == $purchase['warehouseId']?'selected':''?>><?=$warehouse['warehouseName']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="four wide column">
				<a href="javascript:void(0)" class="ui icon circular button pink" onclick="warehouse_modal()"><i class="icon plus"></i></a>
			</div>
		</div>
    </div>

    <div class="field">
        <label><?=$this->lang->line('date')?></label>
        <?=form_input('pDate',$purchase['purchaseDate'],'placeholder="'.$this->lang->line('purchase_date').'" id="datepicker" required')?>
    </div>
    <div class="field">
        <label><?=$this->lang->line('quantity')?></label>
        <?=form_number('qty',$purchase['quantity'],'placeholder="'.$this->lang->line('quantity').'" required')?>
    </div>
    <div class="field">
        <label><?=$this->lang->line('remark')?> ( Optional )</label>
        <?=form_textarea('remark',$purchase['remark'],'placeholder="'.$this->lang->line('remark').'"')?>
    </div>
    <div class="field">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
        <?=anchor('purchase/0',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
	<?=form_close()?>
	</div>
</div>

<!-- Warehouse Modal -->
<div class="ui tiny modal warehouse">
  	<div class="header">
    	<?=$this->lang->line('new_warehouse')?>
  	</div>
  	<div class="content">
    <?=form_open('ignite/addWarehouse', 'class="ui form"')?>
    	<input type="hidden" name="referer" value="edit-purchase/<?=$purchase['purchaseId']?>" />
        <div class="field">
            <?=form_label($this->lang->line('name'))?>
            <?=form_input('name', '','placeholder="'.$this->lang->line('warehouse_name').'" required')?>
        </div>

        <div class="field">
            <?=form_label($this->lang->line('serial'))?>
            <?=form_number('serial','','placeholder="'.$this->lang->line('warehouse_serial').'" required')?>
        </div>

        <div class="field">
            <?=form_label($this->lang->line('remark'))?>
            <?=form_textarea('remark','','placeholder="'.$this->lang->line('warehouse_remark').'"')?>
        </div>

        <div class="field">
            <label><?=$this->lang->line('status')?></label>
            <div class="inline">
                <div class="ui toggle checkbox">
                <input name="active" checked="TRUE" type="checkbox" tabindex="0" class="hidden">
                <label><?=$this->lang->line('active')?></label>
                </div>
            </div>
        </div>
  	</div>
    <div class="actions">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button green"')?>
    </div>
    <?=form_close()?>
</div>