<h3><?=$this->lang->line('new_purchase')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/addPurchase', 'class="ui form"')?>

    <div class="field">
		<?=form_label($this->lang->line('item_name'))?>
		<div class="ui grid">
			<div class="twelve wide column">
				<select name="item" class="ui search dropdown" id="item" required>
					<option value="">Select</option>
					<?php foreach($items as $item):?>
					<option value="<?=$item['itemId']?>"><?=$item['itemName']?> ( <?=$item['categoryName'].' / '.$item['brandName']?> ) <?=$item['price']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="four wide column">
				<a href="new-item/create-purchase" class="ui icon circular button green"><i class="icon plus"></i></a>
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
						<option value="<?=$warehouse['warehouseId']?>"><?=$warehouse['warehouseName']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="four wide column">
				<a href="javascript:void(0)" class="ui icon circular button pink" onclick="warehouse_modal()"><i class="icon plus"></i></a>
			</div>
		</div>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('voucher'))?>
        <div class="ui grid">
            <div class="twelve wide column">
                <select name="voucher" class="ui search dropdown" required>
                    <option value="">Select</option>
                    <?php foreach($vouchers as $voucher): ?>
                        <option value="<?=$voucher->voucherId?>"><?=$voucher->vSerial?> ( <?=$this->ignite_model->supplier($voucher->supplier)?> )</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="four wide column">
                <a href="javascript:void(0)" class="ui icon circular button purple" onclick="openModal('voucher')"><i class="icon plus"></i></a>
            </div>
        </div>
    </div>

    <div class="field">
        <label><?=$this->lang->line('date')?></label>
        <?=form_input('pDate','','placeholder="'.$this->lang->line('purchase_date').'" id="datepicker" required')?>
    </div>
    <div class="field">
        <label><?=$this->lang->line('item_count_type')?></label>
        <select name="countType" class="ui search dropdown" id="countType" required>
            <option value="">Select</option>
        </select>
    </div>
    <div class="field">
        <label><?=$this->lang->line('quantity')?></label>
        <?=form_number('qty','','placeholder="'.$this->lang->line('quantity').'" min="1" required')?>
    </div>
    <div class="field">
        <label><?=$this->lang->line('remark')?> ( Optional )</label>
        <?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
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
    <i class="close icon"></i>
  	<div class="header">
    	<?=$this->lang->line('new_warehouse')?>
  	</div>
  	<div class="content">
    <?=form_open('ignite/addWarehouse', 'class="ui form"')?>
        <input type="hidden" name="referer" value="create-purchase" />
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

<!-- Voucher Modal -->
<div class="ui tiny modal" id="voucher">
    <i class="close icon"></i>
    <div class="header">
        <?=$this->lang->line('new_voucher')?>
    </div>
    <div class="content">
    <?=form_open('ignite/addVoucher', 'class="ui form"')?>
        <input type="hidden" name="referer" value="create-purchase" />
        <div class="field">
            <label><?=$this->lang->line('voucher_date')?></label>
            <?=form_input('vDate','','placeholder="'.$this->lang->line('voucher_date').'" required id="datepicker"')?>
        </div>
        <div class="field">
            <label><?=$this->lang->line('voucher_serial')?></label>
            <?=form_input('vSerial','','placeholder="'.$this->lang->line('voucher_serial').'" required')?>
        </div>
        <div class="field">
            <label><?=$this->lang->line('extra_charges')?></label>
            <select name="extCharge" class="ui dropdown">
                <option> -- Select Extra Charges -- </option>
                <?php foreach($extCharges as $ext): ?>
                    <option value="<?=$ext->chargeId?>"><?=$ext->chargeTitle?> ( <?=$ext->chargeAmount?> )</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label><?=$this->lang->line('supplier')?></label>
            <select name="supplier" class="ui search dropdown">
                <option>-- Select Supplier --</option>
                <?php foreach($suppliers as $supplier): ?>
                    <option value="<?=$supplier->supplierId?>"><?=$supplier->supplierName?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label><?=$this->lang->line('remark')?> ( Optional )</label>
            <?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
        </div>
    <div class="actions">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button green"')?>
    </div>
    <?=form_close()?>
</div>